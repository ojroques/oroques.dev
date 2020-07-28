#!/bin/bash

set -e

# DigitalOcean API credentials .ini file (absolute path)
CREDENTIALS="$(cd "$(dirname "$0")"; pwd -P)/credentials.ini"
PROPAGATION=2000  # Duration for DNS to propagate
DOMAIN="oroques.dev"

if [[ $EUID -ne 0 ]]; then
  echo "This script must be run as root"
  exit 1
fi

echo "CERTIFICATE INSTALLATION"
echo "------------------------------------------------------------"

echo "[Changing file's permissions]"
chmod 600 "$CREDENTIALS"

echo "[Obtaining an SSL certificate]"
certbot certonly \
  --dns-digitalocean \
  --dns-digitalocean-credentials "$CREDENTIALS" \
  --dns-digitalocean-propagation-seconds "$PROPAGATION" \
  -d "*.$DOMAIN" \
  -d "$DOMAIN"

echo "[Changing permissions]"
chmod 0755 /etc/letsencrypt/{live,archive}

echo "[Verifying certbot auto-renewal]"
certbot renew --dry-run

echo "------------------------------------------------------------"
echo "Certificate configuration done."
