using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;

namespace RetalineProAgent.Areas.Support.Controllers;

[Area("Support")]
[Authorize(Policy = "SupportAgent")]
public class KnowledgeController : Controller
{
    public IActionResult Index(int? id) => View();
    public IActionResult Articles(int? id) => View();
    public IActionResult FAQ(int? id) => View();
}
