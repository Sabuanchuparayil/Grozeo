using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;

namespace RetalineProAgent.Areas.Finance.Controllers;

[Area("Finance")]
[Authorize(Policy = "FinanceUser")]
public class ReportController : Controller
{
    public IActionResult Index(int? id) => View();
    public IActionResult Sales(int? id) => View();
    public IActionResult GST(int? id) => View();
    public IActionResult TDS(int? id) => View();
    public IActionResult TCS(int? id) => View();
    public IActionResult ProfitLoss(int? id) => View();
    public IActionResult BalanceSheet(int? id) => View();
    public IActionResult TrialBalance(int? id) => View();
    public IActionResult Daybook(int? id) => View();
}
