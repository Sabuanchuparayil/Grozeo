using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using RetalineProAgent.Services;

namespace RetalineProAgent.Areas.Finance.Controllers;

[Area("Finance")]
[Authorize(Policy = "FinanceUser")]
public class ReportController : Controller
{
    private readonly IDataService _db;
    private readonly ILogger<ReportController> _logger;

    public ReportController(IDataService db, ILogger<ReportController> logger)
    {
        _db = db;
        _logger = logger;
    }

    public IActionResult Index(int? id)
    {
        return View();
    }

    public IActionResult Sales(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Finance", "Report", "Sales");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult GST(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Finance", "Report", "GST");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult TDS(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Finance", "Report", "TDS");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult TCS(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Finance", "Report", "TCS");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult ProfitLoss(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Finance", "Report", "ProfitLoss");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult BalanceSheet(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Finance", "Report", "BalanceSheet");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult TrialBalance(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Finance", "Report", "TrialBalance");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Daybook(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Finance", "Report", "Daybook");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }
}
