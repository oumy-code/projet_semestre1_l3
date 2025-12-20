using Dapper;
using brasilBugerC_.Helpers;
using brasilBugerC_.Models;

namespace brasilBugerC_.Repositories;

public class CompositionMenuRepository : ICompositionMenuRepository
{
    private readonly DbConnectionFactory _connectionFactory;

    public CompositionMenuRepository(DbConnectionFactory connectionFactory)
    {
        _connectionFactory = connectionFactory;
    }

    public async Task<IEnumerable<CompositionMenu>> GetByMenuIdAsync(int menuId)
    {
        const string sql = @"
            SELECT 
                cm.id, cm.id_menu, cm.id_burger, cm.id_complement, cm.quantite,
                b.id, b.nom, b.prix, b.image, b.archive, b.date_creation,
                c.id, c.nom, c.type, c.prix, c.image, c.archive, c.date_creation
            FROM composition_menu cm
            LEFT JOIN burger b ON cm.id_burger = b.id
            LEFT JOIN complement c ON cm.id_complement = c.id
            WHERE cm.id_menu = @MenuId";

        using var connection = _connectionFactory.CreateConnection();
        
        var compositions = await connection.QueryAsync<CompositionMenu, Burger?, Complement?, CompositionMenu>(
            sql,
            (composition, burger, complement) =>
            {
                composition.Burger = burger;
                composition.Complement = complement;
                return composition;
            },
            new { MenuId = menuId },
            splitOn: "id,id"
        );

        return compositions;
    }
}