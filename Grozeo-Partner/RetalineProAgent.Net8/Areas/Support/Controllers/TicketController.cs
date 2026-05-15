using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;

namespace RetalineProAgent.Areas.Support.Controllers;

[Area("Support")]
[Authorize(Policy = "SupportAgent")]
public class TicketController : Controller
{

    public IActionResult Index(int? id)
    {
        return View();
    }

    public IActionResult Create(int? id)
    {
        TempData["Info"] = "This feature is coming soon.";
        return RedirectToAction("Index");
    }

    public IActionResult Details(int? id)
    {
        TempData["Info"] = "This feature is coming soon.";
        return RedirectToAction("Index");
    }

    public IActionResult Resolve(int? id)
    {
        TempData["Info"] = "This feature is coming soon.";
        return RedirectToAction("Index");
    }

    public IActionResult Escalate(int? id)
    {
        TempData["Info"] = "This feature is coming soon.";
        return RedirectToAction("Index");
    }
}
