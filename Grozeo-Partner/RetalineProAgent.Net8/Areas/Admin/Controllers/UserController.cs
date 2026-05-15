using System.Security.Claims;
using BCrypt.Net;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using RetalineProAgent.Models;
using RetalineProAgent.Services;

namespace RetalineProAgent.Areas.Admin.Controllers;

[Area("Admin")]
[Authorize(Policy = "SuperAdmin")]
public class UserController : Controller
{
    private readonly IDataService _db;
    private readonly ILogger<UserController> _logger;

    public UserController(IDataService db, ILogger<UserController> logger)
    {
        _db = db;
        _logger = logger;
    }

    public async Task<IActionResult> Index(int? id, string? q)
    {
        try
        {
            IEnumerable<AppUser> users;
            if (!string.IsNullOrWhiteSpace(q))
            {
                var pattern = $"%{q.Trim()}%";
                users = await _db.QueryAsync<AppUser>(
                    @"SELECT * FROM finascop_usr_master 
                      WHERE usr_name LIKE @Pattern OR usr_email LIKE @Pattern 
                      ORDER BY usr_id",
                    new { Pattern = pattern });
                ViewBag.SearchQuery = q.Trim();
            }
            else
            {
                users = await _db.QueryAsync<AppUser>(
                    "SELECT * FROM finascop_usr_master ORDER BY usr_id");
            }

            return View(users);
        }
        catch (Exception ex)
        {
            _logger.LogError(ex, "Failed to load users");
            return View(Enumerable.Empty<AppUser>());
        }
    }

    [HttpGet]
    public IActionResult Create()
    {
        return View();
    }

    [HttpPost]
    [ValidateAntiForgeryToken]
    public async Task<IActionResult> Create(string usr_name, string usr_email, string password,
        string confirmPassword, string usr_role, int usr_branch_id, int usr_status)
    {
        if (string.IsNullOrWhiteSpace(usr_name) || string.IsNullOrWhiteSpace(usr_email) ||
            string.IsNullOrWhiteSpace(password))
        {
            ViewBag.Error = "Name, email and password are required.";
            return View();
        }

        if (password != confirmPassword)
        {
            ViewBag.Error = "Passwords do not match.";
            return View();
        }

        try
        {
            var existing = await _db.QueryFirstOrDefaultAsync<AppUser>(
                "SELECT * FROM finascop_usr_master WHERE usr_email = @Email",
                new { Email = usr_email });

            if (existing != null)
            {
                ViewBag.Error = "A user with this email already exists.";
                return View();
            }

            var hash = BCrypt.Net.BCrypt.HashPassword(password);

            await _db.ExecuteAsync(
                @"INSERT INTO finascop_usr_master 
                  (usr_name, usr_email, usr_password_hash, usr_role, usr_branch_id, usr_status, usr_created_at, usr_updated_at) 
                  VALUES (@Name, @Email, @Hash, @Role, @BranchId, @Status, NOW(), NOW())",
                new
                {
                    Name = usr_name,
                    Email = usr_email,
                    Hash = hash,
                    Role = usr_role,
                    BranchId = usr_branch_id,
                    Status = usr_status
                });

            TempData["Success"] = "User created successfully.";
            return RedirectToAction(nameof(Index));
        }
        catch (Exception ex)
        {
            _logger.LogError(ex, "Failed to create user");
            ViewBag.Error = "An error occurred while creating the user.";
            return View();
        }
    }

    [HttpGet]
    public async Task<IActionResult> Edit(int? id)
    {
        if (id == null) return RedirectToAction(nameof(Index));

        try
        {
            var user = await _db.QueryFirstOrDefaultAsync<AppUser>(
                "SELECT * FROM finascop_usr_master WHERE usr_id = @Id",
                new { Id = id.Value });

            if (user == null)
            {
                TempData["Error"] = "User not found.";
                return RedirectToAction(nameof(Index));
            }

            return View(user);
        }
        catch (Exception ex)
        {
            _logger.LogError(ex, "Failed to load user {UserId}", id);
            TempData["Error"] = "An error occurred while loading the user.";
            return RedirectToAction(nameof(Index));
        }
    }

    [HttpPost]
    [ValidateAntiForgeryToken]
    public async Task<IActionResult> Edit(int usr_id, string usr_name, string usr_email,
        string? password, string? confirmPassword, string usr_role, int usr_branch_id, int usr_status)
    {
        if (string.IsNullOrWhiteSpace(usr_name) || string.IsNullOrWhiteSpace(usr_email))
        {
            ViewBag.Error = "Name and email are required.";
            var user = await _db.QueryFirstOrDefaultAsync<AppUser>(
                "SELECT * FROM finascop_usr_master WHERE usr_id = @Id", new { Id = usr_id });
            return View(user);
        }

        if (!string.IsNullOrWhiteSpace(password) && password != confirmPassword)
        {
            ViewBag.Error = "Passwords do not match.";
            var user = await _db.QueryFirstOrDefaultAsync<AppUser>(
                "SELECT * FROM finascop_usr_master WHERE usr_id = @Id", new { Id = usr_id });
            return View(user);
        }

        try
        {
            if (!string.IsNullOrWhiteSpace(password))
            {
                var hash = BCrypt.Net.BCrypt.HashPassword(password);
                await _db.ExecuteAsync(
                    @"UPDATE finascop_usr_master 
                      SET usr_name = @Name, usr_email = @Email, usr_password_hash = @Hash, 
                          usr_role = @Role, usr_branch_id = @BranchId, usr_status = @Status, usr_updated_at = NOW()
                      WHERE usr_id = @Id",
                    new { Name = usr_name, Email = usr_email, Hash = hash, Role = usr_role, BranchId = usr_branch_id, Status = usr_status, Id = usr_id });
            }
            else
            {
                await _db.ExecuteAsync(
                    @"UPDATE finascop_usr_master 
                      SET usr_name = @Name, usr_email = @Email, usr_role = @Role, 
                          usr_branch_id = @BranchId, usr_status = @Status, usr_updated_at = NOW()
                      WHERE usr_id = @Id",
                    new { Name = usr_name, Email = usr_email, Role = usr_role, BranchId = usr_branch_id, Status = usr_status, Id = usr_id });
            }

            TempData["Success"] = "User updated successfully.";
            return RedirectToAction(nameof(Index));
        }
        catch (Exception ex)
        {
            _logger.LogError(ex, "Failed to update user {UserId}", usr_id);
            ViewBag.Error = "An error occurred while updating the user.";
            var user = await _db.QueryFirstOrDefaultAsync<AppUser>(
                "SELECT * FROM finascop_usr_master WHERE usr_id = @Id", new { Id = usr_id });
            return View(user);
        }
    }

    public IActionResult Roles(int? id)
    {
        return View();
    }
}
