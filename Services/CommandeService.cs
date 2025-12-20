using brasilBugerC_.Helpers;
using brasilBugerC_.Models;
using brasilBugerC_.Models.Enums;
using brasilBugerC_.Models.ViewModels;
using brasilBugerC_.Repositories;

namespace brasilBugerC_.Services;

public class CommandeService : ICommandeService
{
    private readonly ICommandeRepository _commandeRepository;
    private readonly ILigneCommandeRepository _ligneCommandeRepository;
    private readonly IZoneRepository _zoneRepository;
    private readonly PriceCalculator _priceCalculator;

    public CommandeService(
        ICommandeRepository commandeRepository,
        ILigneCommandeRepository ligneCommandeRepository,
        IZoneRepository zoneRepository,
        PriceCalculator priceCalculator)
    {
        _commandeRepository = commandeRepository;
        _ligneCommandeRepository = ligneCommandeRepository;
        _zoneRepository = zoneRepository;
        _priceCalculator = priceCalculator;
    }

    public async Task<(bool Success, int CommandeId, string Message)> CreateCommandeAsync(
        int clientId,
        PanierViewModel panier,
        TypeRecuperation typeRecuperation,
        int? zoneId = null,
        string? adresseLivraison = null)
    {
        // Validation
        if (panier == null || !panier.Items.Any())
        {
            return (false, 0, "Le panier est vide");
        }

        if (typeRecuperation == TypeRecuperation.Livraison)
        {
            if (!zoneId.HasValue)
            {
                return (false, 0, "Zone de livraison requise");
            }

            if (string.IsNullOrWhiteSpace(adresseLivraison))
            {
                return (false, 0, "Adresse de livraison requise");
            }
        }

        try
        {
            // Calculer le montant total
            decimal montantTotal = panier.Items.Sum(i => i.SousTotal);

            // Ajouter les frais de livraison si applicable
            if (typeRecuperation == TypeRecuperation.Livraison && zoneId.HasValue)
            {
                var zone = await _zoneRepository.GetByIdAsync(zoneId.Value);
                if (zone != null)
                {
                    montantTotal += zone.PrixLivraison;
                }
            }

            // Créer la commande
            var commande = new Commande
            {
                IdClient = clientId,
                IdZone = zoneId,
                MontantTotal = montantTotal,
                Etat = EtatCommande.EnCours,
                TypeRecuperation = typeRecuperation,
                AdresseLivraison = adresseLivraison
            };

            var commandeId = await _commandeRepository.CreateAsync(commande);

            // Créer les lignes de commande
            foreach (var item in panier.Items)
            {
                var ligne = new LigneCommande
                {
                    IdCommande = commandeId,
                    IdBurger = item.BurgerId,
                    IdMenu = item.MenuId,
                    IdComplement = item.ComplementId,
                    Quantite = item.Quantite,
                    PrixUnitaire = item.PrixUnitaire,
                    SousTotal = item.SousTotal
                };

                await _ligneCommandeRepository.CreateAsync(ligne);
            }

            return (true, commandeId, "Commande créée avec succès");
        }
        catch (Exception ex)
        {
            return (false, 0, $"Erreur lors de la création de la commande: {ex.Message}");
        }
    }

    public async Task<IEnumerable<Commande>> GetCommandesByClientAsync(int clientId)
    {
        return await _commandeRepository.GetByClientIdAsync(clientId);
    }

    public async Task<Commande?> GetCommandeDetailsAsync(int commandeId)
    {
        return await _commandeRepository.GetByIdWithDetailsAsync(commandeId);
    }

    public async Task<IEnumerable<Zone>> GetZonesAsync()
    {
        return await _zoneRepository.GetAllAsync();
    }
}