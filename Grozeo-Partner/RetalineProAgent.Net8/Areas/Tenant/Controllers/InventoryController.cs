using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using RetalineProAgent.Services;

namespace RetalineProAgent.Areas.Tenant.Controllers;

[Area("Tenant")]
[Authorize(Policy = "TenantAdmin")]
public class InventoryController : Controller
{
    private readonly IDataService _db;
    private readonly ILogger<InventoryController> _logger;

    public InventoryController(IDataService db, ILogger<InventoryController> logger)
    {
        _db = db;
        _logger = logger;
    }

    public IActionResult Index(int? id)
    {
        return View();
    }

    public IActionResult StockIn(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Tenant", "Inventory", "StockIn");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult StockOut(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Tenant", "Inventory", "StockOut");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Transfer(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Tenant", "Inventory", "Transfer");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Barcode(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Tenant", "Inventory", "Barcode");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }
}
