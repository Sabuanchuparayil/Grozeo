using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;

namespace RetalineProAgent.Areas.Business.Controllers;

[Area("Business")]
[Authorize(Policy = "TenantAdmin")]
public class AreaManagerController : Controller
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

    public IActionResult Edit(int? id)
    {
        TempData["Info"] = "This feature is coming soon.";
        return RedirectToAction("Index");
    }

    public IActionResult Assign(int? id)
    {
        TempData["Info"] = "This feature is coming soon.";
        return RedirectToAction("Index");
    }
}
