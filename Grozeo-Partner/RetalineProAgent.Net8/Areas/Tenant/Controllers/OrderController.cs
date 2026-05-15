using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using RetalineProAgent.Services;

namespace RetalineProAgent.Areas.Tenant.Controllers;

[Area("Tenant")]
[Authorize(Policy = "TenantAdmin")]
public class OrderController : Controller
{
    public IActionResult Index(int? id, string? status)
    {
        var orders = MockDataService.GetOrders();
        if (!string.IsNullOrEmpty(status) && status != "All")
            orders = orders.Where(o => o.Status == status).ToList();
        ViewBag.CurrentStatus = status ?? "All";
        return View(orders);
    }

    public IActionResult Details(string? id)
    {
        var orders = MockDataService.GetOrders();
        var order = orders.FirstOrDefault(o => o.OrderNumber == id) ?? orders.FirstOrDefault();
        if (order == null)
        {
            TempData["Error"] = "Order not found.";
            return RedirectToAction("Index");
        }
        order.Items = new()
        {
            new() { ProductName = "Premium Basmati Rice 5kg", SKU = "GRZ-RICE-001", Quantity = 2, UnitPrice = 450 },
            new() { ProductName = "Sunflower Oil 1L", SKU = "GRZ-OIL-001", Quantity = 1, UnitPrice = 175 },
            new() { ProductName = "Toor Dal 1kg", SKU = "GRZ-DAL-001", Quantity = 2, UnitPrice = 140 }
        };
        return View(order);
    }

    [HttpPost]
    public IActionResult Cancel(string? id)
    {
        TempData["Success"] = $"Order {id ?? "GRZ-2026-0001"} has been cancelled.";
        return RedirectToAction("Index");
    }

    [HttpPost]
    public IActionResult Hold(string? id)
    {
        TempData["Success"] = $"Order {id ?? "GRZ-2026-0001"} has been put on hold.";
        return RedirectToAction("Index");
    }

    public IActionResult Returns(int? id)
    {
        return View();
    }

    public IActionResult BOD(int? id)
    {
        return View();
    }
}
