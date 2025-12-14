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

# Set ownership to nginx/PHP-FPM user (Azure uses www-data or nginx)
chown -R www-data:www-data storage bootstrap/cache || chown -R nginx:nginx storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

#######################################
# Laravel bootstrap
#######################################
php artisan cache:clear || true
php artisan migrate --force || true

# Seed the database (admin user + payment methods)
php artisan db:seed --force || true

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
php-fpm &

# Wait for PHP-FPM to be ready
sleep 3

# Pre-warm the application to reduce cold start impact
echo "Pre-warming application..."
curl -s http://localhost:8080 > /dev/null 2>&1 || true

echo "=== Azure Laravel startup DONE ==="

# Keep container alive (required for Azure)
tail -f /dev/null