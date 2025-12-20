using brasilBugerC_.Models;
using brasilBugerC_.Models.Enums;
using brasilBugerC_.Repositories;

namespace brasilBugerC_.Services;

public class PaiementService : IPaiementService
{
    private readonly IPaiementRepository _paiementRepository;
    private readonly ICommandeRepository _commandeRepository;

    public PaiementService(
        IPaiementRepository paiementRepository,
        ICommandeRepository commandeRepository)
    {
        _paiementRepository = paiementRepository;
        _commandeRepository = commandeRepository;
    }

    public async Task<(bool Success, int PaiementId, string Message)> CreatePaiementAsync(
        int commandeId,
        decimal montant,
        ModePaiement modePaiement)
    {
        try
        {
            // Vérifier que la commande existe
            var commande = await _commandeRepository.GetByIdAsync(commandeId);
            if (commande == null)
            {
                return (false, 0, "Commande introuvable");
            }

            // Vérifier qu'il n'y a pas déjà un paiement
            var existingPaiement = await _paiementRepository.GetByCommandeIdAsync(commandeId);
            if (existingPaiement != null)
            {
                return (false, 0, "Cette commande a déjà été payée");
            }

            // Créer le paiement
            var paiement = new Paiement
            {
                IdCommande = commandeId,
                Montant = montant,
                ModePaiement = modePaiement,
                Statut = StatutPaiement.EnAttente
            };

            var paiementId = await _paiementRepository.CreateAsync(paiement);

            return (true, paiementId, "Paiement en attente de confirmation");
        }
        catch (Exception ex)
        {
            return (false, 0, $"Erreur lors du paiement: {ex.Message}");
        }
    }

    public async Task<Paiement?> GetPaiementByCommandeAsync(int commandeId)
    {
        return await _paiementRepository.GetByCommandeIdAsync(commandeId);
    }

    public async Task<bool> ConfirmerPaiementAsync(int paiementId)
    {
        return await _paiementRepository.UpdateStatutAsync(paiementId, "confirme");
    }
}