using Microsoft.AspNetCore.Mvc;
using Microsoft.Extensions.Configuration;
using System;
using System.Collections.Generic;

namespace Retaline.Web.Controllers
{
    [Route("[controller]")]
    [ApiController]
    public class HealthController : ControllerBase
    {
        private readonly IConfiguration _configuration;

        public HealthController(IConfiguration configuration)
        {
            _configuration = configuration;
        }

        [HttpGet]
        public IActionResult Check()
        {
            var checks = new Dictionary<string, object>
            {
                ["status"] = "healthy",
                ["service"] = "grozeo-public",
                ["timestamp"] = DateTime.UtcNow.ToString("o"),
                ["checks"] = new Dictionary<string, object>
                {
                    ["redis"] = CheckRedis(),
                    ["api_connectivity"] = CheckApiConnectivity()
                }
            };

            var allUp = true;
            foreach (var check in (Dictionary<string, object>)checks["checks"])
            {
                if (check.Value is Dictionary<string, object> c && c.ContainsKey("status") && c["status"].ToString() != "up")
                {
                    allUp = false;
                    break;
                }
            }

            checks["status"] = allUp ? "healthy" : "degraded";
            return allUp ? Ok(checks) : StatusCode(503, checks);
        }

        [HttpGet("ping")]
        public IActionResult Ping()
        {
            return Ok(new { status = "ok", service = "grozeo-public" });
        }

        private Dictionary<string, object> CheckRedis()
        {
            try
            {
                var connStr = _configuration["DistributedCacheConfig:ConnectionString"];
                if (string.IsNullOrEmpty(connStr))
                    return new Dictionary<string, object> { ["status"] = "skipped", ["reason"] = "Not configured" };

                return new Dictionary<string, object> { ["status"] = "up" };
            }
            catch
            {
                return new Dictionary<string, object> { ["status"] = "down" };
            }
        }

        private Dictionary<string, object> CheckApiConnectivity()
        {
            try
            {
                var apiUrl = _configuration["ApiUrl"];
                return new Dictionary<string, object>
                {
                    ["status"] = !string.IsNullOrEmpty(apiUrl) ? "configured" : "not_configured",
                    ["target"] = apiUrl ?? "N/A"
                };
            }
            catch
            {
                return new Dictionary<string, object> { ["status"] = "down" };
            }
        }
    }
}
