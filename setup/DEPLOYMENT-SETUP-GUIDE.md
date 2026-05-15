# Grozeo Platform — Deployment Setup Guide

## Super Admin Credentials
- **Email:** mail@jsabu.com
- **Password:** Admin@1234
- **IMPORTANT:** Change this password immediately after first login in production.

---

## Table of Contents
1. [Infrastructure Requirements](#1-infrastructure-requirements)
2. [Database Setup](#2-database-setup)
3. [Third-Party Accounts Required](#3-third-party-accounts-required)
4. [Service-by-Service Configuration](#4-service-by-service-configuration)
5. [Environment Variables Reference](#5-environment-variables-reference)
6. [Deployment Steps](#6-deployment-steps)
7. [Post-Deployment Checklist](#7-post-deployment-checklist)

---

## 1. Infrastructure Requirements

### Servers / Platforms
| Service | Runtime | Recommended Platform |
|---------|---------|---------------------|
| Grozeo-Bizapi | PHP 8.1+ / Laravel 10 | Railway / DigitalOcean App Platform |
| Grozeo-Scheduler | PHP 8.1+ / Laravel 10 | Railway / DigitalOcean (with cron) |
| Grozeo-Partner | .NET 4.7.2 (WebForms) | Azure App Service (Windows) |
| Grozeo-Partner Net8 | .NET 8 | Azure App Service / Docker |
| Grozeo-Public | .NET 6 (ASP.NET Core) | Azure App Service / Docker |
| Grozeo-Bizadmin | PHP 7.4+ (Legacy) | Any PHP host with cron |
| Manage-Products | PHP 7.4+ (Legacy) | Any PHP host |
| finascop | .NET Core 3.1 | Azure Functions |

### Databases
| Type | Purpose | Minimum Spec |
|------|---------|-------------|
| MySQL 8.0+ | Primary shared database | 4GB RAM, 50GB SSD |
| Azure SQL | Partner tenant database | Standard S2 tier |
| Redis 6+ | Caching, sessions, queues | 1GB RAM |
| AWS DynamoDB | Activity logs, email queue | On-demand capacity |

### Storage
| Service | Purpose |
|---------|---------|
| AWS S3 | Product images, uploads, tenant assets, finance docs, splash screens |
| Azure Blob Storage | Partner portal file uploads |

---

## 2. Database Setup

### Step 1: Create MySQL Database
```sql
CREATE DATABASE grozeo_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'grozeo_user'@'%' IDENTIFIED BY 'YOUR_STRONG_PASSWORD';
GRANT ALL PRIVILEGES ON grozeo_db.* TO 'grozeo_user'@'%';
FLUSH PRIVILEGES;
```

### Step 2: Run Migrations
```bash
# Bizapi
cd Grozeo-Bizapi
cp .env.example .env
# Fill in DB credentials in .env
php artisan migrate

# Scheduler (uses same database)
cd Grozeo-Scheduler
cp .env.example .env
# Fill in same DB credentials
php artisan migrate
```

### Step 3: Seed Super Admin
```bash
mysql -u grozeo_user -p grozeo_db < setup/super-admin-seed.sql
```

For Partner (Azure SQL), run the Azure SQL block from `super-admin-seed.sql` against the tenant database.

---

## 3. Third-Party Accounts Required

### CRITICAL — Required for Core Functionality

| Service | Purpose | Sign Up URL | Env Variables |
|---------|---------|-------------|---------------|
| **AWS Account** | S3, DynamoDB, SES | https://aws.amazon.com | `AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY` |
| **Redis** (AWS ElastiCache / Azure Cache) | Sessions, caching, queues | Cloud provider | `REDIS_HOST`, `REDIS_PASSWORD` |
| **Google Maps API** | Distance calculation, geocoding | https://console.cloud.google.com | `GOOGLE_API_KEY`, `GMAP_DIST_API_KEY` |

### PAYMENT GATEWAYS — Configure at least one

| Gateway | Markets | Sign Up URL | Env Variables |
|---------|---------|-------------|---------------|
| **Razorpay** | India | https://dashboard.razorpay.com | `RP_API_KEY_ID`, `RP_API_KEY` |
| **Stripe** | Global | https://dashboard.stripe.com | `STRIPE_KEY`, `STRIPE_SECRET` |
| **Paytm** | India | https://dashboard.paytm.com | `PAYTM_MERCHANT_KEY`, `PAYTM_MERCHANT_ID` |
| **Instamojo** | India | https://www.instamojo.com | `IM_API_KEY`, `IM_AUTH_TOKEN` |
| **CCAvenue** | India | https://www.ccavenue.com | `CCAVENUE_MERCHANT_ID`, `CCAVENUE_ACCESS_CODE`, `CCAVENUE_WORKING_KEY` |
| **Easebuzz** | India | https://dashboard.easebuzz.in | `EASEBUZZ_KEY`, `EASEBUZZ_SALT` |
| **Easypay** (Axis Bank) | India | Contact Axis Bank | `EASYPAY_CID`, `EASYPAY_CHECKSUM_KEY`, `EASYPAY_ENCRYPTION_KEY` |
| **Atom** | India | https://www.atomtech.in | `ATOM_LOGIN`, `ATOM_PASS`, `ATOM_REQHASHKEY`, `ATOM_RESPHASHKEY` |
| **Revolut** | UK/EU | https://business.revolut.com | `REVOLUT_API_KEY` |

### SMS PROVIDERS — Configure at least one

| Provider | Markets | Sign Up URL | Env Variables |
|----------|---------|-------------|---------------|
| **TextLocal** | India/UK | https://www.textlocal.in | `TEXTLOCAL_API_KEY`, `TEXT_LOCAL_SMS_PASSWORD` |
| **Kaleyra** | India/Global | https://www.kaleyra.com | `KSMS_API_KEY`, `KSMS_SENDER_ID` |
| **BulkSMS** | India | https://www.bulksmsind.in | `BULKSMS_USERNAME`, `BULKSMS_API_KEY` |
| **Airtel SMS** | India | Contact Airtel Business | `SMS_API_KEY` |

### EMAIL — Required

| Service | Purpose | Sign Up URL | Env Variables |
|---------|---------|-------------|---------------|
| **Amazon SES** | Transactional emails | https://aws.amazon.com/ses | `MAIL_USERNAME`, `MAIL_PASSWORD` (SMTP creds) |
| **SendGrid** | Email (Partner portal) | https://sendgrid.com | `SendGridAPIKey` (Web.config) |

### SHIPPING / COURIER — Optional

| Service | Purpose | Sign Up URL | Env Variables |
|---------|---------|-------------|---------------|
| **Shiprocket** | Order fulfillment (India) | https://app.shiprocket.in | `SHIPROCKET_EMAIL`, `SHIPROCKET_PASSWORD` |
| **Shipyaari** | Shipping (India) | https://www.shipyaari.com | `SHIPYAARI_CREATOR`, `SHIPYAARI_AVNKEY`, `SHIPYAARI_USERNAME` |
| **WorldOptions** | International shipping | https://www.worldoptions.com | `WORLDOPTIONS_KEY`, `WORLDOPTIONS_METERNUMBER`, `WORLDOPTIONS_PASSWORD` |

### SOCIAL LOGIN — Optional

| Service | Purpose | Sign Up URL | Config Location |
|---------|---------|-------------|-----------------|
| **Google OAuth** | Social login (Partner) | https://console.cloud.google.com/apis/credentials | `google_client_id`, `google_client_secret` (Web.config) |
| **Facebook OAuth** | Social login (Partner) | https://developers.facebook.com | `Facebook_AppId`, `Facebook_AppSecret` (Web.config) |

### ANALYTICS & MONITORING — Optional

| Service | Purpose | Sign Up URL | Config Location |
|---------|---------|-------------|-----------------|
| **Matomo** | Web analytics | https://matomo.org | `MatomoUrl`, `MatomoToken` (Web.config) |
| **Google reCAPTCHA** | Bot protection | https://www.google.com/recaptcha | `Recaptcha.Key`, `Recaptcha.Secret` (Web.config) |
| **Tawk.to** | Live chat widget | https://www.tawk.to | `TawkToPropertyId`, `TawkToWidgetId` (Web.config) |

### VERIFICATION APIs — Optional

| Service | Purpose | Sign Up URL | Config Location |
|---------|---------|-------------|-----------------|
| **FSSAI (Emptra)** | Food license verification | https://emptra.com | `FSSAIAPIKey`, `FSSAIAPIclientId` (Web.config) |
| **Surepass** | Identity verification | https://surepass.io | `SurepassAPIToken` (Web.config) |
| **VATSTACK** | VAT validation (UK/EU) | https://vatstack.com | `VATSTACKApiSecret` (Web.config) |
| **GetAddress.io** | UK address lookup | https://getaddress.io | `GetAddressIOAPIKey` (Web.config) |
| **Ideal Postcodes** | UK postcode lookup | https://ideal-postcodes.co.uk | `IdealPostcodeKey` (Web.config) |
| **IPInfo** | IP geolocation | https://ipinfo.io | `IPINFO_TOKEN` (.env) |

### PUSH NOTIFICATIONS — Optional

| Service | Purpose | Sign Up URL | Env Variables |
|---------|---------|-------------|---------------|
| **Firebase FCM** | Push notifications | https://console.firebase.google.com | `FCM_SERVER_KEY` |

### AI / GENERATIVE — Optional

| Service | Purpose | Sign Up URL | Config Location |
|---------|---------|-------------|-----------------|
| **Google Gemini** | Product description generation | https://aistudio.google.com | `googleDescriptionKey` (Web.config) |

---

## 4. Service-by-Service Configuration

### Grozeo-Bizapi (Laravel)
```bash
cd Grozeo-Bizapi
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
php artisan migrate
php artisan config:cache
php artisan route:cache
```

### Grozeo-Scheduler (Laravel)
```bash
cd Grozeo-Scheduler
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
# Set up cron: * * * * * cd /path/to/Grozeo-Scheduler && php artisan schedule:run >> /dev/null 2>&1
```

### Grozeo-Partner (.NET 4.7.2 + .NET 8)
1. Fill in all empty `value=""` fields in `RetalineProAgent/Web.config`
2. Set connection strings for Azure SQL and MySQL
3. Configure Azure Key Vault name in `KeyVaultName`
4. Deploy to Azure App Service (Windows)
5. For Net8 service: set environment variables via Azure portal or appsettings.json

### Grozeo-Public (.NET 6)
1. Configure `appsettings.json` with API URLs and tenant database connection
2. Set environment variables for production
3. Deploy to Azure App Service or Docker

### Grozeo-Bizadmin (Legacy PHP)
1. Configure database connection in `includes/config.php`
2. Set `SES_SMTP_USERNAME` and `SES_SMTP_PASSWORD` environment variables
3. Ensure PHP 7.4+ with mysql extensions

### Manage-Products (Legacy PHP)
1. Same as Bizadmin — configure `includes/config.php`
2. Set `SES_SMTP_USERNAME` and `SES_SMTP_PASSWORD` environment variables

### finascop (Azure Functions)
1. Configure `local.settings.json` or Azure Function App settings
2. Set MySQL and Azure SQL connection strings
3. Set encryption key via `ENCRYPTION_DEFAULT_KEY` environment variable

---

## 5. Environment Variables Reference

### Complete list of all environment variables used across the platform:

#### AWS
| Variable | Service(s) | Description |
|----------|-----------|-------------|
| `AWS_ACCESS_KEY_ID` | Bizapi, Scheduler | AWS IAM access key |
| `AWS_SECRET_ACCESS_KEY` | Bizapi, Scheduler | AWS IAM secret |
| `AWS_DEFAULT_REGION` | Bizapi, Scheduler | AWS region (e.g., ap-south-1) |
| `AWS_BUCKET` | Bizapi, Scheduler | Default S3 bucket |
| `AWS_DYNAMODB_ACCESS_KEY_ID` | Bizapi, Scheduler | DynamoDB access key |
| `AWS_DYNAMODB_SECRET_ACCESS_KEY` | Bizapi, Scheduler | DynamoDB secret |
| `QUGEO_S3_UPLOAD_ACCESS` | Bizapi | S3 upload access key |
| `QUGEO_S3_UPLOAD_SECRET` | Bizapi | S3 upload secret |
| `SES_SMTP_USERNAME` | Bizadmin, Manage-Products | Amazon SES SMTP username |
| `SES_SMTP_PASSWORD` | Bizadmin, Manage-Products | Amazon SES SMTP password |

#### Database & Cache
| Variable | Service(s) | Description |
|----------|-----------|-------------|
| `DB_HOST` | Bizapi, Scheduler | MySQL host |
| `DB_DATABASE` | Bizapi, Scheduler | MySQL database name |
| `DB_USERNAME` | Bizapi, Scheduler | MySQL username |
| `DB_PASSWORD` | Bizapi, Scheduler | MySQL password |
| `REDIS_HOST` | Bizapi, Scheduler | Redis host |
| `REDIS_PASSWORD` | Bizapi, Scheduler | Redis password |

#### Security
| Variable | Service(s) | Description |
|----------|-----------|-------------|
| `JWT_SECRET` | Bizapi, Scheduler | JWT signing secret |
| `APP_KEY` | Bizapi, Scheduler | Laravel encryption key |
| `ENCRYPTION_DEFAULT_KEY` | finascop | AES encryption key |

---

## 6. Deployment Steps

### First-Time Setup
1. Provision infrastructure (databases, Redis, S3 buckets)
2. Create all required third-party accounts (see Section 3)
3. Configure environment variables for each service
4. Run database migrations
5. Run super admin seeder (`setup/super-admin-seed.sql`)
6. Deploy each service to its platform
7. Verify all services are running
8. Login with super admin credentials and change password

### CI/CD
- GitHub Actions workflows exist in `.github/workflows/` for Bizapi and Scheduler
- Partner deploys via Azure DevOps pipeline
- finascop deploys via Azure Functions deployment

---

## 7. Post-Deployment Checklist

- [ ] All `.env` files populated with production values (never commit these)
- [ ] Super admin password changed from default
- [ ] SSL certificates configured for all domains
- [ ] `APP_DEBUG=false` in all Laravel services
- [ ] `debug="false"` in Partner Web.config compilation
- [ ] Redis password set and connections secured
- [ ] Database passwords rotated (old ones were in source control)
- [ ] AWS IAM keys rotated and scoped to minimum permissions
- [ ] All payment gateway keys are production keys (not test/sandbox)
- [ ] SMS provider sender IDs approved for production
- [ ] Firewall rules restrict database access to application servers only
- [ ] Scheduler cron job running: `* * * * * php artisan schedule:run`
- [ ] Log rotation configured for all services
- [ ] Backup schedule configured for MySQL and Azure SQL
- [ ] Error monitoring set up (Sentry, Azure Application Insights, etc.)
- [ ] Rate limiting enabled on API endpoints

### S3 Buckets to Create
| Bucket Name | Purpose | Region |
|-------------|---------|--------|
| `{prefix}-products` | Product images | ap-south-1 |
| `{prefix}-uploads` | User uploads | ap-south-1 |
| `{prefix}-finance-settlement` | Finance documents | ap-southeast-1 |
| `{prefix}-tenantapp-frontend` | Tenant app assets | ap-south-1 |
| `{prefix}-splashscreen` | App splash screens | ap-south-1 |

### DynamoDB Tables to Create
| Table Name | Purpose | Partition Key |
|------------|---------|---------------|
| `{prefix}_activelog` | Activity logging | `id` (String) |
| `{prefix}_email_queue` | Email queue | `id` (String) |

---

## Credential Rotation Schedule

Since all credentials were previously hardcoded in source control, **ALL credentials must be rotated immediately** after deployment:

1. **Immediately rotate:** AWS keys, database passwords, Redis password, payment gateway keys
2. **Within 24 hours:** Google API keys, SMS API keys, SendGrid key
3. **Within 1 week:** Social login secrets (Google, Facebook), analytics tokens
4. **Revoke old keys** after confirming new ones work

---

*Generated: 2026-05-15*
*Platform: Grozeo v1.0*
