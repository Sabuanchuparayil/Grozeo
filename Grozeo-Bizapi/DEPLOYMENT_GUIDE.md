# Grozeo Bizapi — Deployment Guide (Railway & DigitalOcean)

**Application:** Grozeo Bizapi (Laravel 11.52.0 / PHP 8.5)  
**Last Updated:** 2026-05-15  
**Branch:** upgrade/laravel-11

---

## Table of Contents

1. [Server Requirements](#1-server-requirements)
2. [Environment Configuration (.env)](#2-environment-configuration-env)
3. [Third-Party Service Accounts](#3-third-party-service-accounts)
4. [Deploy on Railway](#4-deploy-on-railway)
5. [Deploy on DigitalOcean](#5-deploy-on-digitalocean)
6. [Docker Reference](#6-docker-reference)
7. [Health Checks & Monitoring](#7-health-checks--monitoring)
8. [Post-Deployment Verification](#8-post-deployment-verification)
9. [Credential Summary Matrix](#9-credential-summary-matrix)

---

## 1. Server Requirements

| Requirement | Minimum | Recommended |
|-------------|---------|-------------|
| PHP | 8.2+ | 8.5 |
| MySQL | 5.7+ | 8.0+ |
| Redis | 6.0+ | 7.0+ |
| Elasticsearch | 7.x | 8.x |
| Composer | 2.x | 2.9+ |

### Required PHP Extensions

`pdo_mysql`, `redis`, `curl`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, `gd`, `intl`, `opcache`, `pcntl`, `zip`

---

## 2. Environment Configuration (.env)

Copy `.env.example` to `.env` and fill in all values. Below is every variable grouped by service.

### 2.1 Application Core

| Variable | Required | Description | Example |
|----------|----------|-------------|---------|
| `APP_NAME` | Yes | Application display name | `Grozeo API` |
| `APP_ENV` | Yes | Environment (production/staging/local) | `production` |
| `APP_KEY` | Yes | 32-char encryption key. Generate with `php artisan key:generate` | `base64:...` |
| `APP_DEBUG` | Yes | Must be `false` in production | `false` |
| `APP_URL` | Yes | Full URL of the API | `https://bizapi.grozeo.in` |
| `APP_TIMEZONE` | Yes | Server timezone | `Asia/Kolkata` |
| `LOG_CHANNEL` | Yes | Logging driver | `daily` |
| `LOG_LEVEL` | Yes | Minimum log level | `error` |
| `FRONTEND_URL` | Yes | Frontend app URL (used for CORS) | `https://grozeo.in` |
| `CORS_ALLOWED_ORIGINS` | Yes | Comma-separated allowed origins | `https://grozeo.in,https://admin.grozeo.in` |
| `OPERATING_COUNTRY` | Yes | Country code for localization | `IN` or `UK` |
| `DEFAULT_CURRENCY_SYMBOL` | No | Currency symbol | `₹` or `£` |
| `ROUND_OFF` | No | Enable price rounding | `false` |

### 2.2 Database (MySQL)

| Variable | Required | Description |
|----------|----------|-------------|
| `DB_CONNECTION` | Yes | `mysql` |
| `DB_HOST` | Yes | Database server hostname |
| `DB_PORT` | Yes | Default: `3306` |
| `DB_DATABASE` | Yes | Database name |
| `DB_USERNAME` | Yes | Database user |
| `DB_PASSWORD` | Yes | Database password |
| `DB_READ_HOST` | No | Read replica host (for read/write splitting) |
| `DB_PERSISTENT` | No | Use persistent connections (default: `false`) |

### 2.3 Redis

| Variable | Required | Description |
|----------|----------|-------------|
| `REDIS_HOST` | Yes | Redis server hostname |
| `REDIS_PASSWORD` | If secured | Redis auth password |
| `REDIS_PORT` | Yes | Default: `6379` |

Redis is used for: **Session**, **Cache**, **Queue**, and **Rate Limiting**.

### 2.4 Session & Cache

| Variable | Required | Description |
|----------|----------|-------------|
| `CACHE_DRIVER` | Yes | `redis` |
| `QUEUE_CONNECTION` | Yes | `redis` |
| `SESSION_DRIVER` | Yes | `redis` |
| `SESSION_LIFETIME` | No | Minutes (default: 120) |
| `SESSION_SECURE_COOKIE` | Yes | `true` in production |
| `SESSION_HTTP_ONLY` | Yes | `true` |
| `BROADCAST_DRIVER` | No | `log` |

### 2.5 JWT Authentication

| Variable | Required | Description |
|----------|----------|-------------|
| `JWT_SECRET` | Yes | Secret key for JWT signing. Generate with `php artisan jwt:secret` |
| `JWT_TTL` | No | Token lifetime in minutes (default: 60) |
| `JWT_REFRESH_TTL` | No | Refresh window in minutes (default: 20160 = 14 days) |

---

## 3. Third-Party Service Accounts

### 3.1 AWS — Amazon Web Services

**Services Used:** S3 (file storage), SES (email), DynamoDB (order tracking)

#### Primary S3 Bucket (Product Images, Assets)

| Variable | Required | Description |
|----------|----------|-------------|
| `AWS_ACCESS_KEY_ID` | Yes | IAM access key |
| `AWS_SECRET_ACCESS_KEY` | Yes | IAM secret key |
| `AWS_DEFAULT_REGION` | Yes | e.g., `ap-south-1` |
| `AWS_BUCKET` | Yes | Primary S3 bucket name |

**IAM Policy Required:** `s3:GetObject`, `s3:PutObject`, `s3:DeleteObject` on the bucket.

#### Secondary S3 Buckets (Asset Upload & Delivery)

| Variable | Required | Description |
|----------|----------|-------------|
| `AWS_S3_ACCESS_KEY_ID` | Yes | Separate IAM key for asset uploads |
| `AWS_S3_SECRET_ACCESS_KEY` | Yes | Corresponding secret |
| `AWS_S3_ASSET_BUCKET` | Yes | Asset upload bucket |
| `AWS_S3_ASSET_REGION` | No | Default: `ap-southeast-1` |
| `QUGEO_S3_UPLOAD_ACCESS` | Yes | Qugeo delivery upload access key |
| `QUGEO_S3_UPLOAD_SECRET` | Yes | Qugeo delivery upload secret |
| `QUGEO_S3_UPLOAD_BUCKET` | Yes | Qugeo upload bucket |
| `QUGEO_S3_UPLOAD_REGION` | No | Default: `ap-southeast-1` |
| `QUGEO_DELIVERY_ASSET_BUCKET` | Yes | Delivery assets bucket |

#### DynamoDB (Order/Driver Tracking)

| Variable | Required | Description |
|----------|----------|-------------|
| `AWS_DYNAMODB_ACCESS_KEY_ID` | Yes | DynamoDB IAM key |
| `AWS_DYNAMODB_SECRET_ACCESS_KEY` | Yes | DynamoDB IAM secret |
| `AWS_DYNAMODB_REGION` | No | Default: `ap-south-1` |
| `AWSDYNAMODBTABLEPREFIX` | Yes | Table name prefix (e.g., `grozeolive_`) |

#### SES (Email)

| Variable | Required | Description |
|----------|----------|-------------|
| `MAIL_MAILER` | Yes | `smtp` |
| `MAIL_HOST` | Yes | `email-smtp.<region>.amazonaws.com` |
| `MAIL_PORT` | Yes | `587` |
| `MAIL_USERNAME` | Yes | SES SMTP username |
| `MAIL_PASSWORD` | Yes | SES SMTP password |
| `MAIL_ENCRYPTION` | Yes | `tls` |
| `MAIL_FROM_ADDRESS` | Yes | Verified sender email |
| `MAIL_FROM_NAME` | Yes | Sender display name |

**Setup:** Verify sender domain/email in AWS SES console. Request production access (move out of sandbox).

---

### 3.2 Payment Gateways

The application supports **9 payment gateways**. Configure only the ones you will use. Set the default in `config/paymentgateway.php`.

#### Razorpay (India — Default)

| Variable | Required | Description |
|----------|----------|-------------|
| `RP_API_KEY_ID` | Yes | Razorpay Key ID |
| `RP_API_KEY` | Yes | Razorpay Key Secret |
| `RP_CNY` | No | Currency (default: `INR`) |

**Dashboard:** https://dashboard.razorpay.com  
**Webhook:** Configure webhook URL: `https://<api-domain>/payment/result/webhook/razorpay`

#### Stripe (International)

Stripe credentials are stored per-company in the database (`CompanyStripe` model), not in `.env`. Each store group configures its own Stripe keys via the backoffice.

| Variable | Required | Description |
|----------|----------|-------------|
| `STRIPE_KEY` | Per store | Stripe publishable key (stored in DB) |
| `STRIPE_SECRET` | Per store | Stripe secret key (stored in DB) |

**Dashboard:** https://dashboard.stripe.com  
**Webhook:** `https://<api-domain>/payment/result/webhook/stripe`

#### Paytm

| Variable | Required | Description |
|----------|----------|-------------|
| `PAYTM_MERCHANT_KEY` | Yes | Merchant key |
| `PAYTM_MERCHANT_ID` | Yes | Merchant MID |
| `PAYTM_CALLBACK_URL` | Yes | Callback URL |

#### Instamojo

| Variable | Required | Description |
|----------|----------|-------------|
| `IM_API_KEY` | Yes | API key |
| `IM_AUTH_TOKEN` | Yes | Auth token |
| `IM_URL` | Yes | API base URL (`https://api.instamojo.com/api/1.1/` for production) |

#### CCAvenue

| Variable | Required | Description |
|----------|----------|-------------|
| `CCAVENUE_MERCHANT_ID` | Yes | Merchant ID |
| `CCAVENUE_ACCESS_CODE` | Yes | Access code |
| `CCAVENUE_WORKING_KEY` | Yes | Encryption working key |

#### Easebuzz

| Variable | Required | Description |
|----------|----------|-------------|
| `EASEBUZZ_KEY` | Yes | Merchant key |
| `EASEBUZZ_SALT` | Yes | Salt for checksum |

#### Easypay (Axis Bank)

| Variable | Required | Description |
|----------|----------|-------------|
| `EASYPAY_CID` | Yes | Customer ID |
| `EASYPAY_TYP` | Yes | `LIVE` or `TEST` |
| `EASYPAY_VER` | No | API version (default: `1.0`) |
| `EASYPAY_CNY` | No | Currency (default: `INR`) |
| `EASYPAY_RE1` | Yes | Reference field |
| `EASYPAY_PAYMENT_URL` | Yes | Payment API endpoint |
| `EASYPAY_TOKEN_URL` | Yes | Token generation endpoint |
| `EASYPAY_ENQUIRY_URL` | Yes | Enquiry API endpoint |
| `EASYPAY_CHECKSUM_KEY` | Yes | Checksum verification key |
| `EASYPAY_ENCRYPTION_KEY` | Yes | AES encryption key |

#### Atom Payment Gateway

| Variable | Required | Description |
|----------|----------|-------------|
| `ATOM_LOGIN` | Yes | Login ID |
| `ATOM_PASS` | Yes | Password |
| `ATOM_TTYPE` | Yes | Transaction type |
| `ATOM_TXNCURR` | No | Currency (default: `INR`) |
| `ATOM_CLIENTCODE` | Yes | Client code |
| `ATOM_CUSTACC` | Yes | Customer account |
| `ATOM_REQHASHKEY` | Yes | Request hash key |
| `ATOM_RESPHASHKEY` | Yes | Response hash key |
| `ATOM_AESREQHASHKEY` | Yes | AES request hash key |
| `ATOM_AESREQHASHKEYSALT` | Yes | AES request salt |
| `ATOM_AESRESPHASHKEY` | Yes | AES response hash key |
| `ATOM_AESRESPHASHKEYSALT` | Yes | AES response salt |
| `ATOM_PAYMENT_URL` | Yes | Payment endpoint |
| `ATOM_TOKEN_URL` | Yes | Token endpoint |
| `ATOM_ENQUIRY_URL` | Yes | Enquiry endpoint |
| `ATOM_CHECKSUM_KEY` | Yes | Checksum key |
| `ATOM_ENCRYPTION_KEY` | Yes | Encryption key |

#### Revolut

| Variable | Required | Description |
|----------|----------|-------------|
| `REVOLUT_API_KEY` | Yes | API key from Revolut Business |

---

### 3.3 SMS Providers

The application supports multiple SMS providers. Set the active one in `config/sms.php` → `default`.

#### TextLocal (India)

| Variable | Required | Description |
|----------|----------|-------------|
| `TEXTLOCAL_API_KEY` | Yes | TextLocal API key |
| `TEXT_LOCAL_SMS_USERNAME` | Yes | Username |
| `TEXT_LOCAL_SMS_PASSWORD` | Yes | Password |

#### Kaleyra

| Variable | Required | Description |
|----------|----------|-------------|
| `KSMS_API_KEY` | Yes | Kaleyra API key |
| `KSMS_SENDER_ID` | Yes | Sender ID |
| `KSMS_SENDER_NUM` | No | Sender number |

#### Twilio

| Variable | Required | Description |
|----------|----------|-------------|
| `TWILIO_SID` | Yes | Account SID |
| `TWILIO_AUTH_TOKEN` | Yes | Auth token |
| `TWILIO_SERVICE_SID` | Yes | Messaging service SID |

#### BulkSMS India

| Variable | Required | Description |
|----------|----------|-------------|
| `BULKSMS_USERNAME` | Yes | BulkSMS username |
| `BULKSMS_API_KEY` | Yes | BulkSMS API key |

---

### 3.4 Google Services

| Variable | Required | Description |
|----------|----------|-------------|
| `GOOGLE_API_KEY` | No | General Google API key |
| `GMAP_DIST_API_KEY` | Yes | Google Maps Distance Matrix API key |
| `IPINFO_TOKEN` | No | IPInfo.io token for geolocation |

### 3.5 Firebase (Push Notifications)

| Variable | Required | Description |
|----------|----------|-------------|
| `FCM_SERVER_KEY` | Yes | Firebase Cloud Messaging server key |
| `FCM_SENDER_ID` | No | FCM sender ID |

Place the Firebase service account JSON file at: `storage/app/firebase_credentials.json`

### 3.6 Courier/Shipping Partners

#### Shipyaari

| Variable | Required | Description |
|----------|----------|-------------|
| `SHIPYAARI_CREATOR` | Yes | Creator ID |
| `SHIPYAARI_AVNKEY` | Yes | AVN key |
| `SHIPYAARI_USERNAME` | Yes | Username |

#### WorldOptions (UK)

| Variable | Required | Description |
|----------|----------|-------------|
| `WORLDOPTIONS_KEY` | Yes | API key |
| `WORLDOPTIONS_METERNUMBER` | Yes | Meter number |
| `WORLDOPTIONS_PASSWORD` | Yes | Password |

### 3.7 Elasticsearch

| Variable | Required | Description |
|----------|----------|-------------|
| `ELASTICSEARCH_HOST` | Yes | ES server hostname |
| `ELASTICSEARCH_PORT` | No | Default: `9200` |
| `ELASTICSEARCH_SCHEME` | No | `http` or `https` |
| `ELASTICSEARCH_USER` | If secured | ES username |
| `ELASTICSEARCH_PASSWORD` | If secured | ES password |

### 3.8 Qugeo Tracking (Driver/Delivery)

| Variable | Required | Description |
|----------|----------|-------------|
| `QUGEO_TRACKING_API_GATEWAY` | Yes | Qugeo tracking API base URL |

### 3.9 Site Info (Client Branding)

| Variable | Required | Description |
|----------|----------|-------------|
| `PROJECT_NAME` | No | Project name (default: `Grozeo`) |
| `APP_CLIENT_PROJECT_NAME` | No | Client project code |
| `APP_LOGO` | No | Logo URL |
| `APP_CLIENT_REGISTERED_EMAIL` | No | Business email |
| `APP_CLIENT_REGISTERED_PHONE` | No | Business phone |
| `APP_CLIENT_REGISTERED_NAME` | No | Registered business name |

---

## 4. Deploy on Railway

Railway uses Docker (Dockerfile) or Nixpacks to build and deploy. This project includes both — the Dockerfile is recommended for production as it bundles nginx + php-fpm + supervisor.

### 4.1 Prerequisites

- Railway account (https://railway.app)
- Railway CLI installed: `npm i -g @railway/cli`
- GitHub repo connected (or deploy from CLI)

### 4.2 Project Setup

```bash
# Login to Railway
railway login

# Create a new project
railway init

# Link to existing project (if already created in dashboard)
railway link
```

### 4.3 Add Services (MySQL + Redis)

From the Railway dashboard:

1. **Add MySQL**
   - Click "New" → "Database" → "MySQL"
   - Railway auto-provisions and sets `DATABASE_URL`
   - Note the individual connection variables: `MYSQLHOST`, `MYSQLPORT`, `MYSQLDATABASE`, `MYSQLUSER`, `MYSQLPASSWORD`

2. **Add Redis**
   - Click "New" → "Database" → "Redis"
   - Railway auto-provisions and sets `REDIS_URL`
   - Note: `REDISHOST`, `REDISPORT`, `REDISPASSWORD`

### 4.4 Configure Environment Variables

In the Railway dashboard → your service → "Variables" tab, add all env vars from Section 2 and 3. Map Railway's auto-generated database variables:

```
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
```

Generate keys locally and paste them in:

```bash
# Generate APP_KEY (run locally, copy the output)
php artisan key:generate --show

# Generate JWT_SECRET (run locally, copy the output)
php artisan jwt:secret --show
```

Set production defaults:

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://<your-railway-domain>.up.railway.app
LOG_CHANNEL=stderr
LOG_LEVEL=error
```

> **Important:** Use `LOG_CHANNEL=stderr` on Railway so logs appear in the Railway dashboard log viewer.

### 4.5 Deploy

**Option A: GitHub auto-deploy (recommended)**

1. In Railway dashboard → your service → "Settings" → "Source"
2. Connect your GitHub repo
3. Set the branch to `upgrade/laravel-11` (or `main`)
4. Enable auto-deploy — every push triggers a new deployment

**Option B: CLI deploy**

```bash
railway up
```

### 4.6 Run Post-Deploy Commands

After the first deployment, run migrations via Railway CLI:

```bash
# Open a shell in the running container
railway shell

# Inside the container:
php artisan migrate --force
php artisan config:cache
php artisan route:cache
```

Or use Railway's deploy command override for first run:

```bash
railway run php artisan migrate --force
```

### 4.7 Custom Domain

1. Railway dashboard → service → "Settings" → "Networking" → "Custom Domain"
2. Add your domain: `bizapi.yourdomain.com`
3. Add the CNAME record Railway provides to your DNS
4. SSL is automatic via Railway

### 4.8 Railway Architecture

The Dockerfile runs supervisor which manages:

| Process | Description |
|---------|-------------|
| `php-fpm` | PHP FastCGI process manager |
| `nginx` | Web server (port 80) |
| `queue-worker` (x2) | Laravel queue workers for background jobs |

Railway routes traffic to the container's exposed port (80). The health check hits `/health/ping`.

### 4.9 Scaling on Railway

- **Horizontal:** Add replicas in "Settings" → "Scaling" (Pro plan)
- **Vertical:** Railway auto-scales memory/CPU, or set limits manually
- **Queue workers:** Already bundled in supervisor (2 workers). For heavy queue load, deploy a second service using the same image with a different start command:

```bash
# In a separate Railway service, override the start command:
php artisan queue:work redis --sleep=3 --tries=3 --max-time=3600 --max-jobs=1000
```

### 4.10 Cron / Scheduler on Railway

Railway supports cron jobs natively:

1. Railway dashboard → "New" → "Cron Job"
2. Set schedule: `* * * * *`
3. Set command: `php artisan schedule:run`
4. Use the same Docker image and env vars

Alternatively, add the scheduler to supervisor. Create `docker/supervisord-scheduler.conf` or add this block to the existing `supervisord.conf`:

```ini
[program:scheduler]
command=/bin/sh -c "while true; do php /var/www/html/artisan schedule:run --no-interaction >> /dev/null 2>&1; sleep 60; done"
autostart=true
autorestart=true
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0
```

### 4.11 Railway Cost Estimate

| Component | Approx. Monthly Cost |
|-----------|---------------------|
| App service (1 GB RAM, shared CPU) | $5–10 |
| MySQL (1 GB) | $5–7 |
| Redis (256 MB) | $3–5 |
| **Total starter** | **~$15–25/month** |

---

## 5. Deploy on DigitalOcean

Two approaches: **App Platform** (managed PaaS, simpler) or **Droplet** (full VM, more control).

### 5A. DigitalOcean App Platform

#### 5A.1 Prerequisites

- DigitalOcean account (https://cloud.digitalocean.com)
- GitHub repo connected to DigitalOcean
- `doctl` CLI installed (optional): `brew install doctl`

#### 5A.2 Create App

1. DigitalOcean dashboard → "Apps" → "Create App"
2. Select source: GitHub → your repo → branch `upgrade/laravel-11`
3. DigitalOcean detects the Dockerfile automatically
4. Configure the app:
   - **Name:** `grozeo-bizapi`
   - **Region:** Bangalore (BLR1) or closest to your users
   - **Instance Size:** Basic ($12/mo) or Pro ($25/mo)
   - **Port:** `80` (nginx in the container)

#### 5A.3 Add Managed Databases

1. **MySQL:**
   - DigitalOcean → "Databases" → "Create" → "MySQL 8"
   - Plan: Basic ($15/mo, 1 GB RAM, 10 GB storage)
   - Region: Same as your app
   - After creation, note the connection details

2. **Redis:**
   - DigitalOcean → "Databases" → "Create" → "Redis 7"
   - Plan: Basic ($15/mo, 1 GB RAM)
   - Region: Same as your app

3. **Elasticsearch:** DigitalOcean doesn't offer managed ES. Options:
   - Elastic Cloud (https://cloud.elastic.co)
   - Self-host on a separate Droplet
   - Use OpenSearch on AWS

#### 5A.4 Environment Variables

In App Platform → your app → "Settings" → "App-Level Environment Variables":

Set all variables from Sections 2 and 3. For database connections, use the managed database connection details:

```
DB_CONNECTION=mysql
DB_HOST=<mysql-host>.db.ondigitalocean.com
DB_PORT=25060
DB_DATABASE=defaultdb
DB_USERNAME=doadmin
DB_PASSWORD=<auto-generated>

REDIS_HOST=<redis-host>.db.ondigitalocean.com
REDIS_PORT=25061
REDIS_PASSWORD=<auto-generated>

APP_ENV=production
APP_DEBUG=false
LOG_CHANNEL=stderr
```

> **Note:** DigitalOcean managed databases use non-standard ports (25060 for MySQL, 25061 for Redis) and require SSL. Add `MYSQL_ATTR_SSL_CA=/etc/ssl/certs/ca-certificates.crt` if needed.

#### 5A.5 Deploy

App Platform auto-deploys on every push to the configured branch. For manual deploys:

```bash
doctl apps create-deployment <app-id>
```

#### 5A.6 Run Migrations

Use the App Platform console:

1. Dashboard → your app → "Console" tab
2. Run: `php artisan migrate --force`

Or via CLI:

```bash
doctl apps console <app-id> --command="php artisan migrate --force"
```

#### 5A.7 Custom Domain

1. App settings → "Domains" → "Add Domain"
2. Add `bizapi.yourdomain.com`
3. Point your DNS CNAME to the provided App Platform URL
4. SSL is automatic via Let's Encrypt

#### 5A.8 Cron / Scheduler

App Platform supports worker components:

1. In app settings, add a new component → "Worker"
2. Use the same Dockerfile
3. Set run command: `/bin/sh -c "while true; do php /var/www/html/artisan schedule:run; sleep 60; done"`

---

### 5B. DigitalOcean Droplet (Full VM)

For full control over the server.

#### 5B.1 Create Droplet

1. DigitalOcean → "Droplets" → "Create"
2. **Image:** Ubuntu 24.04 LTS
3. **Plan:** Basic ($12/mo, 2 GB RAM, 1 vCPU) — minimum for production
4. **Region:** Bangalore (BLR1) or closest
5. **Auth:** SSH key (recommended)

#### 5B.2 Server Setup

SSH into the droplet and install dependencies:

```bash
# Update system
apt update && apt upgrade -y

# Add PHP 8.5 repository
add-apt-repository ppa:ondrej/php -y
apt update

# Install PHP 8.5 + extensions
apt install -y php8.5-fpm php8.5-mysql php8.5-redis php8.5-curl \
  php8.5-mbstring php8.5-xml php8.5-bcmath php8.5-gd php8.5-zip \
  php8.5-intl php8.5-opcache php8.5-pcntl php8.5-tokenizer

# Install Nginx
apt install -y nginx

# Install Composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Redis
apt install -y redis-server
systemctl enable redis-server

# Install Supervisor (for queue workers)
apt install -y supervisor
```

#### 5B.3 Install MySQL (or use managed)

**Option A: Install locally on the Droplet**

```bash
apt install -y mysql-server
mysql_secure_installation
mysql -u root -p -e "CREATE DATABASE grozeo_bizapi; CREATE USER 'grozeo'@'localhost' IDENTIFIED BY '<strong-password>'; GRANT ALL ON grozeo_bizapi.* TO 'grozeo'@'localhost'; FLUSH PRIVILEGES;"
```

**Option B: Use DigitalOcean Managed MySQL** (recommended for production)

Create via dashboard, use the provided connection details in your `.env`.

#### 5B.4 Deploy Application

```bash
# Create web directory
mkdir -p /var/www/grozeo-bizapi
cd /var/www/grozeo-bizapi

# Clone repository
git clone <repo-url> .
git checkout upgrade/laravel-11

# Install dependencies
composer install --optimize-autoloader --no-dev

# Configure environment
cp .env.example .env
nano .env  # Fill in all required values

# Generate keys
php artisan key:generate
php artisan jwt:secret

# Run migrations
php artisan migrate --force

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan event:cache
php artisan view:cache

# Set permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

#### 5B.5 Nginx Configuration

```bash
nano /etc/nginx/sites-available/grozeo-bizapi
```

```nginx
server {
    listen 80;
    server_name bizapi.yourdomain.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name bizapi.yourdomain.com;
    root /var/www/grozeo-bizapi/public;
    index index.php;

    ssl_certificate /etc/letsencrypt/live/bizapi.yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/bizapi.yourdomain.com/privkey.pem;

    client_max_body_size 50m;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.5-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_read_timeout 300;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    location = /health/ping {
        access_log off;
        fastcgi_pass unix:/var/run/php/php8.5-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root/index.php;
        include fastcgi_params;
    }

    location ~* \.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
    }
}
```

```bash
ln -s /etc/nginx/sites-available/grozeo-bizapi /etc/nginx/sites-enabled/
rm /etc/nginx/sites-enabled/default
nginx -t && systemctl reload nginx
```

#### 5B.6 SSL Certificate (Let's Encrypt)

```bash
apt install -y certbot python3-certbot-nginx
certbot --nginx -d bizapi.yourdomain.com
```

Auto-renewal is configured automatically by certbot.

#### 5B.7 Supervisor (Queue Workers)

```bash
nano /etc/supervisor/conf.d/grozeo-worker.conf
```

```ini
[program:grozeo-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/grozeo-bizapi/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/grozeo-bizapi/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
supervisorctl reread
supervisorctl update
supervisorctl start grozeo-worker:*
```

#### 5B.8 Cron (Task Scheduler)

```bash
crontab -e
```

Add:

```cron
* * * * * cd /var/www/grozeo-bizapi && php artisan schedule:run >> /dev/null 2>&1
```

#### 5B.9 PHP-FPM Tuning

```bash
nano /etc/php/8.5/fpm/pool.d/www.conf
```

Key settings for a 2 GB Droplet:

```ini
pm = dynamic
pm.max_children = 20
pm.start_servers = 5
pm.min_spare_servers = 3
pm.max_spare_servers = 10
pm.max_requests = 1000
```

```bash
systemctl restart php8.5-fpm
```

#### 5B.10 Subsequent Deployments (Droplet)

Create a deploy script at `/var/www/grozeo-bizapi/deploy.sh`:

```bash
#!/bin/bash
set -e

cd /var/www/grozeo-bizapi

git pull origin upgrade/laravel-11
composer install --optimize-autoloader --no-dev
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan event:cache
php artisan view:cache
php artisan queue:restart

echo "Deployed successfully at $(date)"
```

```bash
chmod +x deploy.sh
./deploy.sh
```

#### 5B.11 DigitalOcean Droplet Cost Estimate

| Component | Approx. Monthly Cost |
|-----------|---------------------|
| Droplet (2 GB RAM, 1 vCPU) | $12 |
| Managed MySQL (Basic) | $15 |
| Managed Redis (Basic) | $15 |
| **Total** | **~$42/month** |

Using local MySQL + Redis on the Droplet: **$12/month** (but no managed backups).

---

## 6. Docker Reference

The project includes a production-ready Docker setup:

| File | Purpose |
|------|---------|
| `Dockerfile` | PHP 8.5 + nginx + supervisor (multi-process container) |
| `docker/nginx.conf` | Nginx site config for Laravel |
| `docker/php.ini` | Production PHP settings (256M memory, opcache enabled) |
| `docker/supervisord.conf` | Runs php-fpm, nginx, 2 queue workers |
| `nixpacks.toml` | Nixpacks build config (alternative to Dockerfile) |
| `railway.json` | Railway-specific deploy config |

### Build & Run Locally

```bash
docker build -t grozeo-bizapi .
docker run -p 8080:80 --env-file .env grozeo-bizapi
```

### Key Docker Settings

- **Memory limit:** 256M per PHP process (set in `docker/php.ini`)
- **Upload limit:** 50M (nginx + php.ini)
- **OPcache:** Enabled, 256M, timestamps disabled (production-optimized)
- **Queue workers:** 2 workers, 3 retries, 1-hour max runtime per worker
- **Health check:** `curl http://localhost/health/ping` every 30s

---

## 7. Health Checks & Monitoring

| Endpoint | Method | Expected |
|----------|--------|----------|
| `/health` | GET | 200 OK with system status (DB, Redis, disk) |
| `/health/ping` | GET | 200 OK (lightweight liveness check) |

Configure your platform's health check to hit `/health/ping` for liveness and `/health` for deeper readiness checks.

---

## 8. Post-Deployment Verification

- [ ] `php artisan about` shows correct Laravel 11.x version
- [ ] `php artisan config:cache` succeeds without errors
- [ ] `php artisan route:cache` succeeds without errors
- [ ] `/health` endpoint returns 200
- [ ] `/health/ping` endpoint returns 200
- [ ] JWT authentication works (login → get token → access protected route)
- [ ] Payment gateway webhook URLs are configured in each gateway's dashboard
- [ ] SMS delivery works (send test OTP)
- [ ] Email delivery works (trigger welcome email)
- [ ] S3 upload works (test product image upload)
- [ ] Elasticsearch queries return results
- [ ] Redis is accessible (cache and session working)
- [ ] Queue worker is processing jobs
- [ ] CORS headers are correct for frontend domain
- [ ] SSL certificate is valid and auto-renewing
- [ ] Log files are being written (Railway: check dashboard logs)
- [ ] `APP_DEBUG=false` in production

---

## 9. Credential Summary Matrix

| Service | Account Needed | Env Vars Count | Critical |
|---------|---------------|----------------|----------|
| MySQL | Database server | 5 | Yes |
| Redis | Redis server | 3 | Yes |
| AWS S3 | AWS account + IAM | 10 | Yes |
| AWS SES | AWS account + verified domain | 6 | Yes |
| AWS DynamoDB | AWS account + IAM | 4 | Yes |
| JWT | Self-generated | 1 | Yes |
| Razorpay | Razorpay merchant account | 3 | Per region |
| Stripe | Stripe account (per store) | DB-stored | Per region |
| Paytm | Paytm business account | 3 | Per region |
| Instamojo | Instamojo account | 3 | Per region |
| CCAvenue | CCAvenue merchant account | 3 | Per region |
| Easebuzz | Easebuzz account | 2 | Per region |
| Easypay | Axis Bank merchant account | 10 | Per region |
| Atom | Atom PG merchant account | 15 | Per region |
| Revolut | Revolut Business account | 1 | Per region |
| TextLocal | TextLocal account | 3 | Per SMS provider |
| Kaleyra | Kaleyra account | 3 | Per SMS provider |
| Twilio | Twilio account | 3 | Per SMS provider |
| BulkSMS | BulkSMS India account | 2 | Per SMS provider |
| Google Maps | Google Cloud project | 2 | Yes |
| Firebase | Firebase project | 1 + JSON file | Yes |
| Elasticsearch | ES cluster | 2-4 | Yes |
| Shipyaari | Shipyaari seller account | 3 | Per courier |
| WorldOptions | WorldOptions account | 3 | Per courier |
| Qugeo | Qugeo tracking account | 1 | Yes |

**Total unique credentials/secrets to configure: ~95 environment variables**
