#!/bin/bash
# Laravel Azure Startup Script
# This script is called from Azure Web App startup command

# Create required Laravel directories FIRST
mkdir -p /home/site/wwwroot/bootstrap/cache
mkdir -p /home/site/wwwroot/storage/framework/cache/data
mkdir -p /home/site/wwwroot/storage/framework/sessions
mkdir -p /home/site/wwwroot/storage/framework/views
mkdir -p /home/site/wwwroot/storage/logs
mkdir -p /home/site/wwwroot/storage/app/public

# Set proper permissions
chmod -R 775 /home/site/wwwroot/storage
chmod -R 775 /home/site/wwwroot/bootstrap/cache

# Clear caches first (before config cache to avoid stale data)
php /home/site/wwwroot/artisan cache:clear || true

# Run migrations (skip maintenance mode for now)
php /home/site/wwwroot/artisan migrate --force || true

# Cache routes, config, and views
php /home/site/wwwroot/artisan config:cache || true
php /home/site/wwwroot/artisan route:cache || true
php /home/site/wwwroot/artisan view:cache || true

# Create storage link (ignore if exists)
php /home/site/wwwroot/artisan storage:link 2>/dev/null || true

echo "Laravel startup completed successfully"
