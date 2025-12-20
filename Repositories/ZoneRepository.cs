// Repositories/ZoneRepository.cs
using Dapper;
using brasilBugerC_.Helpers;
using brasilBugerC_.Models;

namespace brasilBugerC_.Repositories;

public class ZoneRepository : IZoneRepository
{
    private readonly DbConnectionFactory _connectionFactory;

    public ZoneRepository(DbConnectionFactory connectionFactory)
    {
        _connectionFactory = connectionFactory;
    }

    public async Task<IEnumerable<Zone>> GetAllAsync()
    {
        const string sql = @"
            SELECT id, nom, prix_livraison AS PrixLivraison,  quartiers, date_creation AS DateCreation
            FROM zone
            ORDER BY nom";

        using var connection = _connectionFactory.CreateConnection();
        return await connection.QueryAsync<Zone>(sql);
    }

    public async Task<Zone?> GetByIdAsync(int id)
    {
        const string sql = @"
            SELECT id, nom, prix_livraison AS PrixLivraison, quartiers, date_creation AS DateCreation
            FROM zone
            WHERE id = @Id";

        using var connection = _connectionFactory.CreateConnection();
        return await connection.QueryFirstOrDefaultAsync<Zone>(sql, new { Id = id });
    }
}