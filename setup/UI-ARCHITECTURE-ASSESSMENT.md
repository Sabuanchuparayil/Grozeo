# Grozeo Platform — UI Architecture Assessment Report

**Date:** 2026-05-15
**Scope:** All 7 services — Public, Partner, Bizadmin, Manage-Products, Bizapi, Scheduler, Finascop
**Focus:** Security vulnerabilities, performance gaps, architecture debt, modernization needs

---

## Executive Summary

The Grozeo UI layer has **critical security vulnerabilities** in the legacy PHP services (Bizadmin, Manage-Products) that must be fixed before production deployment. The modern .NET and Laravel services are significantly better but have gaps in resilience, accessibility, and framework currency. The platform lacks a shared design system, has no unified authentication, and runs on two end-of-life frameworks (.NET 6 and .NET Core 3.1).

### Severity Distribution

| Severity | Count | Services Affected |
|----------|-------|-------------------|
| **CRITICAL** | 5 | Bizadmin, Manage-Products, Finascop, Public |
| **HIGH** | 7 | All services |
| **MEDIUM** | 9 | All services |
| **LOW** | 6 | Various |

---

## CRITICAL Issues (Fix Before Production)

### C1. SQL Injection — Full Database Compromise (Bizadmin + Manage-Products)

**Severity: CRITICAL** | **Services: Bizadmin, Manage-Products**

The `modules/ui/index.php` endpoint allows attackers to inject arbitrary SQL — including table names, column names, sort direction, and WHERE clauses — directly from POST parameters:

```php
// Bizadmin/modules/ui/index.php:177-178
$total = $db->getItemFromDB(sprintf("SELECT %s FROM %s {$where} ORDER BY %s %s",
    'count(1)', $_POST['type'], $_POST['sort'], $_POST['dir']));
$query = sprintf("SELECT %s FROM %s {$where} ORDER BY %s %s",
    $_POST['fields'], $_POST['type'], $_POST['sort'], $_POST['dir']);
```

An attacker can read any table, extract passwords, drop tables, or escalate to OS-level access via `INTO OUTFILE` or `LOAD_FILE()`. The `mkCombo` operation has the same issue.

**Scale:** 732 potentially unsafe SQL patterns found in Bizadmin, 565 in Manage-Products.

**Some modules use safe patterns** (`buildSafeFilterQuery`, `getItemSafe` with parameterized queries) — but the majority do not.

**Fix:** Implement a whitelist of allowed table/field names in the `mkGrid` and `mkCombo` endpoints. Migrate all raw SQL concatenation to parameterized queries.

---

### C2. CSRF Protection Exists But Is Not Enforced (Bizadmin + Manage-Products)

**Severity: CRITICAL** | **Services: Bizadmin, Manage-Products**

The `security_bootstrap.php` defines `verify_csrf_token()` and `csrf_field()` functions, but they are **never called** in the module handlers. No POST request actually validates the CSRF token. The functions exist as dead code.

An attacker can craft a page that makes authenticated admin users perform arbitrary actions (modify orders, delete products, change prices) just by visiting a malicious link.

**Fix:** Add `verify_csrf_token()` call at the top of index.php for all POST requests. Add `csrf_field()` to all ExtJS form submissions.

---

### C3. End-of-Life Frameworks Without Security Patches

**Severity: CRITICAL** | **Services: Public (.NET 6), Finascop (.NET Core 3.1)**

| Framework | EOL Date | Risk |
|-----------|----------|------|
| **.NET 6** (Grozeo-Public) | Nov 12, 2024 | No security patches for 18+ months |
| **.NET Core 3.1** (Finascop) | Dec 13, 2022 | No security patches for 3+ years |

Both are running in production without vendor security support. Any CVE discovered in the ASP.NET runtime will not be patched.

**Fix:**
- Upgrade Grozeo-Public from .NET 6 to .NET 8 (LTS, supported until Nov 2026)
- Upgrade Finascop from .NET Core 3.1 to .NET 8 (or retire the Azure Functions v3 approach)

---

### C4. PHPMailer 5.1 — Known Remote Code Execution (CVE-2016-10033)

**Severity: CRITICAL** | **Services: Bizadmin, Manage-Products**

`includes/class.phpmailer.php` is PHPMailer version 5.1 from 2009. This version has multiple known CVEs:
- **CVE-2016-10033**: Remote code execution via crafted Sender property
- **CVE-2016-10045**: Bypass of CVE-2016-10033 fix
- **CVE-2017-5223**: Local file disclosure via XML injection

**Fix:** Replace the bundled PHPMailer with a modern version via Composer (`phpmailer/phpmailer` ^6.9) or use Laravel's mail system.

---

### C5. Stored XSS via Raw HTML Output (Public)

**Severity: CRITICAL** | **Services: Public**

The Public storefront renders API responses as raw HTML in 15+ views:

```csharp
// Views/Shared/Components/ProductShortDesc/Default.cshtml:78
<p>@Html.Raw(Model?.Product?.ShortDesc)</p>

// Views/Info/About.cshtml:25
@(new HtmlString(HttpUtility.HtmlDecode(content)))

// Views/MyAccount/Invoice.cshtml:16
@(new HtmlString(Model.Data))
```

If an admin (via Bizadmin) enters malicious HTML/JS in a product description, info page, or invoice template, it executes in every customer's browser. This is a stored XSS vector that crosses service boundaries.

**Fix:** Sanitize all HTML content from the API before rendering. Use a library like HtmlSanitizer to allow safe tags only.

---

## HIGH Issues

### H1. No HTTP Resilience for API Calls (Public + Partner)

**Severity: HIGH** | **Services: Public, Partner .NET 8**

The Public storefront registers `HttpClient` without any retry, timeout, or circuit breaker configuration:

```csharp
// Startup.cs:105
services.AddHttpClient();  // bare, no Polly, no timeout
```

If Bizapi is slow or down, the Public site hangs indefinitely. There is no graceful degradation — pages either work or timeout after the TCP default (often 100+ seconds).

**Fix:** Add Polly policies via `Microsoft.Extensions.Http.Polly`:
- Retry: 3 attempts with exponential backoff
- Circuit breaker: break after 5 failures, wait 30 seconds
- Timeout: 10 seconds per request

---

### H2. No API Versioning (Bizapi)

**Severity: HIGH** | **Service: Bizapi**

All API routes are unversioned (`/api/signup/`, `/api/cart/`). There is no `/api/v1/` prefix. Any breaking change to the API immediately breaks all UI services.

With 5 separate UI services consuming this API, a single breaking change can cascade across the entire platform.

**Fix:** Introduce `/api/v1/` prefix for all routes. New breaking changes go into `/api/v2/` while old clients migrate.

---

### H3. Inconsistent API Response Format (Bizapi)

**Severity: HIGH** | **Service: Bizapi**

Responses use at least 4 different formats:

```json
// Format 1: error key
{"error": "Unauthorized"}

// Format 2: status key
{"status": "success"}

// Format 3: SuccessWithData wrapper
{"success": true, "data": {...}}

// Format 4: raw data
{...user object...}
```

Frontend code must handle every variation, leading to bugs when a controller returns a different format than expected.

**Fix:** Standardize on a single envelope format across all controllers:
```json
{"success": bool, "data": any, "message": string, "errors": object}
```

---

### H4. No Shared Authentication / SSO

**Severity: HIGH** | **All UI services**

Each service has its own separate authentication:
- **Public**: JWT tokens from Bizapi
- **Partner .NET 8**: Cookie-based auth (`GrozeoPartner.Auth`)
- **Bizadmin**: PHP sessions
- **Manage-Products**: PHP sessions (separate)

Users must log in separately to each service. Admin users who work across Bizadmin and Partner must maintain two sessions.

**Fix (short-term):** Implement SSO via shared JWT validation across all services.
**Fix (long-term):** Centralize auth in Bizapi and use OAuth2/OIDC for all frontends.

---

### H5. ExtJS 3.3.1 — Unmaintainable, Unsupported (Bizadmin + Manage-Products)

**Severity: HIGH** | **Services: Bizadmin, Manage-Products**

ExtJS 3.3.1 was released in 2010 (16 years ago). It:
- Has no security patches
- Is not compatible with modern browsers (uses deprecated APIs)
- Cannot be updated (Sencha changed licensing in v4+)
- Has no mobile support
- Makes it impossible to hire developers who know it

The entire Bizadmin and Manage-Products UI is built on this framework.

**Fix:** Plan a phased migration to a modern framework. Given the admin panel nature of these apps, consider React + Ant Design or similar admin-focused frameworks. This is a major project (50+ modules) and should be planned over multiple quarters.

---

### H6. Unbounded Query Results (Bizapi)

**Severity: HIGH** | **Service: Bizapi**

Multiple controller endpoints call `->get()` or `->all()` without pagination:

```php
// CategoryScreenController.php:303
$data = $this->concern->select([...])->get();

// CategoryScreenController.php:368
$product = $query->get();

// RetailerController.php:40,72,108
->get();  // no limit
```

A category with 10,000 products will dump the entire result set into memory, causing slow responses or crashes.

**Fix:** Enforce pagination on all list endpoints. Add a `per_page` parameter with a default of 20 and a max of 100.

---

### H7. Anti-Forgery Only on Login (Partner .NET 8)

**Severity: HIGH** | **Service: Partner .NET 8**

`[ValidateAntiForgeryToken]` is only applied to the Account controller (login/logout). The 20+ other controllers handling orders, products, inventory, delivery, and CRM operations have no CSRF protection.

**Fix:** Add `[AutoValidateAntiforgeryToken]` as a global filter in `Program.cs`, or apply `[ValidateAntiForgeryToken]` to all POST/PUT/DELETE actions.

---

## MEDIUM Issues

### M1. PWA Service Worker Is Non-Functional

**Severity: MEDIUM** | **Service: Public**

The service worker does nothing:
```javascript
// service-worker.js
self.addEventListener('fetch', () => { });
```

The `manifest.json` is properly configured with icons and shortcuts, but without a caching strategy in the service worker, the PWA offers no offline capability, no push notifications, and no performance benefit.

**Fix:** Implement a cache-first strategy for static assets and a network-first strategy for API calls using Workbox.

---

### M2. No Image Lazy Loading (Public)

**Severity: MEDIUM** | **Service: Public**

No `<img>` tags use `loading="lazy"`. Product listing pages with dozens of images load all images immediately, hurting initial page load time on mobile.

**Fix:** Add `loading="lazy"` to all product images, category images, and below-the-fold content.

---

### M3. Missing Alt Text on Images — Accessibility (Public)

**Severity: MEDIUM** | **Service: Public**

15+ `<img>` tags have no `alt` attribute across views including error pages, wishlist, wallet, orders, and branch selection. This fails WCAG 2.1 Level A compliance and hurts SEO.

**Fix:** Add descriptive `alt` attributes to all `<img>` tags. Use empty `alt=""` for decorative images.

---

### M4. No Content-Security-Policy Header (Public)

**Severity: MEDIUM** | **Service: Public**

Bizadmin has security headers via `security_bootstrap.php`, but the Public storefront has no CSP, no X-Frame-Options, no X-Content-Type-Options configured in its Startup.cs middleware.

**Fix:** Add security headers middleware:
```csharp
app.Use(async (context, next) => {
    context.Response.Headers.Append("X-Content-Type-Options", "nosniff");
    context.Response.Headers.Append("X-Frame-Options", "SAMEORIGIN");
    context.Response.Headers.Append("Content-Security-Policy", "default-src 'self'; ...");
    await next();
});
```

---

### M5. Developer Exception Page in Development Mode Can Leak (Public)

**Severity: MEDIUM** | **Service: Public**

```csharp
// Startup.cs:200
app.UseDeveloperExceptionPage();  // in Development mode
```

If `ASPNETCORE_ENVIRONMENT` is accidentally set to `Development` in production (misconfigured env var), stack traces, source code, and connection strings are exposed to users.

**Fix:** Remove `UseDeveloperExceptionPage()` entirely or add a hard check:
```csharp
if (env.IsDevelopment() && !env.IsProduction()) { ... }
```

---

### M6. No Design System / Component Library

**Severity: MEDIUM** | **All UI services**

Each service uses a completely different frontend stack:
- **Public**: Custom CSS + jQuery
- **Partner .NET 8**: Bootstrap + jQuery
- **Bizadmin**: ExtJS 3.x
- **Manage-Products**: ExtJS 3.x (copy of Bizadmin)

There is no shared component library, design tokens, or CSS variables. Branding consistency is manually maintained.

**Fix (long-term):** Create a shared CSS design token file (colors, typography, spacing) and a lightweight component library for common elements (buttons, forms, tables).

---

### M7. Multi-Tenant CSS Injection Risk (Public)

**Severity: MEDIUM** | **Service: Public**

Custom themes are loaded from `wwwroot/customthemes/` per tenant. The theme loading mechanism should be checked to ensure tenant-provided CSS cannot:
- Include `url()` references to external tracking pixels
- Use `content:` property for data exfiltration
- Override critical UI elements (hiding prices, changing checkout flow)

**Fix:** Validate tenant CSS on upload. Strip `url()`, `@import`, `expression()`, and `behavior:` properties.

---

### M8. Bizapi 600 Requests/Min Global Rate Limit Is Too Permissive

**Severity: MEDIUM** | **Service: Bizapi**

```php
// Kernel.php:44
'throttle:600,1'  // 600 requests per minute globally
```

Auth endpoints are properly limited (3-5/min), but the global limit of 600/min per IP is generous. A single attacker can make 10 requests/second before being throttled.

**Fix:** Reduce global limit to 120-180/min. Add per-endpoint limits for resource-intensive operations (search, reports).

---

### M9. Debug Code Left in Production (Bizadmin + Manage-Products)

**Severity: MEDIUM** | **Services: Bizadmin, Manage-Products**

22+ commented-out `print_r($_POST)` and `print_r($_GET)` lines found across modules. While currently harmless (commented), they indicate a pattern of debug-by-print that can easily leak data when uncommented during troubleshooting.

The `mkGrid` endpoint also exposes query strings in development:
```php
if (strpos($_SERVER['HTTP_HOST'], 'sil.lab')) {
    $data["query"] = $query;  // leaks SQL queries
}
```

**Fix:** Remove all debug print_r statements. Replace with proper structured logging.

---

## LOW Issues

### L1. Manifest PWA Icons Use SVG Only

**Severity: LOW** | **Service: Public**

All PWA icons reference `/images/Footer_Logo.svg`. Some older Android devices don't support SVG icons in PWA manifests and require rasterized PNG icons at specific sizes (192x192, 512x512).

---

### L2. Session Cookie Domain Too Narrow (Bizadmin)

**Severity: LOW** | **Service: Bizadmin**

```php
session_set_cookie_params(0, dirname($_SERVER['PHP_SELF']), $_SERVER['SERVER_NAME']);
```

The cookie domain is set to `$_SERVER['SERVER_NAME']` which can be manipulated by the `Host` header. Should use a hardcoded domain.

---

### L3. Prototype.js Loaded (Bizadmin)

**Severity: LOW** | **Services: Bizadmin, Manage-Products**

Prototype.js (circa 2007) is included alongside ExtJS. It conflicts with modern JavaScript (modifies Array.prototype, Object.prototype). This will cause issues if any modern JS library is added.

---

### L4. Azure Connection String in Public appsettings.json

**Severity: LOW** | **Service: Public**

`appsettings.json` contains an Azure SQL connection string with a password placeholder `$(AZURE_DB_PASSWORD)`. While this uses variable substitution, the hostname and username are exposed. This should be moved entirely to environment variables.

---

### L5. No Compression Middleware (Public)

**Severity: LOW** | **Service: Public**

No `UseResponseCompression()` middleware is configured. All HTML, JSON, and CSS responses are sent uncompressed, increasing bandwidth usage.

---

### L6. Copy-Paste Codebase (Manage-Products = Bizadmin Fork)

**Severity: LOW** | **Services: Bizadmin, Manage-Products**

Manage-Products is a near-identical copy of Bizadmin (same module structure, same `ui/index.php` SQL injection, same ExtJS). Bug fixes in one are not propagated to the other.

---

## Upgrade Roadmap (Recommended Priority)

### Phase 1: Security Hardening (Week 1-2)
1. Fix SQL injection in Bizadmin/Manage-Products `mkGrid` and `mkCombo` (C1)
2. Enforce CSRF token validation (C2)
3. Replace PHPMailer 5.1 with modern version (C4)
4. Sanitize `@Html.Raw()` outputs in Public (C5)
5. Add anti-forgery tokens globally in Partner .NET 8 (H7)

### Phase 2: Framework Upgrades (Week 3-6)
6. Upgrade Grozeo-Public from .NET 6 to .NET 8 (C3)
7. Upgrade Finascop from .NET Core 3.1 to .NET 8 (C3)
8. Add Polly resilience to HTTP clients (H1)
9. Add security headers to Public (M4)
10. Standardize Bizapi response format (H3)

### Phase 3: API Improvements (Week 7-8)
11. Add API versioning `/api/v1/` (H2)
12. Enforce pagination on all list endpoints (H6)
13. Tighten rate limits (M8)
14. Remove debug code (M9)

### Phase 4: UX & Performance (Week 9-12)
15. Implement PWA service worker caching (M1)
16. Add lazy loading for images (M2)
17. Fix accessibility — alt text, ARIA (M3)
18. Add response compression (L5)
19. Implement shared SSO (H4)

### Phase 5: Modernization (Quarter 2-4)
20. Begin Bizadmin migration from ExtJS to modern framework (H5)
21. Establish shared design system (M6)
22. Consolidate Manage-Products into Bizadmin (L6)
