using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using RetalineProAgent.Services;

namespace RetalineProAgent.Areas.Admin.Controllers;

[Area("Admin")]
[Authorize(Policy = "SuperAdmin")]
public class SystemController : Controller
{
    private readonly IDataService _db;
    private readonly ILogger<SystemController> _logger;

    public SystemController(IDataService db, ILogger<SystemController> logger)
    {
        _db = db;
        _logger = logger;
    }

    public IActionResult Jobs(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Admin", "System", "Jobs");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Logs(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Admin", "System", "Logs");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Settings(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Admin", "System", "Settings");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }
}
