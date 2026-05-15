using DataEntry;
using log4net;
using log4net.Config;
using System.Reflection;
using System.IO;
using Microsoft.AspNetCore.Http;
using Microsoft.Extensions.Logging;

var builder = WebApplication.CreateBuilder(args);

builder.Services.AddLogging();
builder.Services.AddHostedService<AuditBackgroundService>();

var app = builder.Build();

var logRepository = LogManager.GetRepository(Assembly.GetExecutingAssembly());
XmlConfigurator.Configure(logRepository, new FileInfo("log4net.config"));

app.MapGet("/health", () => Results.Ok(new { status = "ok", service = "finascop-dataentry" }));

app.MapMethods("/api/FinascopDataEntry", new[] { "GET", "POST" }, async (HttpContext ctx, ILogger<Program> log) =>
{
    return await FinascopDataEntry.Run(ctx.Request, log);
});

app.MapMethods("/api/AddCostCentreEntries", new[] { "GET", "POST" }, async (HttpContext ctx, ILogger<Program> log) =>
{
    return await AddCostCentreEntries.Run(ctx.Request, log);
});

app.MapMethods("/api/CreateTenantLedger", new[] { "GET", "POST" }, async (HttpContext ctx, ILogger<Program> log) =>
{
    return await CreateTenantLedger.Run(ctx.Request, log);
});

app.MapMethods("/api/CreateGroupLedger", new[] { "GET", "POST" }, async (HttpContext ctx, ILogger<Program> log) =>
{
    return await CreateGroupLedger.Run(ctx.Request, log);
});

app.MapMethods("/api/GetTenantTransactions", new[] { "GET", "POST" }, async (HttpContext ctx, ILogger<Program> log) =>
{
    return await GetTenantTransactions.Run(ctx.Request, log);
});

app.MapMethods("/api/DailySalesReport", new[] { "GET", "POST" }, async (HttpContext ctx, ILogger<Program> log) =>
{
    return await DailySalesReport.Run(ctx.Request, log);
});

app.Run();

public partial class Program { }
