using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;

namespace RetalineProAgent.Areas.Tenant.Controllers;

[Area("Tenant")]
[Authorize]
public class DeliveryController : Controller
{
    public IActionResult Index(int? id) => View();
    public IActionResult Slots(int? id) => View();
    public IActionResult Rules(int? id) => View();
    public IActionResult Staff(int? id) => View();
    public IActionResult JobConfirm(int? id) => View();
}
