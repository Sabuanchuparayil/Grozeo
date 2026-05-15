using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;

namespace RetalineProAgent.Areas.Finance.Controllers;

[Area("Finance")]
[Authorize(Policy = "FinanceUser")]
public class VoucherController : Controller
{
    public IActionResult Index(int? id) => View();
    public IActionResult Entry(int? id) => View();
    public IActionResult Details(int? id) => View();
    public IActionResult AutoPosting(int? id) => View();
}
