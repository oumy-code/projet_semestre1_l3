// Repositories/IZoneRepository.cs
using brasilBugerC_.Models;

namespace brasilBugerC_.Repositories;

public interface IZoneRepository
{
    Task<IEnumerable<Zone>> GetAllAsync();
    Task<Zone?> GetByIdAsync(int id);
}
