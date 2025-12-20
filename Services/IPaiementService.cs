using brasilBugerC_.Models;
using brasilBugerC_.Models.Enums;

namespace brasilBugerC_.Services;

public interface IPaiementService
{
    Task<(bool Success, int PaiementId, string Message)> CreatePaiementAsync(
        int commandeId, 
        decimal montant, 
        ModePaiement modePaiement);
    Task<Paiement?> GetPaiementByCommandeAsync(int commandeId);
    Task<bool> ConfirmerPaiementAsync(int paiementId);
}
