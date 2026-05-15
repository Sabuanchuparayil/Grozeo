using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;

namespace RetalineProAgent.Areas.Tenant.Controllers;

[Area("Tenant")]
[Authorize(Policy = "TenantAdmin")]
public class InventoryController : Controller
{
    public IActionResult Index(int? id) => View();
    public IActionResult StockIn(int? id) => View();
    public IActionResult StockOut(int? id) => View();
    public IActionResult Transfer(int? id) => View();
    public IActionResult Barcode(int? id) => View();
}
