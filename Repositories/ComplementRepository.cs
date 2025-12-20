using Dapper;
using brasilBugerC_.Helpers;
using brasilBugerC_.Models;
using brasilBugerC_.Models.Enums;

namespace brasilBugerC_.Repositories;

public class ComplementRepository : IComplementRepository
{
    private readonly DbConnectionFactory _connectionFactory;

    public ComplementRepository(DbConnectionFactory connectionFactory)
    {
        _connectionFactory = connectionFactory;
    }

    public async Task<IEnumerable<Complement>> GetAllAsync()
    {
        const string sql = @"
            SELECT id, nom, type, prix, image, archive, date_creation
            FROM complement
            ORDER BY type, nom";

        using var connection = _connectionFactory.CreateConnection();
        return await connection.QueryAsync<Complement>(sql);
    }

    public async Task<IEnumerable<Complement>> GetActiveAsync()
    {
        const string sql = @"
            SELECT id, nom, type, prix, image, archive, date_creation
            FROM complement
            WHERE archive = false
            ORDER BY type, nom";

        using var connection = _connectionFactory.CreateConnection();
        return await connection.QueryAsync<Complement>(sql);
    }

    public async Task<IEnumerable<Complement>> GetByTypeAsync(ComplementType type)
    {
        const string sql = @"
            SELECT id, nom, type, prix, image, archive, date_creation
            FROM complement
            WHERE type = @Type::""ComplementType""
              AND archive = false
            ORDER BY nom";

        using var connection = _connectionFactory.CreateConnection();
        return await connection.QueryAsync<Complement>(
            sql,
            new { Type = type.ToString() }
        );
    }

    public async Task<Complement?> GetByIdAsync(int id)
    {
        const string sql = @"
            SELECT id, nom, type, prix, image, archive, date_creation
            FROM complement
            WHERE id = @Id";

        using var connection = _connectionFactory.CreateConnection();
        return await connection.QueryFirstOrDefaultAsync<Complement>(sql, new { Id = id });
    }
}
