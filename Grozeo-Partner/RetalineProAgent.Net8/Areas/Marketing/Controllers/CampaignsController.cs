using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using RetalineProAgent.Services;

namespace RetalineProAgent.Areas.Marketing.Controllers;

[Area("Marketing")]
[Authorize(Policy = "TenantAdmin")]
public class CampaignsController : Controller
{
    private readonly IDataService _db;
    private readonly ILogger<CampaignsController> _logger;

    public CampaignsController(IDataService db, ILogger<CampaignsController> logger)
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
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Marketing", "Campaigns", "Create");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Edit(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Marketing", "Campaigns", "Edit");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Analytics(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Marketing", "Campaigns", "Analytics");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }
}
