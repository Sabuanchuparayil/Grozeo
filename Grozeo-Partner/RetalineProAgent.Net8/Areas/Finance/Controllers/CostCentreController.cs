using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;

namespace RetalineProAgent.Areas.Finance.Controllers;

[Area("Finance")]
[Authorize(Policy = "FinanceUser")]
public class CostCentreController : Controller
{
    public IActionResult Index(int? id) => View();
    public IActionResult Entry(int? id) => View();
    public IActionResult Allocation(int? id) => View();
    public IActionResult Reports(int? id) => View();
}
