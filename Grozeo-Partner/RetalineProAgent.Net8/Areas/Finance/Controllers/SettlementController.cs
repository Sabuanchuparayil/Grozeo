using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using RetalineProAgent.Services;

namespace RetalineProAgent.Areas.Finance.Controllers;

[Area("Finance")]
[Authorize(Policy = "FinanceUser")]
public class SettlementController : Controller
{
    public IActionResult Index(int? id)
    {
        var settlements = MockDataService.GetSettlements();
        return View(settlements);
    }

    public IActionResult Apply(int? id)
    {
        return View();
    }

    [HttpPost]
    public IActionResult Apply(decimal amount)
    {
        TempData["Success"] = $"Settlement request for ₹{amount:N2} has been submitted.";
        return RedirectToAction("Index");
    }

    public IActionResult Details(string? id)
    {
        var settlements = MockDataService.GetSettlements();
        var settlement = settlements.FirstOrDefault(s => s.SettlementNumber == id) ?? settlements.FirstOrDefault();
        if (settlement == null)
        {
            TempData["Error"] = "Settlement not found.";
            return RedirectToAction("Index");
        }
        return View(settlement);
    }

    public IActionResult Download(int? id)
    {
        TempData["Success"] = "Settlement report downloaded.";
        return RedirectToAction("Index");
    }

    public IActionResult Passbook(int? id)
    {
        var settlements = MockDataService.GetSettlements();
        return View(settlements);
    }
}
