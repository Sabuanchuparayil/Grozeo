using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using RetalineProAgent.Models;
using RetalineProAgent.Services;

namespace RetalineProAgent.Areas.Tenant.Controllers;

[Area("Tenant")]
[Authorize]
public class ProductController : Controller
{
    public IActionResult Index(int? id, string? status)
    {
        var products = MockDataService.GetProducts();
        if (!string.IsNullOrEmpty(status))
        {
            products = status switch
            {
                "Active" => products.Where(p => p.Status == "Active").ToList(),
                "OutOfStock" => products.Where(p => p.Stock == 0).ToList(),
                "LowStock" => products.Where(p => p.Stock > 0 && p.Stock <= 5).ToList(),
                _ => products
            };
        }
        ViewBag.CurrentStatus = status ?? "All";
        return View(products);
    }

    public IActionResult Create(int? id)
    {
        return View(new ProductViewModel());
    }

    [HttpPost]
    public IActionResult Create(ProductViewModel model)
    {
        TempData["Success"] = $"Product '{model.Name}' has been created.";
        return RedirectToAction("Index");
    }

    public IActionResult Edit(int? id)
    {
        var products = MockDataService.GetProducts();
        var product = products.FirstOrDefault(p => p.Id == id) ?? products.First();
        return View(product);
    }

    [HttpPost]
    public IActionResult Edit(ProductViewModel model)
    {
        TempData["Success"] = $"Product '{model.Name}' has been updated.";
        return RedirectToAction("Index");
    }

    [HttpPost]
    public IActionResult Delete(int? id)
    {
        TempData["Success"] = "Product has been deleted.";
        return RedirectToAction("Index");
    }

    public IActionResult BulkUpload(int? id)
    {
        return View();
    }

    public IActionResult Pricing(int? id)
    {
        var products = MockDataService.GetProducts();
        return View(products);
    }
}
