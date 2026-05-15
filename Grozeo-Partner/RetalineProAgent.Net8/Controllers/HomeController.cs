using Microsoft.AspNetCore.Mvc;

namespace RetalineProAgent.Controllers;

public class HomeController : Controller
{
    public IActionResult Index() =>
        RedirectToAction("Login", "Account", new { area = "Account" });

    public IActionResult Error() => View();
}
