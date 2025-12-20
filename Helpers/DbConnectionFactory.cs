using Npgsql;
using System.Data;

namespace brasilBugerC_.Helpers;

public class DbConnectionFactory
{
    private readonly string _connectionString;

    public DbConnectionFactory(IConfiguration configuration)
    {
        _connectionString = configuration.GetConnectionString("NeonDb") 
            ?? throw new InvalidOperationException("Connection string 'NeonDb' not found.");
    }

    public IDbConnection CreateConnection()
    {
        return new NpgsqlConnection(_connectionString);
    }
}