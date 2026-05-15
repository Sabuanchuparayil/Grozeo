using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;

namespace RetalineProAgent.Areas.Marketing.Controllers;

[Area("Marketing")]
[Authorize(Policy = "TenantAdmin")]
public class LeadsController : Controller
{
    public IActionResult Index(int? id) => View();
    public IActionResult Import(int? id) => View();
    public IActionResult Assign(int? id) => View();
}
