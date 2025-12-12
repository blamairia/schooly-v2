#!/bin/bash
set -e

echo "=== Azure Laravel startup BEGIN ==="

# Always run from app root
cd /home/site/wwwroot

#######################################
# Apply nginx config (Laravel public/)
#######################################
if [ -f /home/site/wwwroot/default ]; then
  echo "Applying nginx config"
  cp /home/site/wwwroot/default /etc/nginx/sites-available/default
  service nginx reload
fi

#######################################
# Apply custom PHP config (optional)
#######################################
if [ -f /home/site/wwwroot/php.ini ]; then
  echo "Applying php.ini"
  cp /home/site/wwwroot/php.ini /usr/local/etc/php/conf.d/php.ini
fi

#######################################
# Laravel filesystem prep
#######################################
mkdir -p \
  bootstrap/cache \
  storage/framework/cache/data \
  storage/framework/sessions \
  storage/framework/views \
  storage/logs \
  storage/app/public

chmod -R 775 storage bootstrap/cache || true

#######################################
# Laravel bootstrap
#######################################
php artisan cache:clear || true
php artisan migrate --force || true

php artisan config:clear || true
php artisan config:cache || true
php artisan route:cache || true
php artisan view:clear || true
php artisan view:cache || true

php artisan storage:link || true

# Ensure Vite build assets are present (should be built before deployment)
if [ ! -d "public/build" ]; then
  echo "WARNING: public/build directory not found. Run 'npm run build' before deployment."
fi

php artisan up || true

#######################################
# Start PHP-FPM (REQUIRED)
#######################################
echo "Starting php-fpm"
php-fpm

echo "=== Azure Laravel startup DONE ==="