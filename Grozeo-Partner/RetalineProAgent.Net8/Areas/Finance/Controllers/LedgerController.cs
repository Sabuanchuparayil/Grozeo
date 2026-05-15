using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using RetalineProAgent.Services;

namespace RetalineProAgent.Areas.Finance.Controllers;

[Area("Finance")]
[Authorize(Policy = "FinanceUser")]
public class LedgerController : Controller
{
    private readonly IDataService _db;
    private readonly ILogger<LedgerController> _logger;

    public LedgerController(IDataService db, ILogger<LedgerController> logger)
    {
        _db = db;
        _logger = logger;
    }

    public IActionResult Index(int? id)
    {
        return View();
    }

    public IActionResult Create(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Finance", "Ledger", "Create");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Edit(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Finance", "Ledger", "Edit");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Groups(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Finance", "Ledger", "Groups");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }
}
