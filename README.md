# Grozeo Platform

A comprehensive multi-service e-commerce and retail management platform.

## Architecture Overview

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                           GROZEO PLATFORM                                    │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐    │
│  │   Bizapi     │  │  Scheduler   │  │   Public     │  │   Partner    │    │
│  │  (Laravel)   │  │  (Laravel)   │  │  (.NET 8)    │  │  (.NET 8)    │    │
│  │  Port: 8001  │  │  Port: 8002  │  │  Port: 5000  │  │  Port: 5001  │    │
│  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘    │
│                                                                              │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐                       │
│  │  Bizadmin    │  │Manage-Products│ │   Finascop   │                       │
│  │    (PHP)     │  │    (PHP)     │  │  (.NET 8)    │                       │
│  │  Port: 8003  │  │  Port: 8004  │  │  Port: 8005  │                       │
│  └──────────────┘  └──────────────┘  └──────────────┘                       │
│                                                                              │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐                       │
│  │   MySQL 8    │  │   Redis 7    │  │    Nginx     │                       │
│  │  Port: 3306  │  │  Port: 6379  │  │  Port: 80/443│                       │
│  └──────────────┘  └──────────────┘  └──────────────┘                       │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

## Services

| Service | Technology | Purpose | Port |
|---------|------------|---------|------|
| **Grozeo-Bizapi** | PHP 8.2 / Laravel 11 | Customer-facing REST API | 8001 |
| **Grozeo-Scheduler** | PHP 8.1 / Laravel 11 | Background jobs, queues, cron | 8002 |
| **Grozeo-Public** | ASP.NET 8.0 | Public storefront website | 5000 |
| **Grozeo-Partner** | ASP.NET 8.0 | Partner/Agent portal | 5001 |
| **Grozeo-Bizadmin** | PHP 8.2 / Apache | Admin panel | 8003 |
| **Manage-Products** | PHP 8.2 / Apache | Product management | 8004 |
| **Finascop** | ASP.NET 8.0 | Finance/Accounting module | 8005 |

## Quick Start (Local Development)

### Prerequisites
- Docker & Docker Compose
- Git

### 1. Clone the Repository
```bash
git clone https://github.com/YOUR_ORG/Grozeo-Latest.git
cd Grozeo-Latest
```

### 2. Configure Environment
```bash
# Copy environment files
cp Grozeo-Bizapi/.env.example Grozeo-Bizapi/.env
cp Grozeo-Scheduler/.env.example Grozeo-Scheduler/.env

# Edit with your values
nano Grozeo-Bizapi/.env
```

### 3. Start Services
```bash
docker compose up -d --build
```

### 4. Run Migrations
```bash
docker compose exec bizapi php artisan migrate --force
docker compose exec bizapi php artisan db:seed
```

### 5. Access Applications
- **API**: http://localhost:8001
- **Public Site**: http://localhost:5000
- **Partner Portal**: http://localhost:5001
- **Admin Panel**: http://localhost:8003
- **Products Manager**: http://localhost:8004
- **Finascop**: http://localhost:8005

## Deployment Options

### Option 1: Railway (Recommended for most cases)

Railway provides easy deployment with automatic scaling and HTTPS.

**Estimated Cost**: ~$40-75/month

1. Install Railway CLI: `npm install -g @railway/cli`
2. Login: `railway login`
3. Deploy each service from its directory

See [setup/RAILWAY-DIGITALOCEAN-DEPLOYMENT.md](setup/RAILWAY-DIGITALOCEAN-DEPLOYMENT.md) for detailed instructions.

### Option 2: DigitalOcean App Platform (Managed)

Fully managed platform with auto-scaling.

**Estimated Cost**: ~$50-100/month

```bash
doctl apps create --spec setup/digitalocean-app-spec.yaml
```

### Option 3: DigitalOcean Droplet (Self-Managed)

Single VPS running all services via Docker Compose.

**Estimated Cost**: ~$24-54/month

```bash
ssh root@YOUR_DROPLET_IP
bash <(curl -sL https://raw.githubusercontent.com/YOUR_ORG/Grozeo-Latest/main/setup/digitalocean-droplet-setup.sh)
```

### Option 4: Hybrid (Production Recommended)

Best of both worlds:
- **Railway**: Modern services (Bizapi, Public, Partner)
- **DigitalOcean Managed DB**: MySQL & Redis
- **DigitalOcean Droplet**: Legacy PHP apps (Bizadmin, Manage-Products)

**Estimated Cost**: ~$67-77/month

## Platform Comparison

| Feature | Railway | DO App Platform | DO Droplet |
|---------|---------|-----------------|------------|
| Setup Complexity | Low | Medium | High |
| Auto-scaling | Yes | Yes | Manual |
| SSL/HTTPS | Automatic | Automatic | Manual (Certbot) |
| Custom Domains | Easy | Easy | Manual |
| Database Backups | Basic | Managed | Manual |
| Cost Control | Pay-per-use | Predictable | Most Predictable |
| Ideal For | Startups, MVPs | Growth stage | Budget-conscious |

## CI/CD Pipeline

GitHub Actions workflows are configured for:

- **`bizapi-ci.yml`**: PHP linting, testing, security audit
- **`scheduler-ci.yml`**: Scheduler build verification
- **`dotnet-ci.yml`**: .NET services build
- **`deploy.yml`**: Manual deployment trigger

### Triggering a Deploy
```bash
# Via GitHub UI
# Go to Actions > Deploy > Run workflow

# Or via GitHub CLI
gh workflow run deploy.yml -f environment=staging -f service=all
```

## Environment Variables

Required secrets for deployment:

| Variable | Service | Description |
|----------|---------|-------------|
| `APP_KEY` | Bizapi, Scheduler | Laravel app key |
| `JWT_SECRET` | Bizapi | JWT authentication secret |
| `DB_*` | All | MySQL connection details |
| `REDIS_*` | Bizapi, Scheduler | Redis connection details |
| `AWS_*` | Bizapi | S3, SES, DynamoDB keys |
| `RAZORPAY_*` | Bizapi | Payment gateway |
| `STRIPE_*` | Bizapi | Payment gateway |
| `FCM_SERVER_KEY` | Scheduler | Firebase notifications |

See `.env.example` files in each Laravel service for complete lists.

## Domain Configuration

| Domain | Service |
|--------|---------|
| `bizapi.grozeo.in` | Grozeo-Bizapi |
| `*.grozeo.in` | Grozeo-Public (wildcard for multi-tenant) |
| `partner.grozeo.in` | Grozeo-Partner |
| `admin.grozeo.in` | Grozeo-Bizadmin |
| `products.grozeo.in` | Manage-Products |
| `finascop.grozeo.in` | Finascop |

## Health Checks

All services expose health endpoints:

| Service | Health Endpoint |
|---------|-----------------|
| Bizapi | `/health/ping` |
| Scheduler | `/health/ping` |
| Public | `/Health/ping` |
| Partner | `/health` |

## Directory Structure

```
Grozeo-Latest/
├── Grozeo-Bizapi/          # Main API (Laravel)
├── Grozeo-Scheduler/       # Background workers (Laravel)
├── Grozeo-Public/          # Storefront (.NET)
├── Grozeo-Partner/         # Partner portal (.NET)
├── Grozeo-Bizadmin/        # Admin panel (PHP)
├── Manage-Products/        # Product management (PHP)
├── finascop/               # Finance module (.NET)
├── setup/                  # Deployment configs
│   ├── nginx-api-gateway.conf
│   ├── digitalocean-app-spec.yaml
│   ├── digitalocean-droplet-setup.sh
│   └── super-admin-seed.sql
├── shared/                 # Shared resources
├── docker-compose.yml      # Local development
└── .github/workflows/      # CI/CD pipelines
```

## Security Considerations

- All traffic goes through HTTPS (TLS 1.2+)
- Rate limiting configured in Nginx
- JWT authentication for API endpoints
- Environment secrets managed via platform (Railway/DO)
- Regular dependency security audits via `composer audit`

## Monitoring & Logs

- **Railway**: Built-in logging in dashboard
- **DigitalOcean**: Managed logs or `docker compose logs -f`
- **Laravel Logs**: `/storage/logs/laravel.log`

## Support

For deployment issues, check:
1. [setup/DEPLOYMENT-SETUP-GUIDE.md](setup/DEPLOYMENT-SETUP-GUIDE.md)
2. [setup/RAILWAY-DIGITALOCEAN-DEPLOYMENT.md](setup/RAILWAY-DIGITALOCEAN-DEPLOYMENT.md)
3. Individual service READMEs in each folder
