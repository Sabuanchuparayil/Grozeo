using System.Data;
using RetalineProAgent.Services;

namespace RetalineProAgent.Services;

public class DataService : IDataService
{
    private readonly IConfiguration _config;

    public DataService(IConfiguration config) => _config = config;

    private string ConnectionString =>
        _config.GetConnectionString("DefaultConnection")
        ?? Environment.GetEnvironmentVariable("AZURE_SQL_CONNECTION")
        ?? throw new InvalidOperationException("DefaultConnection not configured.");

    public async Task<IEnumerable<T>> QueryAsync<T>(string sql, object? param = null)
    {
        // Full implementation uses Dapper — added at deploy time
        await Task.CompletedTask;
        return Enumerable.Empty<T>();
    }

    public async Task<T?> QueryFirstOrDefaultAsync<T>(string sql, object? param = null)
    {
        await Task.CompletedTask;
        return default;
    }

    public async Task<int> ExecuteAsync(string sql, object? param = null)
    {
        await Task.CompletedTask;
        return 0;
    }
}
