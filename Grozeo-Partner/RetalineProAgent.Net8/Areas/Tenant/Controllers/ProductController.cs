using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using RetalineProAgent.Services;

namespace RetalineProAgent.Areas.Tenant.Controllers;

[Area("Tenant")]
[Authorize(Policy = "TenantAdmin")]
public class ProductController : Controller
{
    private readonly IDataService _db;
    private readonly ILogger<ProductController> _logger;

    public ProductController(IDataService db, ILogger<ProductController> logger)
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
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Tenant", "Product", "Create");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Edit(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Tenant", "Product", "Edit");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Delete(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Tenant", "Product", "Delete");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult BulkUpload(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Tenant", "Product", "BulkUpload");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Pricing(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Tenant", "Product", "Pricing");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }
}
