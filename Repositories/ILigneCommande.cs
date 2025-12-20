using brasilBugerC_.Models;

namespace brasilBugerC_.Repositories;

public interface ILigneCommandeRepository
{
    Task CreateAsync(LigneCommande ligne);
    Task<IEnumerable<LigneCommande>> GetByCommandeIdAsync(int commandeId);
}