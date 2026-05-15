using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using RetalineProAgent.Services;

namespace RetalineProAgent.Areas.Tenant.Controllers;

[Area("Tenant")]
[Authorize(Policy = "TenantAdmin")]
public class CampaignController : Controller
{
    private readonly IDataService _db;
    private readonly ILogger<CampaignController> _logger;

    public CampaignController(IDataService db, ILogger<CampaignController> logger)
    {
        _db = db;
        _logger = logger;
    }

    public IActionResult Index(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Tenant", "Campaign", "Index");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Create(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Tenant", "Campaign", "Create");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Edit(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Tenant", "Campaign", "Edit");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Coupon(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Tenant", "Campaign", "Coupon");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Sponsored(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Tenant", "Campaign", "Sponsored");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }
}
