using System.Text.Json;
using brasilBugerC_.Models;

namespace brasilBugerC_.Helpers;

public class SessionHelper
{
    private readonly IHttpContextAccessor _httpContextAccessor;
    private const string CLIENT_KEY = "CurrentClient";
    private const string PANIER_KEY = "Panier";

    public SessionHelper(IHttpContextAccessor httpContextAccessor)
    {
        _httpContextAccessor = httpContextAccessor;
    }

    private ISession? Session => _httpContextAccessor.HttpContext?.Session;

    // Gestion du client connecté
    public void SetCurrentClient(Client client)
    {
        var json = JsonSerializer.Serialize(client);
        Session?.SetString(CLIENT_KEY, json);
    }

    public Client? GetCurrentClient()
    {
        var json = Session?.GetString(CLIENT_KEY);
        return string.IsNullOrEmpty(json) ? null : JsonSerializer.Deserialize<Client>(json);
    }

    public void ClearCurrentClient()
    {
        Session?.Remove(CLIENT_KEY);
    }

    public bool IsAuthenticated()
    {
        return GetCurrentClient() != null;
    }

    // Gestion du panier
    public void SetPanier<T>(T panier)
    {
        var json = JsonSerializer.Serialize(panier);
        Session?.SetString(PANIER_KEY, json);
    }

    public T? GetPanier<T>() where T : class
    {
        var json = Session?.GetString(PANIER_KEY);
        return string.IsNullOrEmpty(json) ? null : JsonSerializer.Deserialize<T>(json);
    }

    public void ClearPanier()
    {
        Session?.Remove(PANIER_KEY);
    }
}