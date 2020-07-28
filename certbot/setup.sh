#!/bin/bash

set -e

# DigitalOcean API token .ini file (absolute path)
TOKEN="$(cd "$(dirname "$0")"; pwd -P)/digitalocean.ini"
PROPAGATION=2000  # Duration for DNS to propagate
DOMAIN="oroques.dev"

if [[ $EUID -ne 0 ]]; then
  echo "This script must be run as root"
  exit 1
fi

echo "CERTIFICATE INSTALLATION"
echo "------------------------------------------------------------"

echo "[Changing file's permissions]"
chmod 0600 "$TOKEN"

echo "[Obtaining an SSL certificate]"
certbot certonly \
  --dns-digitalocean \
  --dns-digitalocean-credentials "$TOKEN" \
  --dns-digitalocean-propagation-seconds "$PROPAGATION" \
  -d "*.$DOMAIN" \
  -d "$DOMAIN"

echo "[Changing permissions]"
chmod 0600 "$TOKEN"
chmod 0755 /etc/letsencrypt/{live,archive}

echo "[Verifying certbot auto-renewal]"
certbot renew --dry-run

echo "------------------------------------------------------------"
echo "Certificate configuration done."
