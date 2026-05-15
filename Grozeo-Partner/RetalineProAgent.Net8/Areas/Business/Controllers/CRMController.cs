using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;

namespace RetalineProAgent.Areas.Business.Controllers;

[Area("Business")]
[Authorize(Policy = "TenantAdmin")]
public class CRMController : Controller
{

    public IActionResult Index(int? id)
    {
        return View();
    }

    public IActionResult Prospects(int? id)
    {
        TempData["Info"] = "This feature is coming soon.";
        return RedirectToAction("Index");
    }

    public IActionResult Retailers(int? id)
    {
        TempData["Info"] = "This feature is coming soon.";
        return RedirectToAction("Index");
    }

    public IActionResult Leads(int? id)
    {
        TempData["Info"] = "This feature is coming soon.";
        return RedirectToAction("Index");
    }

    public IActionResult Contacts(int? id)
    {
        TempData["Info"] = "This feature is coming soon.";
        return RedirectToAction("Index");
    }

    public IActionResult Followups(int? id)
    {
        TempData["Info"] = "This feature is coming soon.";
        return RedirectToAction("Index");
    }
}
