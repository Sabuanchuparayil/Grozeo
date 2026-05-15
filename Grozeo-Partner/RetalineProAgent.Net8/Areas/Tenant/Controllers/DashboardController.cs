using System.Security.Claims;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using RetalineProAgent.Services;

namespace RetalineProAgent.Areas.Tenant.Controllers;

[Area("Tenant")]
[Authorize]
public class DashboardController : Controller
{
    private readonly IDataService _db;
    private readonly ILogger<DashboardController> _logger;

    public DashboardController(IDataService db, ILogger<DashboardController> logger)
    {
        _db = db;
        _logger = logger;
    }

    public async Task<IActionResult> Index(int? id)
    {
        var userName = User.FindFirst(ClaimTypes.Name)?.Value ?? "Partner";
        ViewBag.UserName = userName;

        var stats = MockDataService.GetDashboardStats();
        ViewBag.Stats = stats;

        try
        {
            var totalUsers = await _db.QueryFirstOrDefaultAsync<int>(
                "SELECT COUNT(*) FROM grozeo_partner_users");
            ViewBag.TotalUsers = totalUsers > 0 ? totalUsers : 5;

            var activeUsers = await _db.QueryFirstOrDefaultAsync<int>(
                "SELECT COUNT(*) FROM grozeo_partner_users WHERE usr_status = 1");
            ViewBag.ActiveUsers = activeUsers > 0 ? activeUsers : 4;
        }
        catch
        {
            ViewBag.TotalUsers = 5;
            ViewBag.ActiveUsers = 4;
        }

        return View(stats);
    }

    public IActionResult Analytics(int? id)
    {
        return View();
    }

    public IActionResult StoreCompletion(int? id)
    {
        return View();
    }
}
