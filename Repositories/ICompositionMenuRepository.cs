using brasilBugerC_.Models;

namespace brasilBugerC_.Repositories;

public interface ICompositionMenuRepository
{
    Task<IEnumerable<CompositionMenu>> GetByMenuIdAsync(int menuId);
}