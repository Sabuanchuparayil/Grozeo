using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using RetalineProAgent.Services;

namespace RetalineProAgent.Areas.Finance.Controllers;

[Area("Finance")]
[Authorize(Policy = "FinanceUser")]
public class SettlementController : Controller
{
    private readonly IDataService _db;
    private readonly ILogger<SettlementController> _logger;

    public SettlementController(IDataService db, ILogger<SettlementController> logger)
    {
        _db = db;
        _logger = logger;
    }

    public IActionResult Index(int? id)
    {
        return View();
    }

    public IActionResult Apply(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Finance", "Settlement", "Apply");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Details(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Finance", "Settlement", "Details");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Download(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Finance", "Settlement", "Download");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Passbook(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Finance", "Settlement", "Passbook");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }
}
