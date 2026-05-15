using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;

namespace RetalineProAgent.Areas.Finance.Controllers;

[Area("Finance")]
[Authorize(Policy = "FinanceUser")]
public class LedgerController : Controller
{
    public IActionResult Index(int? id) => View();
    public IActionResult Create(int? id) => View();
    public IActionResult Edit(int? id) => View();
    public IActionResult Groups(int? id) => View();
}
