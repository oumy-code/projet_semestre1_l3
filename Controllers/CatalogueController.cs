using Microsoft.AspNetCore.Mvc;
using brasilBugerC_.Models.Enums;
using brasilBugerC_.Models.ViewModels;
using brasilBugerC_.Services;

namespace brasilBugerC_.Controllers;

public class CatalogueController : Controller
{
    private readonly ICatalogueService _catalogueService;
    private readonly IPanierService _panierService;

    public CatalogueController(ICatalogueService catalogueService, IPanierService panierService)
    {
        _catalogueService = catalogueService;
        _panierService = panierService;
    }

    [HttpGet]
    public async Task<IActionResult> Index(string? filtre)
    {
        var viewModel = new CatalogueViewModel
        {
            Filtre = filtre
        };

        if (filtre == "burger")
        {
            viewModel.Burgers = await _catalogueService.GetAllBurgersAsync();
        }
        else if (filtre == "menu")
        {
            viewModel.Menus = await _catalogueService.GetAllMenusAsync();
        }
        else
        {
            viewModel.Burgers = await _catalogueService.GetAllBurgersAsync();
            viewModel.Menus = await _catalogueService.GetAllMenusAsync();
        }

        return View(viewModel);
    }

    [HttpGet]
    public async Task<IActionResult> BurgerDetails(int id)
    {
        var burger = await _catalogueService.GetBurgerByIdAsync(id);
        
        if (burger == null)
        {
            return NotFound();
        }

        var viewModel = new ProductDetailsViewModel
        {
            Type = "burger",
            Burger = burger,
            ComplementsFrites = await _catalogueService.GetComplementsByTypeAsync(ComplementType.FRITES),
            ComplementsBoissons = await _catalogueService.GetComplementsByTypeAsync(ComplementType.BOISSON)
        };

        return View(viewModel);
    }

    [HttpPost]
    public async Task<IActionResult> AddBurgerToPanier(int id, int quantite, List<int>? complementIds)
    {
        var burger = await _catalogueService.GetBurgerByIdAsync(id);
        
        if (burger == null)
        {
            return NotFound();
        }

        decimal prixTotal = burger.Prix;

        if (complementIds != null && complementIds.Any())
        {
            foreach (var complementId in complementIds)
            {
                var complement = await _catalogueService.GetComplementByIdAsync(complementId);
                if (complement != null)
                {
                    prixTotal += complement.Prix;
                }
            }
        }

        _panierService.AddBurger(id, burger.Nom, prixTotal, quantite, complementIds);
        TempData["SuccessMessage"] = $"{burger.Nom} ajouté au panier";
        return RedirectToAction(nameof(Index));
    }

    [HttpGet]
    public async Task<IActionResult> MenuDetails(int id)
    {
        var menu = await _catalogueService.GetMenuByIdAsync(id);
        
        if (menu == null)
        {
            return NotFound();
        }

        var viewModel = new ProductDetailsViewModel
        {
            Type = "menu",
            Menu = menu
        };

        return View(viewModel);
    }

    [HttpPost]
    public async Task<IActionResult> AddMenuToPanier(int id, int quantite)
    {
        var menu = await _catalogueService.GetMenuByIdAsync(id);
        
        if (menu == null)
        {
            return NotFound();
        }

        _panierService.AddMenu(id, menu.Nom, menu.Prix, quantite);
        TempData["SuccessMessage"] = $"{menu.Nom} ajouté au panier";
        return RedirectToAction(nameof(Index));
    }
}
