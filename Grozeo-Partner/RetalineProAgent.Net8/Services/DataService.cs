using System.Data;
using Dapper;
using MySqlConnector;

namespace RetalineProAgent.Services;

public class DataService : IDataService
{
    private readonly string _connectionString;

    public DataService(IConfiguration config)
    {
        _connectionString =
            config.GetConnectionString("MySqlConnection")
            ?? config.GetConnectionString("DefaultConnection")
            ?? Environment.GetEnvironmentVariable("MYSQL_URL")
            ?? throw new InvalidOperationException(
                "No database connection string configured. Set ConnectionStrings:MySqlConnection.");
    }

    private IDbConnection CreateConnection() => new MySqlConnection(_connectionString);

    public async Task<IEnumerable<T>> QueryAsync<T>(string sql, object? param = null)
    {
        using var conn = CreateConnection();
        return await conn.QueryAsync<T>(sql, param);
    }

    public async Task<T?> QueryFirstOrDefaultAsync<T>(string sql, object? param = null)
    {
        using var conn = CreateConnection();
        return await conn.QueryFirstOrDefaultAsync<T>(sql, param);
    }

    public async Task<int> ExecuteAsync(string sql, object? param = null)
    {
        using var conn = CreateConnection();
        return await conn.ExecuteAsync(sql, param);
    }
}
