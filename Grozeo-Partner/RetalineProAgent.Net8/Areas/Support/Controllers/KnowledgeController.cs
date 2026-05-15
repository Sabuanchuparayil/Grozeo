using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using RetalineProAgent.Services;

namespace RetalineProAgent.Areas.Support.Controllers;

[Area("Support")]
[Authorize(Policy = "TenantAdmin")]
public class KnowledgeController : Controller
{
    private readonly IDataService _db;
    private readonly ILogger<KnowledgeController> _logger;

    public KnowledgeController(IDataService db, ILogger<KnowledgeController> logger)
    {
        _db = db;
        _logger = logger;
    }

    public IActionResult Index(int? id)
    {
        return View();
    }

    public IActionResult Articles(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Support", "Knowledge", "Articles");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult FAQ(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Support", "Knowledge", "FAQ");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }
}
