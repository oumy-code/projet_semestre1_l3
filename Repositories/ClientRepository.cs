using Dapper;
using brasilBugerC_.Helpers;
using brasilBugerC_.Models;

namespace brasilBugerC_.Repositories;

public class ClientRepository : IClientRepository
{
    private readonly DbConnectionFactory _connectionFactory;

    public ClientRepository(DbConnectionFactory connectionFactory)
    {
        _connectionFactory = connectionFactory;
    }

    public async Task<Client?> GetByIdAsync(int id)
    {
        const string sql = @"
            SELECT u.id, u.nom, u.prenom, u.telephone, u.email, u.date_creation AS DateCreation,
                   c.login, c.mot_de_passe AS MotDePasse
            FROM ""user"" u
            INNER JOIN client c ON u.id = c.id
            WHERE u.id = @Id";

        using var connection = _connectionFactory.CreateConnection();
        return await connection.QueryFirstOrDefaultAsync<Client>(sql, new { Id = id });
    }

    public async Task<Client?> GetByLoginAsync(string login)
    {
        const string sql = @"
            SELECT u.id, u.nom, u.prenom, u.telephone, u.email, u.date_creation AS DateCreation,
                   c.login, c.mot_de_passe AS MotDePasse
            FROM ""user"" u
            INNER JOIN client c ON u.id = c.id
            WHERE c.login = @Login";

        using var connection = _connectionFactory.CreateConnection();
        return await connection.QueryFirstOrDefaultAsync<Client>(sql, new { Login = login });
    }

    public async Task<Client?> GetByEmailAsync(string email)
    {
        const string sql = @"
            SELECT u.id, u.nom, u.prenom, u.telephone, u.email, u.date_creation AS DateCreation,
                   c.login, c.mot_de_passe AS MotDePasse
            FROM ""user"" u
            INNER JOIN client c ON u.id = c.id
            WHERE u.email = @Email";

        using var connection = _connectionFactory.CreateConnection();
        return await connection.QueryFirstOrDefaultAsync<Client>(sql, new { Email = email });
    }

    public async Task<int> CreateAsync(Client client)
    {
        using var connection = _connectionFactory.CreateConnection();
        connection.Open();
        using var transaction = connection.BeginTransaction();

        try
        {
            const string sqlUser = @"
                INSERT INTO ""user"" (nom, prenom, telephone, email)
                VALUES (@Nom, @Prenom, @Telephone, @Email)
                RETURNING id";

            var userId = await connection.ExecuteScalarAsync<int>(sqlUser, new
            {
                client.Nom,
                client.Prenom,
                client.Telephone,
                client.Email
            }, transaction);

            const string sqlClient = @"
                INSERT INTO client (id, login, mot_de_passe)
                VALUES (@Id, @Login, @MotDePasse)";

            await connection.ExecuteAsync(sqlClient, new
            {
                Id = userId,
                client.Login,
                client.MotDePasse
            }, transaction);

            transaction.Commit();
            return userId;
        }
        catch
        {
            transaction.Rollback();
            throw;
        }
    }

    public async Task<bool> UpdateAsync(Client client)
    {
        const string sql = @"
            UPDATE ""user""
            SET nom = @Nom, prenom = @Prenom, telephone = @Telephone, email = @Email
            WHERE id = @Id";

        using var connection = _connectionFactory.CreateConnection();
        var rowsAffected = await connection.ExecuteAsync(sql, client);
        return rowsAffected > 0;
    }

    public async Task<bool> ExistsAsync(string login, string email)
    {
        const string sql = @"
            SELECT COUNT(*)
            FROM ""user"" u
            INNER JOIN client c ON u.id = c.id
            WHERE c.login = @Login OR u.email = @Email";

        using var connection = _connectionFactory.CreateConnection();
        var count = await connection.ExecuteScalarAsync<int>(sql, new { Login = login, Email = email });
        return count > 0;
    }
}
