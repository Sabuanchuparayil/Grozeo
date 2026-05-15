using Microsoft.AspNetCore.Authentication.Cookies;
using RetalineProAgent.Services;

var builder = WebApplication.CreateBuilder(args);

builder.Services.AddAuthentication(CookieAuthenticationDefaults.AuthenticationScheme)
    .AddCookie(options =>
    {
        options.LoginPath     = "/Account/Account/Login";
        options.LogoutPath    = "/Account/Account/Logout";
        options.AccessDeniedPath = "/Account/Account/AccessDenied";
        options.ExpireTimeSpan   = TimeSpan.FromHours(8);
        options.SlidingExpiration = true;
        options.Cookie.HttpOnly   = true;
        options.Cookie.SecurePolicy = CookieSecurePolicy.SameAsRequest;
        options.Cookie.SameSite   = SameSiteMode.Lax;
        options.Cookie.Name       = "GrozeoPartner.Auth";
    });

builder.Services.AddAuthorization(options =>
{
    options.AddPolicy("SuperAdmin",   p => p.RequireRole("SuperAdmin"));
    options.AddPolicy("TenantAdmin",  p => p.RequireRole("TenantAdmin", "SuperAdmin"));
    options.AddPolicy("FinanceUser",  p => p.RequireRole("Finance", "TenantAdmin", "SuperAdmin"));
    options.AddPolicy("SupportAgent", p => p.RequireRole("Support", "TenantAdmin", "SuperAdmin"));
});

builder.Services.AddControllersWithViews(options =>
{
    options.Filters.Add(new Microsoft.AspNetCore.Mvc.AutoValidateAntiforgeryTokenAttribute());
});
builder.Services.AddSession(options =>
{
    options.IdleTimeout = TimeSpan.FromMinutes(60);
    options.Cookie.HttpOnly = true;
    options.Cookie.IsEssential = true;
    options.Cookie.SecurePolicy = CookieSecurePolicy.SameAsRequest;
});

builder.Services.AddHttpContextAccessor();
builder.Services.AddHttpClient("GrozeoApi", client =>
{
    client.Timeout = TimeSpan.FromSeconds(30);
});
builder.Services.AddHttpClient();
builder.Services.AddScoped<IDataService,    DataService>();
builder.Services.AddScoped<IUserService,    UserService>();
builder.Services.AddScoped<ISettlementService, SettlementService>();
builder.Services.AddScoped<IReportService,  ReportService>();
builder.Services.AddScoped<IProductService, ProductService>();
builder.Services.AddScoped<IOrderService,   OrderService>();
builder.Services.AddScoped<IFinanceService, FinanceService>();
builder.Services.AddScoped<IExcelExportService, ExcelExportService>();
builder.Services.AddAntiforgery(opts => opts.HeaderName = "X-CSRF-TOKEN");

var app = builder.Build();

await RetalineProAgent.Services.DatabaseInitializer.InitializeAsync(
    app.Configuration, app.Logger);

if (!app.Environment.IsDevelopment())
{
    app.UseExceptionHandler("/Error");
    app.UseStatusCodePagesWithReExecute("/Error/{0}");
    app.UseHsts();
}
else
{
    app.UseDeveloperExceptionPage();
}

var forwardedHeadersOptions = new ForwardedHeadersOptions
{
    ForwardedHeaders = Microsoft.AspNetCore.HttpOverrides.ForwardedHeaders.XForwardedFor
                     | Microsoft.AspNetCore.HttpOverrides.ForwardedHeaders.XForwardedProto
};
forwardedHeadersOptions.KnownNetworks.Clear();
forwardedHeadersOptions.KnownProxies.Clear();
app.UseForwardedHeaders(forwardedHeadersOptions);
app.UseStaticFiles();
app.Use(async (ctx, next) =>
{
    ctx.Response.Headers.Append("X-Content-Type-Options", "nosniff");
    ctx.Response.Headers.Append("X-Frame-Options",        "SAMEORIGIN");
    ctx.Response.Headers.Append("X-XSS-Protection",       "1; mode=block");
    ctx.Response.Headers.Append("Strict-Transport-Security", "max-age=31536000; includeSubDomains");
    ctx.Response.Headers.Remove("Server");
    await next();
});

app.UseRouting();
app.UseSession();
app.UseAuthentication();
app.UseAuthorization();

app.MapGet("/health", () => Results.Ok("healthy"));
app.MapControllerRoute("areas",   "{area:exists}/{controller=Dashboard}/{action=Index}/{id?}");
app.MapControllerRoute("default", "{controller=Home}/{action=Index}/{id?}");

app.Run();
