using brasilBugerC_.Helpers;
using brasilBugerC_.Repositories;
using brasilBugerC_.Services;
using Microsoft.AspNetCore.Authentication.Cookies;

var builder = WebApplication.CreateBuilder(args);

// ============================================
// ⚠️ CRITIQUE : INITIALISER LES TYPE HANDLERS DAPPER
// Doit être fait AVANT toute utilisation de Dapper
// ============================================
DapperTypeHandlerConfig.Initialize();

// Add services to the container.
builder.Services.AddControllersWithViews();

// Configuration de la session
builder.Services.AddSession(options =>
{
    options.IdleTimeout = TimeSpan.FromMinutes(30);
    options.Cookie.HttpOnly = true;
    options.Cookie.IsEssential = true;
    options.Cookie.Name = ".BrasilBurger.Session";
});

// HttpContextAccessor pour la session et helpers
builder.Services.AddHttpContextAccessor();

// Authentification cookie
builder.Services.AddAuthentication("BrasilBurgerCookie")
    .AddCookie("BrasilBurgerCookie", options =>
    {
        options.LoginPath = "/Account/Login";     // Redirection si non authentifié
        options.LogoutPath = "/Account/Logout";
        options.Cookie.Name = "BrasilBurgerAuth";
        options.ExpireTimeSpan = TimeSpan.FromHours(1);
        options.SlidingExpiration = true;
    });

// Enregistrement de DbConnectionFactory
builder.Services.AddSingleton<DbConnectionFactory>();

// Enregistrement des Repositories
builder.Services.AddScoped<IClientRepository, ClientRepository>();
builder.Services.AddScoped<IBurgerRepository, BurgerRepository>();
builder.Services.AddScoped<IMenuRepository, MenuRepository>();
builder.Services.AddScoped<IComplementRepository, ComplementRepository>();
builder.Services.AddScoped<ICompositionMenuRepository, CompositionMenuRepository>();
builder.Services.AddScoped<ICommandeRepository, CommandeRepository>();
builder.Services.AddScoped<ILigneCommandeRepository, LigneCommandeRepository>();
builder.Services.AddScoped<IPaiementRepository, PaiementRepository>();
builder.Services.AddScoped<IZoneRepository, ZoneRepository>();

// Enregistrement des Services
builder.Services.AddScoped<IAuthenticationService, AuthenticationService>();
builder.Services.AddScoped<ICatalogueService, CatalogueService>();
builder.Services.AddScoped<ICommandeService, CommandeService>();
builder.Services.AddScoped<IPaiementService, PaiementService>();
builder.Services.AddScoped<IPanierService, PanierService>();

// Helpers
builder.Services.AddSingleton<PasswordHasher>();
builder.Services.AddScoped<SessionHelper>();
builder.Services.AddSingleton<PriceCalculator>();

var app = builder.Build();

// Configure the HTTP request pipeline.
if (!app.Environment.IsDevelopment())
{
    app.UseExceptionHandler("/Home/Error");
    app.UseHsts();
}

app.UseHttpsRedirection();
app.UseStaticFiles();
app.UseRouting();

// Session
app.UseSession();

// Authentification et autorisation
app.UseAuthentication();
app.UseAuthorization();

// Route par défaut
app.MapControllerRoute(
    name: "default",
    pattern: "{controller=Catalogue}/{action=Index}/{id?}");

app.Run();