using Microsoft.AspNetCore.Authentication;
using Microsoft.AspNetCore.Authentication.Cookies;
using Microsoft.AspNetCore.Mvc;
using RetalineProAgent.Services;
using System.Security.Claims;

namespace RetalineProAgent.Areas.Account.Controllers;

[Area("Account")]
public class AccountController : Controller
{
    private readonly IUserService _users;
    private readonly ILogger<AccountController> _logger;

    public AccountController(IUserService users, ILogger<AccountController> logger)
    {
        _users = users;
        _logger = logger;
    }

    [HttpGet]
    public IActionResult Login(string? returnUrl = null)
    {
        if (User.Identity?.IsAuthenticated == true)
            return RedirectToAction("Index", "Dashboard", new { area = "Tenant" });
        ViewData["ReturnUrl"] = returnUrl;
        return View();
    }

    [HttpPost]
    [ValidateAntiForgeryToken]
    public async Task<IActionResult> Login(string email, string password, string? returnUrl = null, string? rememberMe = null)
    {
        ModelState.Remove("rememberMe");
        bool isPersistent = string.Equals(rememberMe, "true", StringComparison.OrdinalIgnoreCase)
                         || string.Equals(rememberMe, "on", StringComparison.OrdinalIgnoreCase);

        if (string.IsNullOrWhiteSpace(email) || string.IsNullOrWhiteSpace(password))
        {
            ModelState.AddModelError("", "Email and password are required.");
            return View();
        }

        var user = await _users.AuthenticateAsync(email, password);
        if (user == null)
        {
            _logger.LogWarning("Failed login attempt for {Email}", email);
            ModelState.AddModelError("", "Invalid credentials.");
            return View();
        }

        var claims = new List<Claim>
        {
            new(ClaimTypes.NameIdentifier, user.usr_id.ToString()),
            new(ClaimTypes.Name,           user.usr_name),
            new(ClaimTypes.Email,          user.usr_email),
            new(ClaimTypes.Role,           user.usr_role),
            new("BranchId",                user.usr_branch_id.ToString()),
        };

        var identity   = new ClaimsIdentity(claims, CookieAuthenticationDefaults.AuthenticationScheme);
        var principal  = new ClaimsPrincipal(identity);
        var authProps  = new AuthenticationProperties
        {
            IsPersistent = isPersistent,
            ExpiresUtc   = isPersistent ? DateTimeOffset.UtcNow.AddDays(7) : DateTimeOffset.UtcNow.AddHours(8)
        };

        await HttpContext.SignInAsync(CookieAuthenticationDefaults.AuthenticationScheme, principal, authProps);
        _logger.LogInformation("User {Email} logged in", email);

        TempData["Success"] = $"Welcome back, {user.usr_name}!";

        if (!string.IsNullOrEmpty(returnUrl) && Url.IsLocalUrl(returnUrl))
            return Redirect(returnUrl);

        return user.usr_role switch
        {
            "Finance" => RedirectToAction("Index", "Settlement", new { area = "Finance" }),
            "Support" => RedirectToAction("Index", "Ticket", new { area = "Support" }),
            _         => RedirectToAction("Index", "Dashboard", new { area = "Tenant" }),
        };
    }

    [HttpPost]
    [ValidateAntiForgeryToken]
    public async Task<IActionResult> Logout()
    {
        await HttpContext.SignOutAsync(CookieAuthenticationDefaults.AuthenticationScheme);
        return RedirectToAction("Login");
    }

    public IActionResult AccessDenied() => View();
}
