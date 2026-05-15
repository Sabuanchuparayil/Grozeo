using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;

namespace RetalineProAgent.Controllers;

[AllowAnonymous]
public class ErrorController : Controller
{
    [Route("Error/{statusCode:int}")]
    public IActionResult HttpStatusCodeHandler(int statusCode)
    {
        ViewBag.StatusCode = statusCode;
        ViewBag.Title = statusCode switch
        {
            404 => "Page Not Found",
            403 => "Access Forbidden",
            500 => "Server Error",
            _ => "Error"
        };
        ViewBag.Message = statusCode switch
        {
            404 => "The page you're looking for doesn't exist or has been moved.",
            403 => "You don't have permission to access this resource.",
            500 => "Something went wrong on our end. Please try again later.",
            _ => "An unexpected error occurred."
        };
        
        return View("StatusCode");
    }

    [Route("Error")]
    public IActionResult Error()
    {
        return View();
    }
}
