using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;

namespace RetalineProAgent.Areas.Fleet.Controllers;

[Area("Fleet")]
[Authorize(Policy = "TenantAdmin")]
public class VehicleController : Controller
{
    public IActionResult Index(int? id) => View();
    public IActionResult Create(int? id) => View();
    public IActionResult Edit(int? id) => View();
    public IActionResult History(int? id) => View();
}
