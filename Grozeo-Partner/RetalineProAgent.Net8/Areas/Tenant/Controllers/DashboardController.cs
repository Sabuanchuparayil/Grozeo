using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using RetalineProAgent.Services;

namespace RetalineProAgent.Areas.Tenant.Controllers;

[Area("Tenant")]
[Authorize(Policy = "TenantAdmin")]
public class DashboardController : Controller
{
    private readonly IDataService _db;
    private readonly ILogger<DashboardController> _logger;

    public DashboardController(IDataService db, ILogger<DashboardController> logger)
    {
        _db = db;
        _logger = logger;
    }

    public IActionResult Index(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Tenant", "Dashboard", "Index");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Analytics(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Tenant", "Dashboard", "Analytics");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult StoreCompletion(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Tenant", "Dashboard", "StoreCompletion");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }
}
