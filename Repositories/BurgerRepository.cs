using Dapper;
using brasilBugerC_.Helpers;
using brasilBugerC_.Models;

namespace brasilBugerC_.Repositories;

public class BurgerRepository : IBurgerRepository
{
    private readonly DbConnectionFactory _connectionFactory;

    public BurgerRepository(DbConnectionFactory connectionFactory)
    {
        _connectionFactory = connectionFactory;
    }

    public async Task<IEnumerable<Burger>> GetAllAsync()
    {
        const string sql = @"
            SELECT id, nom, prix, image, archive, date_creation
            FROM burger
            ORDER BY nom";

        using var connection = _connectionFactory.CreateConnection();
        return await connection.QueryAsync<Burger>(sql);
    }

    public async Task<IEnumerable<Burger>> GetActiveAsync()
    {
        const string sql = @"
            SELECT id, nom, prix, image, archive, date_creation
            FROM burger
            WHERE archive = false
            ORDER BY nom";

        using var connection = _connectionFactory.CreateConnection();
        return await connection.QueryAsync<Burger>(sql);
    }

    public async Task<Burger?> GetByIdAsync(int id)
    {
        const string sql = @"
            SELECT id, nom, prix, image, archive, date_creation
            FROM burger
            WHERE id = @Id";

        using var connection = _connectionFactory.CreateConnection();
        return await connection.QueryFirstOrDefaultAsync<Burger>(sql, new { Id = id });
    }
}