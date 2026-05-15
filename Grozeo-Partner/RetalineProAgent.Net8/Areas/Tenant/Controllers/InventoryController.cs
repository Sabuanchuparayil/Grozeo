using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;

namespace RetalineProAgent.Areas.Tenant.Controllers;

[Area("Tenant")]
[Authorize(Policy = "TenantAdmin")]
public class InventoryController : Controller
{

    public IActionResult Index(int? id)
    {
        return View();
    }

    public IActionResult StockIn(int? id)
    {
        TempData["Info"] = "This feature is coming soon.";
        return RedirectToAction("Index");
    }

    public IActionResult StockOut(int? id)
    {
        TempData["Info"] = "This feature is coming soon.";
        return RedirectToAction("Index");
    }

    public IActionResult Transfer(int? id)
    {
        TempData["Info"] = "This feature is coming soon.";
        return RedirectToAction("Index");
    }

    public IActionResult Barcode(int? id)
    {
        TempData["Info"] = "This feature is coming soon.";
        return RedirectToAction("Index");
    }
}
