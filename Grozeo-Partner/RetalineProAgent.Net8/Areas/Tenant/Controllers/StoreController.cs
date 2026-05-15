using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;

namespace RetalineProAgent.Areas.Tenant.Controllers;

[Area("Tenant")]
[Authorize(Policy = "TenantAdmin")]
public class StoreController : Controller
{
    public IActionResult Index(int? id) => View();
    public IActionResult Settings(int? id) => View();
    public IActionResult Branches(int? id) => View();
    public IActionResult Domains(int? id) => View();
    public IActionResult Subscription(int? id) => View();
}
