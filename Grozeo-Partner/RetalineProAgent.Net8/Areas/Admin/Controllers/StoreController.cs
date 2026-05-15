using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using RetalineProAgent.Services;

namespace RetalineProAgent.Areas.Admin.Controllers;

[Area("Admin")]
[Authorize(Policy = "SuperAdmin")]
public class StoreController : Controller
{
    private readonly IDataService _db;
    private readonly ILogger<StoreController> _logger;

    public StoreController(IDataService db, ILogger<StoreController> logger)
    {
        _db = db;
        _logger = logger;
    }

    public IActionResult Index(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Admin", "Store", "Index");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Domains(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Admin", "Store", "Domains");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Config(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Admin", "Store", "Config");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }
}
