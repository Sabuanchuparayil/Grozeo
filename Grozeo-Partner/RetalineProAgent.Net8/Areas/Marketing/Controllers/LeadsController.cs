using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using RetalineProAgent.Services;

namespace RetalineProAgent.Areas.Marketing.Controllers;

[Area("Marketing")]
[Authorize(Policy = "TenantAdmin")]
public class LeadsController : Controller
{
    private readonly IDataService _db;
    private readonly ILogger<LeadsController> _logger;

    public LeadsController(IDataService db, ILogger<LeadsController> logger)
    {
        _db = db;
        _logger = logger;
    }

    public IActionResult Index(int? id)
    {
        return View();
    }

    public IActionResult Import(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Marketing", "Leads", "Import");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Assign(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Marketing", "Leads", "Assign");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }
}
