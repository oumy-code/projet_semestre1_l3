using Microsoft.AspNetCore.Mvc;
using brasilBugerC_.Helpers;
using brasilBugerC_.Models.ViewModels;

namespace brasilBugerC_.Controllers
{
    public class PanierController : Controller
    {
        private readonly SessionHelper _sessionHelper;

        public PanierController(SessionHelper sessionHelper)
        {
            _sessionHelper = sessionHelper;
        }

        public IActionResult Index()
        {
            // Récupérer le panier depuis la session ou créer un panier vide
            var panier = _sessionHelper.GetPanier<PanierViewModel>() ?? new PanierViewModel();
            return View(panier);
        }

        [HttpPost]
        public IActionResult Clear()
        {
            _sessionHelper.ClearPanier();
            TempData["SuccessMessage"] = "Panier vidé avec succès";
            return RedirectToAction("Index");
        }
    }
}
