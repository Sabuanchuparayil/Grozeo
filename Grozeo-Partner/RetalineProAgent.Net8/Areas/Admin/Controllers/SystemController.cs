using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;

namespace RetalineProAgent.Areas.Admin.Controllers;

[Area("Admin")]
[Authorize(Policy = "SuperAdmin")]
public class SystemController : Controller
{
    public IActionResult Index(int? id) => View();
    public IActionResult Jobs(int? id) => View();
    public IActionResult Logs(int? id) => View();
    public IActionResult Settings(int? id) => View();
}
