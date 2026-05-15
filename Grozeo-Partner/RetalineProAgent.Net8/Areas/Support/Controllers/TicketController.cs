using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using RetalineProAgent.Models;
using RetalineProAgent.Services;

namespace RetalineProAgent.Areas.Support.Controllers;

[Area("Support")]
[Authorize(Policy = "SupportAgent")]
public class TicketController : Controller
{
    public IActionResult Index(int? id, string? status)
    {
        var tickets = MockDataService.GetTickets();
        if (!string.IsNullOrEmpty(status) && status != "All")
            tickets = tickets.Where(t => t.Status == status).ToList();
        ViewBag.CurrentStatus = status ?? "All";
        return View(tickets);
    }

    public IActionResult Create(int? id)
    {
        return View(new TicketViewModel());
    }

    [HttpPost]
    public IActionResult Create(TicketViewModel model)
    {
        TempData["Success"] = "Ticket created successfully.";
        return RedirectToAction("Index");
    }

    public IActionResult Details(string? id)
    {
        var tickets = MockDataService.GetTickets();
        var ticket = tickets.FirstOrDefault(t => t.TicketNumber == id) ?? tickets.FirstOrDefault();
        if (ticket == null)
        {
            TempData["Error"] = "Ticket not found.";
            return RedirectToAction("Index");
        }
        return View(ticket);
    }

    [HttpPost]
    public IActionResult Resolve(string? id)
    {
        TempData["Success"] = $"Ticket {id} has been marked as resolved.";
        return RedirectToAction("Index");
    }

    [HttpPost]
    public IActionResult Escalate(string? id)
    {
        TempData["Success"] = $"Ticket {id} has been escalated.";
        return RedirectToAction("Index");
    }
}
