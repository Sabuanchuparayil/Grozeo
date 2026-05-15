using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;

namespace RetalineProAgent.Areas.Business.Controllers;

[Area("Business")]
[Authorize(Policy = "TenantAdmin")]
public class AreaManagerController : Controller
{
    public IActionResult Index(int? id) => View();
    public IActionResult Create(int? id) => View();
    public IActionResult Edit(int? id) => View();
    public IActionResult Assign(int? id) => View();
}
