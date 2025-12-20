using brasilBugerC_.Models;
using brasilBugerC_.Models.Enums;

namespace brasilBugerC_.Services;

public interface ICatalogueService
{
    Task<IEnumerable<Burger>> GetAllBurgersAsync();
    Task<IEnumerable<Menu>> GetAllMenusAsync();
    Task<IEnumerable<Complement>> GetAllComplementsAsync();
    Task<IEnumerable<Complement>> GetComplementsByTypeAsync(ComplementType type);
    Task<Burger?> GetBurgerByIdAsync(int id);
    Task<Menu?> GetMenuByIdAsync(int id);
    Task<Complement?> GetComplementByIdAsync(int id);
}
