using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;

namespace RetalineProAgent.Areas.Operation.Controllers;

[Area("Operation")]
[Authorize(Policy = "TenantAdmin")]
public class MonitoringController : Controller
{
    public IActionResult Index(int? id) => View();
    public IActionResult PackingDelays(int? id) => View();
    public IActionResult DeliveryDelays(int? id) => View();
    public IActionResult LiveOrders(int? id) => View();
}
