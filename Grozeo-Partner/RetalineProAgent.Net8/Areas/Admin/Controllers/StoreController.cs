using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;

namespace RetalineProAgent.Areas.Admin.Controllers;

[Area("Admin")]
[Authorize(Policy = "SuperAdmin")]
public class StoreController : Controller
{
    public IActionResult Index(int? id) => View();
    public IActionResult Domains(int? id) => View();
    public IActionResult Config(int? id) => View();
}
