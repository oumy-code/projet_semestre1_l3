using brasilBugerC_.Models;

namespace brasilBugerC_.Repositories;

public interface IPaiementRepository
{
    Task<int> CreateAsync(Paiement paiement);
    Task<Paiement?> GetByCommandeIdAsync(int commandeId);
    Task<bool> UpdateStatutAsync(int id, string statut);
}