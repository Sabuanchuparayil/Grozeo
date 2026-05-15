using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;

namespace RetalineProAgent.Areas.Marketing.Controllers;

[Area("Marketing")]
[Authorize(Policy = "TenantAdmin")]
public class CampaignsController : Controller
{
    public IActionResult Index(int? id) => View();
    public IActionResult Create(int? id) => View();
    public IActionResult Edit(int? id) => View();
    public IActionResult Analytics(int? id) => View();
}
