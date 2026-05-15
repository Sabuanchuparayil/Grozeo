using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using RetalineProAgent.Services;

namespace RetalineProAgent.Areas.Finance.Controllers;

[Area("Finance")]
[Authorize(Policy = "FinanceUser")]
public class CostCentreController : Controller
{
    private readonly IDataService _db;
    private readonly ILogger<CostCentreController> _logger;

    public CostCentreController(IDataService db, ILogger<CostCentreController> logger)
    {
        _db = db;
        _logger = logger;
    }

    public IActionResult Index(int? id)
    {
        return View();
    }

    public IActionResult Entry(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Finance", "CostCentre", "Entry");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Allocation(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Finance", "CostCentre", "Allocation");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Reports(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Finance", "CostCentre", "Reports");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }
}
