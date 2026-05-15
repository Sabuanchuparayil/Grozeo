#!/bin/bash
set -euo pipefail

##############################################
# Grozeo Platform — DigitalOcean Droplet Setup
# Run on a fresh Ubuntu 22.04+ droplet
# Recommended: 4GB RAM / 2 vCPU minimum
##############################################

echo "=== [1/6] Installing Docker & Docker Compose ==="
apt-get update
apt-get install -y ca-certificates curl gnupg lsb-release
install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | gpg --dearmor -o /etc/apt/keyrings/docker.gpg
chmod a+r /etc/apt/keyrings/docker.gpg
echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" > /etc/apt/sources.list.d/docker.list
apt-get update
apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

echo "=== [2/6] Installing Certbot for SSL ==="
apt-get install -y certbot python3-certbot-nginx

echo "=== [3/6] Setting up firewall ==="
ufw allow OpenSSH
ufw allow 80/tcp
ufw allow 443/tcp
ufw --force enable

echo "=== [4/6] Creating app directory ==="
mkdir -p /opt/grozeo
echo "Clone your repos into /opt/grozeo/ and copy docker-compose.yml there."

echo "=== [5/6] Setting up swap (for 4GB droplets) ==="
if [ ! -f /swapfile ]; then
    fallocate -l 2G /swapfile
    chmod 600 /swapfile
    mkswap /swapfile
    swapon /swapfile
    echo '/swapfile none swap sw 0 0' >> /etc/fstab
fi

echo "=== [6/6] Docker post-install ==="
systemctl enable docker
systemctl start docker

echo ""
echo "========================================="
echo " Droplet setup complete!"
echo "========================================="
echo ""
echo "Next steps:"
echo "  1. cd /opt/grozeo"
echo "  2. Clone all repos (or copy codebase)"
echo "  3. Copy docker-compose.yml to /opt/grozeo/"
echo "  4. Create .env files for Bizapi and Scheduler"
echo "  5. Run: docker compose up -d"
echo "  6. Set up SSL: certbot --nginx -d bizapi.grozeo.in -d '*.grozeo.in'"
echo ""
