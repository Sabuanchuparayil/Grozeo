using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using RetalineProAgent.Services;

namespace RetalineProAgent.Areas.Support.Controllers;

[Area("Support")]
[Authorize(Policy = "TenantAdmin")]
public class TicketController : Controller
{
    private readonly IDataService _db;
    private readonly ILogger<TicketController> _logger;

    public TicketController(IDataService db, ILogger<TicketController> logger)
    {
        _db = db;
        _logger = logger;
    }

    public IActionResult Index(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Support", "Ticket", "Index");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Create(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Support", "Ticket", "Create");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Details(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Support", "Ticket", "Details");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Resolve(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Support", "Ticket", "Resolve");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }

    public IActionResult Escalate(int? id)
    {
        _logger.LogWarning("{Area}/{Controller}/{Action} not yet implemented", "Support", "Ticket", "Escalate");
        ViewBag.Title = "Coming Soon";
        ViewBag.Message = "This feature is under development.";
        return View("~/Views/Shared/ComingSoon.cshtml");
    }
}
