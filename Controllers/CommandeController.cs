// Controllers/CommandeController.cs
using Microsoft.AspNetCore.Mvc;
using brasilBugerC_.Helpers;
using brasilBugerC_.Models.Enums;
using brasilBugerC_.Models.ViewModels;
using brasilBugerC_.Services;

namespace brasilBugerC_.Controllers;

public class CommandeController : Controller
{
    private readonly ICommandeService _commandeService;
    private readonly IPaiementService _paiementService;
    private readonly IPanierService _panierService;
    private readonly SessionHelper _sessionHelper;

    public CommandeController(
        ICommandeService commandeService,
        IPaiementService paiementService,
        IPanierService panierService,
        SessionHelper sessionHelper)
    {
        _commandeService = commandeService;
        _paiementService = paiementService;
        _panierService = panierService;
        _sessionHelper = sessionHelper;
    }

    // ============================================
    // ACTION PANIER - Accessible sans connexion
    // ============================================
    [HttpGet]
    public IActionResult Panier()
    {
        var panier = _panierService.GetPanier();
        
        // Vérifier si le client est connecté pour afficher le bon bouton
        ViewBag.IsAuthenticated = _sessionHelper.IsAuthenticated();
        
        return View(panier);
    }

    [HttpPost]
    public IActionResult UpdateQuantite(string itemKey, int quantite)
    {
        _panierService.UpdateQuantite(itemKey, quantite);
        return RedirectToAction(nameof(Panier));
    }

    [HttpPost]
    public IActionResult RemoveItem(string itemKey)
    {
        _panierService.RemoveItem(itemKey);
        TempData["SuccessMessage"] = "Article retiré du panier";
        return RedirectToAction(nameof(Panier));
    }

    [HttpPost]
    public IActionResult Clear()
    {
        _panierService.ClearPanier();
        TempData["SuccessMessage"] = "Panier vidé";
        return RedirectToAction(nameof(Panier));
    }

    // ============================================
    // ACTION CHECKOUT - Nécessite connexion
    // ============================================
    [HttpGet]
    public async Task<IActionResult> Checkout()
    {
        // Vérifier si le client est connecté
        var client = _sessionHelper.GetCurrentClient();
        
        if (client == null)
        {
            TempData["ErrorMessage"] = "Veuillez vous connecter pour finaliser votre commande";
            return RedirectToAction("Login", "Account", new { returnUrl = Url.Action("Checkout") });
        }

        var panier = _panierService.GetPanier();
        
        if (panier.Items.Count == 0)
        {
            TempData["ErrorMessage"] = "Votre panier est vide";
            return RedirectToAction(nameof(Panier));
        }

        var viewModel = new CheckoutViewModel
        {
            Panier = panier,
            Zones = await _commandeService.GetZonesAsync()
        };

        return View(viewModel);
    }

    [HttpPost]
    public async Task<IActionResult> Checkout(CheckoutViewModel model)
    {
        if (!ModelState.IsValid)
    {
        // Ce code va lister exactement quelle propriété pose problème dans ta console
        var errors = ModelState.Values.SelectMany(v => v.Errors);
        foreach (var error in errors)
        {
            Console.WriteLine("ERREUR VALIDATION: " + error.ErrorMessage);
        }
    }
        var client = _sessionHelper.GetCurrentClient();
        if (client == null)
            return RedirectToAction("Login", "Account");

        var panier = _panierService.GetPanier();
        
        if (panier.Items.Count == 0)
        {
            TempData["ErrorMessage"] = "Votre panier est vide";
            return RedirectToAction(nameof(Panier));
        }

        // ⚠️ CORRECTION : Nettoyer les valeurs selon le type de récupération
        int? zoneId = null;
        string? adresseLivraison = null;
        
        if (model.TypeRecuperation == TypeRecuperation.Livraison)
        {
            zoneId = model.ZoneId;
            adresseLivraison = model.AdresseLivraison;
            
            if (!zoneId.HasValue || zoneId.Value <= 0)
            {
                ModelState.AddModelError("ZoneId", "Veuillez sélectionner une zone de livraison");
            }
            
            if (string.IsNullOrWhiteSpace(adresseLivraison))
            {
                ModelState.AddModelError("AdresseLivraison", "Veuillez saisir une adresse de livraison");
            }
        }

        if (!ModelState.IsValid)
        {
            model.Panier = panier;
            model.Zones = await _commandeService.GetZonesAsync();
            return View(model);
        }

        // ✅ Validation du mode de paiement
        if (!model.ModePaiement.HasValue)
        {
            TempData["ErrorMessage"] = "Veuillez sélectionner un mode de paiement";
            ModelState.AddModelError("ModePaiement", "Le mode de paiement est requis");
            model.Panier = panier;
            model.Zones = await _commandeService.GetZonesAsync();
            return View(model);
        }

        // ✅ CORRECTION : Déclarer explicitement les types du tuple
        (bool success, int commandeId, string message) = await _commandeService.CreateCommandeAsync(
            client.Id,
            panier,
            model.TypeRecuperation ?? TypeRecuperation.SurPlace, // Assurer une valeur non-nullable
            zoneId,
            adresseLivraison
        );

        if (!success)
        {
            TempData["ErrorMessage"] = message;
            ModelState.AddModelError(string.Empty, message);
            model.Panier = panier;
            model.Zones = await _commandeService.GetZonesAsync();
            return View(model);
        }

        decimal montantTotal = panier.Total;
        if (model.TypeRecuperation == TypeRecuperation.Livraison && zoneId.HasValue)
        {
            var zones = await _commandeService.GetZonesAsync();
            var zone = zones.FirstOrDefault(z => z.Id == zoneId.Value);
            if (zone != null)
                montantTotal += zone.PrixLivraison;
        }

        await _paiementService.CreatePaiementAsync(commandeId, montantTotal, model.ModePaiement.Value);
        _panierService.ClearPanier();

        TempData["SuccessMessage"] = "Commande passée avec succès";
        return RedirectToAction("Confirmation", new { id = commandeId });
    }

    [HttpGet]
    public async Task<IActionResult> Confirmation(int id)
    {
        var client = _sessionHelper.GetCurrentClient();
        
        if (client == null)
        {
            return RedirectToAction("Login", "Account");
        }

        var commande = await _commandeService.GetCommandeDetailsAsync(id);
        
        if (commande == null || commande.IdClient != client.Id)
        {
            return NotFound();
        }

        var paiement = await _paiementService.GetPaiementByCommandeAsync(id);

        var viewModel = new CommandeDetailsViewModel
        {
            Commande = commande,
            Paiement = paiement
        };

        return View(viewModel);
    }

    [HttpGet]
    public async Task<IActionResult> MesCommandes()
    {
        var client = _sessionHelper.GetCurrentClient();
        
        if (client == null)
        {
            return RedirectToAction("Login", "Account", new { returnUrl = Url.Action("MesCommandes") });
        }

        var commandes = await _commandeService.GetCommandesByClientAsync(client.Id);

        var viewModel = new CommandeViewModel
        {
            Commandes = commandes
        };

        return View(viewModel);
    }

    [HttpGet]
    public async Task<IActionResult> Details(int id)
    {
        var client = _sessionHelper.GetCurrentClient();
        
        if (client == null)
        {
            return RedirectToAction("Login", "Account");
        }

        var commande = await _commandeService.GetCommandeDetailsAsync(id);
        
        if (commande == null || commande.IdClient != client.Id)
        {
            return NotFound();
        }

        var paiement = await _paiementService.GetPaiementByCommandeAsync(id);

        var viewModel = new CommandeDetailsViewModel
        {
            Commande = commande,
            Paiement = paiement
        };

        return View(viewModel);
    }
}