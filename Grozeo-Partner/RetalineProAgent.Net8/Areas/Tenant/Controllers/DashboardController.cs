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

        try
        {
            var totalUsers = await _db.QueryFirstOrDefaultAsync<int>(
                "SELECT COUNT(*) FROM finascop_usr_master");
            ViewBag.TotalUsers = totalUsers;

            var activeUsers = await _db.QueryFirstOrDefaultAsync<int>(
                "SELECT COUNT(*) FROM finascop_usr_master WHERE usr_status = 1");
            ViewBag.ActiveUsers = activeUsers;

            var roleCounts = await _db.QueryAsync<dynamic>(
                "SELECT usr_role, COUNT(*) as cnt FROM finascop_usr_master WHERE usr_status = 1 GROUP BY usr_role");
            ViewBag.RoleCounts = roleCounts;
        }
        catch (Exception ex)
        {
            _logger.LogWarning(ex, "Could not load user stats — table may not exist");
            ViewBag.TotalUsers = 0;
            ViewBag.ActiveUsers = 0;
            ViewBag.RoleCounts = Enumerable.Empty<dynamic>();
        }

        try
        {
            var totalOrders = await _db.QueryFirstOrDefaultAsync<int>(
                "SELECT COUNT(*) FROM finascop_order_master");
            ViewBag.TotalOrders = totalOrders;
        }
        catch
        {
            ViewBag.TotalOrders = 0;
        }

        try
        {
            var totalProducts = await _db.QueryFirstOrDefaultAsync<int>(
                "SELECT COUNT(*) FROM finascop_product_master");
            ViewBag.TotalProducts = totalProducts;
        }
        catch
        {
            ViewBag.TotalProducts = 0;
        }

        try
        {
            var revenue = await _db.QueryFirstOrDefaultAsync<decimal>(
                "SELECT COALESCE(SUM(order_total), 0) FROM finascop_order_master WHERE order_status = 'completed'");
            ViewBag.Revenue = revenue;
        }
        catch
        {
            ViewBag.Revenue = 0m;
        }

        return View();
    }

    public IActionResult Analytics(int? id)
    {
        TempData["Info"] = "This feature is coming soon.";
        return RedirectToAction("Index");
    }

    public IActionResult StoreCompletion(int? id)
    {
        TempData["Info"] = "This feature is coming soon.";
        return RedirectToAction("Index");
    }
}
