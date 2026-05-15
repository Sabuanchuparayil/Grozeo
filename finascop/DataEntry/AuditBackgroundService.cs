using System;
using System.Threading;
using System.Threading.Tasks;
using Microsoft.Extensions.Hosting;
using Microsoft.Extensions.Logging;

namespace DataEntry
{
    public class AuditBackgroundService : BackgroundService
    {
        private readonly ILogger<AuditBackgroundService> _logger;

        public AuditBackgroundService(ILogger<AuditBackgroundService> logger)
        {
            _logger = logger;
        }

        protected override async Task ExecuteAsync(CancellationToken stoppingToken)
        {
            while (!stoppingToken.IsCancellationRequested)
            {
                var now = DateTime.UtcNow;
                var nextRun = now.Date.AddDays(1).AddMinutes(5);
                var delay = nextRun - now;
                if (delay < TimeSpan.Zero)
                    delay = TimeSpan.FromMinutes(1);

                await Task.Delay(delay, stoppingToken);

                if (stoppingToken.IsCancellationRequested) break;

                try
                {
                    _logger.LogInformation("Running FinascopAudit at {Time}", DateTime.UtcNow);
                    var audit = new FinascopAudit();
                    audit.Run(_logger);
                }
                catch (Exception ex)
                {
                    _logger.LogError(ex, "FinascopAudit background task failed");
                }
            }
        }
    }
}
