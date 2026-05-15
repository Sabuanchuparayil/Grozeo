using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using RetalineProAgent.Services;

namespace RetalineProAgent.Areas.Fleet.Controllers;

[Area("Fleet")]
[Authorize(Policy = "TenantAdmin")]
public class VehicleController : Controller
{
    private readonly IDataService _db;
    private readonly ILogger<VehicleController> _logger;

    public VehicleController(IDataService db, ILogger<VehicleController> logger)
    {
        _db = db;
        _logger = logger;
    }

    public IActionResult Index(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Fleet", "Vehicle", "Index");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Create(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Fleet", "Vehicle", "Create");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Edit(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Fleet", "Vehicle", "Edit");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult History(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Fleet", "Vehicle", "History");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }
}
