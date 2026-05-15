using System.Data;
using Dapper;
using MySqlConnector;

namespace RetalineProAgent.Services;

public static class DatabaseInitializer
{
    private const string PartnerTable = "grozeo_partner_users";

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
            logger.LogInformation("Database connection opened successfully for initialization");

            await conn.ExecuteAsync($@"
                CREATE TABLE IF NOT EXISTS {PartnerTable} (
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

            logger.LogInformation("Table {Table} ensured", PartnerTable);

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
                    $"SELECT COUNT(*) FROM {PartnerTable} WHERE usr_email = @Email",
                    new { u.Email });

                if (exists == 0)
                {
                    var hash = BCrypt.Net.BCrypt.HashPassword(u.Password);
                    await conn.ExecuteAsync(
                        $@"INSERT INTO {PartnerTable} (usr_name, usr_email, usr_password_hash, usr_role, usr_branch_id, usr_status)
                           VALUES (@Name, @Email, @Hash, @Role, 0, 1)",
                        new { u.Name, u.Email, Hash = hash, u.Role });
                    logger.LogInformation("Seeded user {Email} with role {Role}", u.Email, u.Role);
                }
                else
                {
                    var currentHash = await conn.ExecuteScalarAsync<string>(
                        $"SELECT usr_password_hash FROM {PartnerTable} WHERE usr_email = @Email",
                        new { u.Email });

                    bool needsUpdate = string.IsNullOrEmpty(currentHash)
                        || !currentHash.StartsWith("$2");

                    if (!needsUpdate)
                    {
                        try { needsUpdate = !BCrypt.Net.BCrypt.Verify(u.Password, currentHash); }
                        catch { needsUpdate = true; }
                    }

                    if (needsUpdate)
                    {
                        var hash = BCrypt.Net.BCrypt.HashPassword(u.Password);
                        await conn.ExecuteAsync(
                            $"UPDATE {PartnerTable} SET usr_password_hash = @Hash WHERE usr_email = @Email",
                            new { Hash = hash, u.Email });
                        logger.LogInformation("Reset password hash for {Email}", u.Email);
                    }
                    else
                    {
                        logger.LogInformation("User {Email} already exists with valid hash", u.Email);
                    }
                }
            }

            logger.LogInformation("Database initialization completed successfully");
        }
        catch (Exception ex)
        {
            logger.LogError(ex, "Database initialization failed — app will continue but login may not work until DB is available");
        }
    }
}
