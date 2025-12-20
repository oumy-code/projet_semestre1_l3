using Microsoft.AspNetCore.Mvc;
using brasilBugerC_.Helpers;
using brasilBugerC_.Models;
using brasilBugerC_.Models.ViewModels;
using brasilBugerC_.Services;

namespace brasilBugerC_.Controllers;

public class AccountController : Controller
{
    private readonly IAuthenticationService _authService;
    private readonly SessionHelper _sessionHelper;

    public AccountController(IAuthenticationService authService, SessionHelper sessionHelper)
    {
        _authService = authService;
        _sessionHelper = sessionHelper;
    }

    [HttpGet]
    public IActionResult Login(string? returnUrl = null)
    {
        ViewData["ReturnUrl"] = returnUrl;
        return View();
    }

    [HttpPost]
    public async Task<IActionResult> Login(LoginViewModel model, string? returnUrl = null)
    {
        ViewData["ReturnUrl"] = returnUrl;

        if (!ModelState.IsValid)
            return View(model);

        // Login par login ou email
        var (success, client, message) = await _authService.LoginAsync(model.Login, model.MotDePasse);

        if (!success || client == null)
        {
            ModelState.AddModelError(string.Empty, message);
            return View(model);
        }

        _sessionHelper.SetCurrentClient(client);
        TempData["SuccessMessage"] = "Connexion réussie";

        if (!string.IsNullOrEmpty(returnUrl) && Url.IsLocalUrl(returnUrl))
            return Redirect(returnUrl);

        return RedirectToAction("Index", "Catalogue");
    }

    [HttpGet]
    public IActionResult Register() => View();

    [HttpPost]
    public async Task<IActionResult> Register(RegisterViewModel model)
    {
        if (!ModelState.IsValid)
            return View(model);

        var client = new Client
        {
            Nom = model.Nom,
            Prenom = model.Prenom,
            Telephone = model.Telephone,
            Email = model.Email,
            Login = model.Login
        };

        var (success, clientId, message) = await _authService.RegisterAsync(client, model.MotDePasse);

        if (!success)
        {
            ModelState.AddModelError(string.Empty, message);
            return View(model);
        }

        TempData["SuccessMessage"] = "Inscription réussie. Vous pouvez maintenant vous connecter.";
        return RedirectToAction(nameof(Login));
    }

    [HttpPost]
    public IActionResult Logout()
    {
        _sessionHelper.ClearCurrentClient();
        _sessionHelper.ClearPanier();
        TempData["SuccessMessage"] = "Vous êtes déconnecté";
        return RedirectToAction("Index", "Catalogue");
    }

    [HttpGet]
    public IActionResult Profile()
    {
        var client = _sessionHelper.GetCurrentClient();
        if (client == null)
            return RedirectToAction(nameof(Login));

        return View(client);
    }
}
