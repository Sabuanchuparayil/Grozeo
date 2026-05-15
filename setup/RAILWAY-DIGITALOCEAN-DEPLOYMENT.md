# Grozeo Platform — Railway + DigitalOcean Deployment Guide

## Architecture Overview

```
┌─────────────────────────────────────────────────────────────────┐
│  RAILWAY (Application Services)                                  │
│  ┌──────────┐ ┌───────────┐ ┌──────────┐ ┌──────────────────┐  │
│  │ Bizapi   │ │ Scheduler │ │ Public   │ │ Partner (.NET 8) │  │
│  │ PHP 8.5  │ │ PHP 8.1   │ │ .NET 6   │ │ .NET 8           │  │
│  │ Port 80  │ │ Port 8080 │ │ Port 5000│ │ Port 5001        │  │
│  └──────────┘ └───────────┘ └──────────┘ └──────────────────┘  │
│  ┌──────────┐ ┌─────────────────┐ ┌──────────┐                 │
│  │ Bizadmin │ │ Manage-Products │ │ Finascop │                 │
│  │ PHP 8.2  │ │ PHP 8.2         │ │ .NET 3.1 │                 │
│  └──────────┘ └─────────────────┘ └──────────┘                 │
│  ┌──────────┐ ┌──────────┐                                      │
│  │ MySQL 8  │ │ Redis 7  │  (Railway Add-ons)                  │
│  └──────────┘ └──────────┘                                      │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│  DIGITALOCEAN (Alternative / Production)                         │
│  Option A: App Platform  — Managed, auto-scaling                 │
│  Option B: Droplet       — docker-compose, self-managed          │
└─────────────────────────────────────────────────────────────────┘
```

---

## PART 1: Railway Deployment

### Prerequisites
- Railway account (https://railway.app)
- GitHub repos for each service (or use Railway CLI for local deploys)
- Railway CLI installed: `npm install -g @railway/cli`

### Step 1: Create a Railway Project

```bash
railway login
railway init    # creates a new project
```

### Step 2: Provision Infrastructure Add-ons

In Railway Dashboard → your project → **+ New** → **Database**:

1. **MySQL 8.0** — creates `MYSQL_URL`, `MYSQLHOST`, `MYSQLPORT`, `MYSQLDATABASE`, `MYSQLUSER`, `MYSQLPASSWORD`
2. **Redis 7** — creates `REDIS_URL`, `REDISHOST`, `REDISPORT`, `REDISPASSWORD`

### Step 3: Deploy Each Service

Each service is a separate Railway **service** within the same project.
For each service below, in Railway Dashboard → **+ New** → **GitHub Repo** (or **Empty Service** → link repo).

#### 3a. Bizapi (Main API)

- **Source**: `Grozeo-Bizapi` repo
- **Builder**: Dockerfile (auto-detected from `railway.json`)
- **Env vars** (set in Railway Dashboard → Service → Variables):

```
APP_NAME=Grozeo API
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:GENERATE_WITH_php_artisan_key:generate
APP_URL=https://bizapi-production.up.railway.app
APP_TIMEZONE=Asia/Kolkata

DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}

REDIS_HOST=${{Redis.REDISHOST}}
REDIS_PORT=${{Redis.REDISPORT}}
REDIS_PASSWORD=${{Redis.REDISPASSWORD}}

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120

JWT_SECRET=YOUR_JWT_SECRET
PORT=80
```

Plus all payment gateway, AWS, SMS, and Firebase keys from `.env.example`.

- **Custom domain**: Add `bizapi.grozeo.in` → update DNS CNAME to Railway's provided domain
- **Health check**: `/health/ping`

#### 3b. Scheduler (Queue Worker)

- **Source**: `Grozeo-Scheduler` repo
- **Builder**: Nixpacks (from `railway.json`)
- **Important**: This runs as a **worker**, not a web service. In Railway, set it as a worker (no public domain needed).
- **Env vars**: Same DB/Redis references as Bizapi, plus scheduler-specific keys from `.env.example`

```
APP_NAME=Grozeo Scheduler
APP_ENV=production
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}
REDIS_HOST=${{Redis.REDISHOST}}
REDIS_PORT=${{Redis.REDISPORT}}
REDIS_PASSWORD=${{Redis.REDISPASSWORD}}
QUEUE_CONNECTION=redis
```

For the **cron scheduler** (runs `php artisan schedule:run` every minute):
- Create a **second service** from the same repo
- Set start command: `php artisan schedule:run`
- Set as a **Cron Job** with schedule: `* * * * *`

#### 3c. Public (Storefront)

- **Source**: `Grozeo-Public` repo
- **Builder**: Dockerfile
- **Env vars**:

```
ASPNETCORE_ENVIRONMENT=Production
ASPNETCORE_URLS=http://+:5000
PORT=5000
```

- **Custom domain**: Add `*.grozeo.in` (wildcard) or specific subdomains
- **Health check**: `/Health/ping`

#### 3d. Partner Portal

- **Source**: `Grozeo-Partner` repo
- **Builder**: Dockerfile (uses the new Dockerfile created)
- **Env vars**:

```
ASPNETCORE_ENVIRONMENT=Production
ASPNETCORE_URLS=http://+:5001
PORT=5001
```

- **Custom domain**: `partner.grozeo.in`

#### 3e. Bizadmin (Admin Panel)

- **Source**: `Grozeo-Bizadmin` repo
- **Builder**: Dockerfile (new Dockerfile created)
- **Env vars**: DB connection details (same MySQL reference), plus any config from the PHP includes
- **Custom domain**: `admin.grozeo.in`

#### 3f. Manage-Products

- **Source**: `Manage-Products` repo
- **Builder**: Dockerfile (new Dockerfile created)
- **Custom domain**: `products.grozeo.in`

#### 3g. Finascop

- **Source**: `finascop` repo
- **Builder**: Dockerfile (new Dockerfile created)
- **Note**: This is an Azure Functions v3 project (.NET 3.1). Running it outside Azure requires adjustments. Consider keeping this on Azure, or use the Dockerfile which runs it as a standalone app.

### Step 4: Run Database Migrations

```bash
# Connect to the Bizapi service
railway connect bizapi
railway run php artisan migrate --force
railway run php artisan db:seed  # if needed
```

Or import the seed SQL:
```bash
# Get MySQL connection string from Railway dashboard
mysql -h MYSQLHOST -P MYSQLPORT -u MYSQLUSER -pMYSQLPASSWORD MYSQLDATABASE < setup/super-admin-seed.sql
```

### Step 5: Configure Networking

Railway automatically provides HTTPS on `*.up.railway.app` domains.
For custom domains:
1. Railway Dashboard → Service → Settings → **Custom Domain**
2. Add your domain (e.g., `bizapi.grozeo.in`)
3. Railway provides a CNAME target
4. Update your DNS: `bizapi.grozeo.in CNAME → <railway-target>.up.railway.app`

---

## PART 2: DigitalOcean Deployment

### Option A: App Platform (Managed)

#### Prerequisites
- DigitalOcean account
- GitHub repos connected to DigitalOcean
- `doctl` CLI installed: `brew install doctl` / `snap install doctl`

#### Step 1: Authenticate

```bash
doctl auth init
```

#### Step 2: Create App from Spec

The app spec is at `setup/digitalocean-app-spec.yaml`.

1. Edit the spec file:
   - Replace `YOUR_ORG/Grozeo-Bizapi` etc. with your actual GitHub org/repo names
   - Update any environment variable values

2. Deploy:
```bash
doctl apps create --spec setup/digitalocean-app-spec.yaml
```

3. Set secret environment variables via the DO dashboard (API keys, passwords, etc.)

#### Step 3: Configure Databases

DO App Platform creates managed MySQL and Redis automatically from the spec.
- Connection details are auto-injected via `${db.HOSTNAME}` etc.
- Run migrations by opening the app console or using `doctl apps console`

#### Step 4: Custom Domains

```bash
doctl apps update YOUR_APP_ID --spec setup/digitalocean-app-spec.yaml
```

In DO Dashboard → App → Settings → Domains → Add domain for each service.

### Option B: Droplet (Self-Managed with docker-compose)

#### Step 1: Create a Droplet

- **Image**: Ubuntu 22.04
- **Size**: 4GB RAM / 2 vCPU minimum (8GB recommended for all 7 services)
- **Region**: BLR1 (Bangalore) or LON1 (London)
- **Enable**: Monitoring, backups

#### Step 2: Run Setup Script

```bash
ssh root@YOUR_DROPLET_IP
curl -sL https://raw.githubusercontent.com/YOUR_ORG/Grozeo-Latest/main/setup/digitalocean-droplet-setup.sh | bash
```

Or copy and run `setup/digitalocean-droplet-setup.sh` manually.

#### Step 3: Deploy with docker-compose

```bash
cd /opt/grozeo

# Clone all repos or copy the monorepo
git clone ... # your repos

# Copy the docker-compose.yml
cp docker-compose.yml /opt/grozeo/

# Create environment files
cp Grozeo-Bizapi/.env.example Grozeo-Bizapi/.env
cp Grozeo-Scheduler/.env.example Grozeo-Scheduler/.env
# Edit both .env files with real values

# Build and start
docker compose up -d --build

# Run migrations
docker compose exec bizapi php artisan migrate --force
docker compose exec bizapi php artisan db:seed
```

#### Step 4: Set Up SSL with Certbot

```bash
# Install nginx on the host (for SSL termination)
apt install -y nginx
cp setup/nginx-api-gateway.conf /etc/nginx/sites-available/grozeo
ln -s /etc/nginx/sites-available/grozeo /etc/nginx/sites-enabled/

# Get SSL certificates
certbot --nginx -d bizapi.grozeo.in -d partner.grozeo.in
certbot --nginx -d '*.grozeo.in'  # wildcard needs DNS challenge

# Reload
nginx -t && systemctl reload nginx
```

#### Step 5: DNS Configuration

Point your domains to the Droplet IP:

```
A    bizapi.grozeo.in     → DROPLET_IP
A    partner.grozeo.in    → DROPLET_IP
A    admin.grozeo.in      → DROPLET_IP
A    products.grozeo.in   → DROPLET_IP
A    *.grozeo.in           → DROPLET_IP
```

---

## PART 3: Recommended Split Strategy

For production, we recommend:

| Component | Platform | Why |
|-----------|----------|-----|
| **Bizapi** | Railway | Auto-deploy, easy scaling, built-in healthchecks |
| **Scheduler** | Railway | Worker process with cron, same project as Bizapi |
| **Public** | Railway | Auto-HTTPS, custom domains |
| **Partner** | Railway | .NET 8 runs great on Railway |
| **MySQL** | DigitalOcean Managed DB | Better for production: daily backups, failover, 99.99% SLA |
| **Redis** | DigitalOcean Managed Redis | Same — managed infra is more reliable |
| **Bizadmin** | DigitalOcean Droplet | Legacy PHP, less traffic, cheaper on a VPS |
| **Manage-Products** | DigitalOcean Droplet | Same as Bizadmin |
| **Finascop** | Azure Functions or DO Droplet | Azure Functions v3 is native; or containerize |

### Why split?

- **Railway** is great for app services: zero-config deploys, auto-HTTPS, scaling
- **DigitalOcean Managed DBs** provide backups, failover, and monitoring that Railway's add-ons don't match at production scale
- **Legacy PHP apps** (Bizadmin, Manage-Products) have lower traffic and simpler needs — a cheap Droplet is cost-effective
- **Finascop** was built for Azure Functions — keeping it there avoids compatibility issues

### Connecting Railway services to DO Managed MySQL

1. Create a DO Managed MySQL cluster
2. Get the connection string from DO Dashboard → Databases
3. In Railway, set the env vars pointing to the DO MySQL host:
   ```
   DB_HOST=your-do-mysql-host.db.ondigitalocean.com
   DB_PORT=25060
   DB_DATABASE=grozeo
   DB_USERNAME=grozeo
   DB_PASSWORD=your-password
   DB_SSL_MODE=REQUIRED
   ```
4. Whitelist Railway's outbound IPs in DO's trusted sources (or allow all — Railway IPs change)

---

## PART 4: Cost Estimates

### Railway (all 7 services)
| Resource | Monthly Est. |
|----------|-------------|
| Bizapi (512MB) | ~$5-10 |
| Scheduler (256MB) | ~$3-5 |
| Public (512MB) | ~$5-10 |
| Partner (512MB) | ~$5-10 |
| Bizadmin (256MB) | ~$3-5 |
| Manage-Products (256MB) | ~$3-5 |
| Finascop (256MB) | ~$3-5 |
| MySQL add-on | ~$7-15 |
| Redis add-on | ~$5-10 |
| **Total** | **~$40-75/mo** |

### DigitalOcean Droplet (all-in-one)
| Resource | Monthly |
|----------|---------|
| 4GB/2vCPU Droplet | $24 |
| Managed MySQL (optional) | $15 |
| Managed Redis (optional) | $15 |
| **Total** | **$24-54/mo** |

### Recommended Split
| Resource | Monthly |
|----------|---------|
| Railway: 4 modern services | ~$25-35 |
| DO Managed MySQL | $15 |
| DO Managed Redis | $15 |
| DO Droplet (legacy apps) | $12 |
| **Total** | **~$67-77/mo** |

---

## PART 5: Environment Variables Checklist

Before deploying, ensure you have values for all required secrets:

- [ ] `APP_KEY` — generate with `php artisan key:generate --show`
- [ ] `JWT_SECRET` — generate a random 64-char string
- [ ] MySQL credentials (auto-provided by Railway/DO managed DB)
- [ ] Redis credentials (auto-provided by Railway/DO managed DB)
- [ ] AWS keys (S3, SES, DynamoDB)
- [ ] Payment gateway keys (Razorpay, Stripe, Paytm, etc.)
- [ ] SMS provider keys (TextLocal, etc.)
- [ ] Google API keys (Maps, etc.)
- [ ] Firebase FCM server key
- [ ] Courier partner keys (Shipyaari, WorldOptions)

See `Grozeo-Bizapi/.env.example` and `Grozeo-Scheduler/.env.example` for the full list.
