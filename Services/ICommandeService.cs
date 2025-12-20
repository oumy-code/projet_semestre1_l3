using brasilBugerC_.Models;
using brasilBugerC_.Models.Enums;
using brasilBugerC_.Models.ViewModels;

namespace brasilBugerC_.Services;

public interface ICommandeService
{
    Task<(bool Success, int CommandeId, string Message)> CreateCommandeAsync(
        int clientId, 
        PanierViewModel panier, 
        TypeRecuperation typeRecuperation, 
        int? zoneId = null, 
        string? adresseLivraison = null);
    Task<IEnumerable<Commande>> GetCommandesByClientAsync(int clientId);
    Task<Commande?> GetCommandeDetailsAsync(int commandeId);
    Task<IEnumerable<Zone>> GetZonesAsync();
    
}
