using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using RetalineProAgent.Services;

namespace RetalineProAgent.Areas.Finance.Controllers;

[Area("Finance")]
[Authorize(Policy = "FinanceUser")]
public class VoucherController : Controller
{
    private readonly IDataService _db;
    private readonly ILogger<VoucherController> _logger;

    public VoucherController(IDataService db, ILogger<VoucherController> logger)
    {
        _db = db;
        _logger = logger;
    }

    public IActionResult Index(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Finance", "Voucher", "Index");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Entry(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Finance", "Voucher", "Entry");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Details(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Finance", "Voucher", "Details");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult AutoPosting(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Finance", "Voucher", "AutoPosting");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }
}
