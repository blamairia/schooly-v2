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

# Set ownership to nginx/PHP-FPM user (Azure uses www-data)
# The Azure container runs as www-data by default
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
chown -R www-data:www-data storage/framework 2>/dev/null || true

# Set proper permissions (775 for directories, 664 for files)
find storage bootstrap/cache -type d -exec chmod 775 {} \; 2>/dev/null || true
find storage bootstrap/cache -type f -exec chmod 664 {} \; 2>/dev/null || true

#######################################
# Laravel bootstrap
#######################################
# Verify session directory is writable before starting
if [ ! -w "storage/framework/sessions" ]; then
  echo "⚠️  Session directory not writable, fixing..."
  chmod -R 775 storage/framework/sessions
  chown -R www-data:www-data storage/framework/sessions 2>/dev/null || true
fi

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
# Final permission check (critical for sessions)
#######################################
echo "Ensuring storage permissions for PHP-FPM..."
chown -R www-data:www-data storage 2>/dev/null || true
chmod -R 775 storage 2>/dev/null || true

# Specifically ensure session directory is writable
chmod 777 storage/framework/sessions 2>/dev/null || true

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