using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using RetalineProAgent.Services;

namespace RetalineProAgent.Areas.Business.Controllers;

[Area("Business")]
[Authorize(Policy = "TenantAdmin")]
public class CRMController : Controller
{
    public IActionResult Index(int? id)
    {
        var customers = MockDataService.GetCustomers();
        return View(customers);
    }

    public IActionResult Prospects(int? id)
    {
        var customers = MockDataService.GetCustomers().Where(c => c.Status == "New").ToList();
        return View(customers);
    }

    public IActionResult Retailers(int? id)
    {
        var customers = MockDataService.GetCustomers().Where(c => c.TotalOrders > 10).ToList();
        return View(customers);
    }

    public IActionResult Leads(int? id)
    {
        var leads = MockDataService.GetLeads();
        return View(leads);
    }

    public IActionResult Contacts(int? id)
    {
        var customers = MockDataService.GetCustomers();
        return View(customers);
    }

    public IActionResult Followups(int? id)
    {
        var leads = MockDataService.GetLeads().Where(l => l.Status == "Contacted" || l.Status == "Qualified").ToList();
        return View(leads);
    }
}
