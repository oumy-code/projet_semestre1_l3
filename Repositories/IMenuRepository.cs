using brasilBugerC_.Models;

namespace brasilBugerC_.Repositories;

public interface IMenuRepository
{
    Task<IEnumerable<Menu>> GetAllAsync();
    Task<IEnumerable<Menu>> GetActiveAsync();
    Task<Menu?> GetByIdAsync(int id);
    Task<Menu?> GetByIdWithCompositionsAsync(int id);
}
