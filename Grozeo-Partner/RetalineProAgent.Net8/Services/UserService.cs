using RetalineProAgent.Models;

namespace RetalineProAgent.Services;

public class UserService : IUserService
{
    private readonly IDataService _db;

    public UserService(IDataService db) => _db = db;

    public async Task<AppUser?> AuthenticateAsync(string email, string password)
    {
        var user = await _db.QueryFirstOrDefaultAsync<AppUser>(
            "SELECT usr_id, usr_name, usr_email, usr_role, usr_branch_id, usr_password_hash " +
            "FROM grozeo_partner_users WHERE usr_email = @Email AND usr_status = 1",
            new { Email = email });

        if (user == null) return null;

        if (!BCrypt.Net.BCrypt.Verify(password, user.usr_password_hash))
            return null;

        return user;
    }

    public async Task<AppUser?> GetByIdAsync(int userId) =>
        await _db.QueryFirstOrDefaultAsync<AppUser>(
            "SELECT * FROM grozeo_partner_users WHERE usr_id = @Id",
            new { Id = userId });

    public async Task<IEnumerable<AppUser>> GetAllAsync(int branchId) =>
        await _db.QueryAsync<AppUser>(
            "SELECT * FROM grozeo_partner_users WHERE usr_branch_id = @BranchId AND usr_status = 1",
            new { BranchId = branchId });
}
