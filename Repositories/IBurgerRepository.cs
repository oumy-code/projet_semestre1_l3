using brasilBugerC_.Models;

namespace brasilBugerC_.Repositories;

public interface IBurgerRepository
{
    Task<IEnumerable<Burger>> GetAllAsync();
    Task<IEnumerable<Burger>> GetActiveAsync();
    Task<Burger?> GetByIdAsync(int id);
}