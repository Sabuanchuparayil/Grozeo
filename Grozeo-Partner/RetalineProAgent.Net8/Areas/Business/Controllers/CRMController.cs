using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using RetalineProAgent.Services;

namespace RetalineProAgent.Areas.Business.Controllers;

[Area("Business")]
[Authorize(Policy = "TenantAdmin")]
public class CRMController : Controller
{
    private readonly IDataService _db;
    private readonly ILogger<CRMController> _logger;

    public CRMController(IDataService db, ILogger<CRMController> logger)
    {
        _db = db;
        _logger = logger;
    }

    public IActionResult Index(int? id)
    {
        return View();
    }

    public IActionResult Prospects(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Business", "CRM", "Prospects");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Retailers(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Business", "CRM", "Retailers");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Leads(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Business", "CRM", "Leads");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Contacts(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Business", "CRM", "Contacts");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Followups(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Business", "CRM", "Followups");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }
}
