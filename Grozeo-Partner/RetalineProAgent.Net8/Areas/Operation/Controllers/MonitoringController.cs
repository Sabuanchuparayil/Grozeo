using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using RetalineProAgent.Services;

namespace RetalineProAgent.Areas.Operation.Controllers;

[Area("Operation")]
[Authorize(Policy = "TenantAdmin")]
public class MonitoringController : Controller
{
    private readonly IDataService _db;
    private readonly ILogger<MonitoringController> _logger;

    public MonitoringController(IDataService db, ILogger<MonitoringController> logger)
    {
        _db = db;
        _logger = logger;
    }

    public IActionResult Index(int? id)
    {
        return View();
    }

    public IActionResult PackingDelays(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Operation", "Monitoring", "PackingDelays");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult DeliveryDelays(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Operation", "Monitoring", "DeliveryDelays");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult LiveOrders(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Operation", "Monitoring", "LiveOrders");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }
}
