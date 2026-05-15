using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using RetalineProAgent.Services;

namespace RetalineProAgent.Areas.Tenant.Controllers;

[Area("Tenant")]
[Authorize(Policy = "TenantAdmin")]
public class DeliveryController : Controller
{
    private readonly IDataService _db;
    private readonly ILogger<DeliveryController> _logger;

    public DeliveryController(IDataService db, ILogger<DeliveryController> logger)
    {
        _db = db;
        _logger = logger;
    }

    public IActionResult Index(int? id)
    {
        return View();
    }

    public IActionResult Slots(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Tenant", "Delivery", "Slots");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Rules(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Tenant", "Delivery", "Rules");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Staff(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Tenant", "Delivery", "Staff");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult JobConfirm(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Tenant", "Delivery", "JobConfirm");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }
}
