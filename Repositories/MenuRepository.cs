using Dapper;
using brasilBugerC_.Helpers;
using brasilBugerC_.Models;

namespace brasilBugerC_.Repositories;

public class MenuRepository : IMenuRepository
{
    private readonly DbConnectionFactory _connectionFactory;
    private readonly ICompositionMenuRepository _compositionMenuRepository;
    private readonly PriceCalculator _priceCalculator;

    public MenuRepository(DbConnectionFactory connectionFactory, 
                         ICompositionMenuRepository compositionMenuRepository,
                         PriceCalculator priceCalculator)
    {
        _connectionFactory = connectionFactory;
        _compositionMenuRepository = compositionMenuRepository;
        _priceCalculator = priceCalculator;
    }

    public async Task<IEnumerable<Menu>> GetAllAsync()
    {
        const string sql = @"
            SELECT id, nom, image, archive, date_creation
            FROM menu
            ORDER BY nom";

        using var connection = _connectionFactory.CreateConnection();
        var menus = await connection.QueryAsync<Menu>(sql);
        
        foreach (var menu in menus)
        {
            menu.Compositions = (await _compositionMenuRepository.GetByMenuIdAsync(menu.Id)).ToList();
            menu.Prix = _priceCalculator.CalculatePrixMenu(menu.Compositions);
        }
        
        return menus;
    }

    public async Task<IEnumerable<Menu>> GetActiveAsync()
    {
        const string sql = @"
            SELECT id, nom, image, archive, date_creation
            FROM menu
            WHERE archive = false
            ORDER BY nom";

        using var connection = _connectionFactory.CreateConnection();
        var menus = await connection.QueryAsync<Menu>(sql);
        
        foreach (var menu in menus)
        {
            menu.Compositions = (await _compositionMenuRepository.GetByMenuIdAsync(menu.Id)).ToList();
            menu.Prix = _priceCalculator.CalculatePrixMenu(menu.Compositions);
        }
        
        return menus;
    }

    public async Task<Menu?> GetByIdAsync(int id)
    {
        const string sql = @"
            SELECT id, nom, image, archive, date_creation
            FROM menu
            WHERE id = @Id";

        using var connection = _connectionFactory.CreateConnection();
        var menu = await connection.QueryFirstOrDefaultAsync<Menu>(sql, new { Id = id });
        
        if (menu != null)
        {
            menu.Compositions = (await _compositionMenuRepository.GetByMenuIdAsync(menu.Id)).ToList();
            menu.Prix = _priceCalculator.CalculatePrixMenu(menu.Compositions);
        }
        
        return menu;
    }

    public async Task<Menu?> GetByIdWithCompositionsAsync(int id)
    {
        return await GetByIdAsync(id);
    }
}