using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;

namespace RetalineProAgent.Areas.Tenant.Controllers;

[Area("Tenant")]
[Authorize(Policy = "TenantAdmin")]
public class CampaignController : Controller
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

    public IActionResult Coupon(int? id)
    {
        TempData["Info"] = "This feature is coming soon.";
        return RedirectToAction("Index");
    }

    public IActionResult Sponsored(int? id)
    {
        TempData["Info"] = "This feature is coming soon.";
        return RedirectToAction("Index");
    }
}
