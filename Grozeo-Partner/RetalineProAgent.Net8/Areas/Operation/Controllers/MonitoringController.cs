using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;

namespace RetalineProAgent.Areas.Operation.Controllers;

[Area("Operation")]
[Authorize(Policy = "TenantAdmin")]
public class MonitoringController : Controller
{

    public IActionResult Index(int? id)
    {
        return View();
    }

    public IActionResult PackingDelays(int? id)
    {
        TempData["Info"] = "This feature is coming soon.";
        return RedirectToAction("Index");
    }

    public IActionResult DeliveryDelays(int? id)
    {
        TempData["Info"] = "This feature is coming soon.";
        return RedirectToAction("Index");
    }

    public IActionResult LiveOrders(int? id)
    {
        TempData["Info"] = "This feature is coming soon.";
        return RedirectToAction("Index");
    }
}
