using brasilBugerC_.Models;
using brasilBugerC_.Models.Enums;

namespace brasilBugerC_.Repositories;

public interface IComplementRepository
{
    Task<IEnumerable<Complement>> GetAllAsync();
    Task<IEnumerable<Complement>> GetActiveAsync();
    Task<IEnumerable<Complement>> GetByTypeAsync(ComplementType type);
    Task<Complement?> GetByIdAsync(int id);
}
