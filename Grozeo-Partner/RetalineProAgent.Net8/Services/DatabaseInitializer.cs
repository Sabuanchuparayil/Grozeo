using System.Data;
using Dapper;
using MySqlConnector;

namespace RetalineProAgent.Services;

public static class DatabaseInitializer
{
    public static async Task InitializeAsync(IConfiguration config, ILogger logger)
    {
        var connectionString =
            config.GetConnectionString("MySqlConnection")
            ?? config.GetConnectionString("DefaultConnection")
            ?? Environment.GetEnvironmentVariable("MYSQL_URL");

        if (string.IsNullOrEmpty(connectionString))
        {
            logger.LogWarning("No database connection string configured — skipping DB initialization");
            return;
        }

        try
        {
            using IDbConnection conn = new MySqlConnection(connectionString);
            conn.Open();

            await conn.ExecuteAsync(@"
                CREATE TABLE IF NOT EXISTS finascop_usr_master (
                    usr_id          INT AUTO_INCREMENT PRIMARY KEY,
                    usr_name        VARCHAR(100)  NOT NULL,
                    usr_email       VARCHAR(200)  NOT NULL UNIQUE,
                    usr_password_hash VARCHAR(200) NOT NULL,
                    usr_role        VARCHAR(50)   NOT NULL DEFAULT 'TenantAdmin',
                    usr_branch_id   INT           NOT NULL DEFAULT 0,
                    usr_status      TINYINT       NOT NULL DEFAULT 1,
                    usr_created_at  DATETIME      DEFAULT CURRENT_TIMESTAMP,
                    usr_updated_at  DATETIME      DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                )");

            logger.LogInformation("Ensuring default users exist in finascop_usr_master");

            var defaultUsers = new[]
            {
                new { Name = "Super Admin",    Email = "admin@grozeo.com",   Role = "SuperAdmin", Password = "Admin@123" },
                new { Name = "Tenant Manager", Email = "tenant@grozeo.com",  Role = "TenantAdmin", Password = "Tenant@123" },
                new { Name = "Finance User",   Email = "finance@grozeo.com", Role = "Finance",    Password = "Finance@123" },
                new { Name = "Support Agent",  Email = "support@grozeo.com", Role = "Support",    Password = "Support@123" },
            };

            foreach (var u in defaultUsers)
            {
                var exists = await conn.ExecuteScalarAsync<int>(
                    "SELECT COUNT(*) FROM finascop_usr_master WHERE usr_email = @Email",
                    new { u.Email });
                if (exists == 0)
                {
                    var hash = BCrypt.Net.BCrypt.HashPassword(u.Password);
                    await conn.ExecuteAsync(
                        @"INSERT INTO finascop_usr_master (usr_name, usr_email, usr_password_hash, usr_role, usr_branch_id, usr_status)
                          VALUES (@Name, @Email, @Hash, @Role, 0, 1)",
                        new { u.Name, u.Email, Hash = hash, u.Role });
                    logger.LogInformation("Seeded user {Email} with role {Role}", u.Email, u.Role);
                }
            }
        }
        catch (Exception ex)
        {
            logger.LogError(ex, "Database initialization failed — app will continue but login may not work until DB is available");
        }
    }
}
