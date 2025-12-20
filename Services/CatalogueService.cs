using brasilBugerC_.Models;
using brasilBugerC_.Models.Enums;
using brasilBugerC_.Repositories;

namespace brasilBugerC_.Services;

public class CatalogueService : ICatalogueService
{
    private readonly IBurgerRepository _burgerRepository;
    private readonly IMenuRepository _menuRepository;
    private readonly IComplementRepository _complementRepository;

    public CatalogueService(
        IBurgerRepository burgerRepository,
        IMenuRepository menuRepository,
        IComplementRepository complementRepository)
    {
        _burgerRepository = burgerRepository;
        _menuRepository = menuRepository;
        _complementRepository = complementRepository;
    }

    public async Task<IEnumerable<Burger>> GetAllBurgersAsync()
    {
        return await _burgerRepository.GetActiveAsync();
    }

    public async Task<IEnumerable<Menu>> GetAllMenusAsync()
    {
        return await _menuRepository.GetActiveAsync();
    }

    public async Task<IEnumerable<Complement>> GetAllComplementsAsync()
    {
        return await _complementRepository.GetActiveAsync();
    }

    public async Task<IEnumerable<Complement>> GetComplementsByTypeAsync(ComplementType type)
    {
        return await _complementRepository.GetByTypeAsync(type);
    }

    public async Task<Burger?> GetBurgerByIdAsync(int id)
    {
        return await _burgerRepository.GetByIdAsync(id);
    }

    public async Task<Menu?> GetMenuByIdAsync(int id)
    {
        return await _menuRepository.GetByIdWithCompositionsAsync(id);
    }

    public async Task<Complement?> GetComplementByIdAsync(int id)
    {
        return await _complementRepository.GetByIdAsync(id);
    }
}