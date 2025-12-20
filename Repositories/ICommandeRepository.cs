using brasilBugerC_.Models;

namespace brasilBugerC_.Repositories;

public interface ICommandeRepository
{
    Task<int> CreateAsync(Commande commande);
    Task<IEnumerable<Commande>> GetByClientIdAsync(int clientId);
    Task<Commande?> GetByIdAsync(int id);
    Task<Commande?> GetByIdWithDetailsAsync(int id);
    Task<bool> UpdateAsync(Commande commande);
}
