# name this file as "startup.sh" and call it from "startup command" as "/home/site/wwwroot/laravel-azure/startup.sh"

# Copy nginx configuration
cp /home/site/wwwroot/laravel-azure/nginx-default /etc/nginx/sites-enabled/default

# Copy php.ini configuration
cp /home/site/wwwroot/laravel-azure/php.ini /usr/local/etc/php/conf.d/php.ini

# install support for webp file conversion (Optional: This can slow down startup)
# apt-get update --allow-releaseinfo-change && apt-get install -y libfreetype6-dev \
#                 libjpeg62-turbo-dev \
#                 libpng-dev \
#                 libwebp-dev \
#         && docker-php-ext-configure gd --with-freetype --with-webp  --with-jpeg
# docker-php-ext-install gd

# install support for queue
apt-get update && apt-get install -y supervisor 

# Create required Laravel directories FIRST
mkdir -p /home/site/wwwroot/bootstrap/cache
mkdir -p /home/site/wwwroot/storage/framework/cache/data
mkdir -p /home/site/wwwroot/storage/framework/sessions
mkdir -p /home/site/wwwroot/storage/framework/views
mkdir -p /home/site/wwwroot/storage/logs
mkdir -p /home/site/wwwroot/storage/app/public

# Create supervisor log directory
mkdir -p /var/log/supervisor

# Set proper permissions
chmod -R 775 /home/site/wwwroot/storage
chmod -R 775 /home/site/wwwroot/bootstrap/cache
chown -R www-data:www-data /home/site/wwwroot/storage
chown -R www-data:www-data /home/site/wwwroot/bootstrap/cache

# Copy supervisor configuration
cp /home/site/wwwroot/laravel-azure/laravel-worker.conf /etc/supervisor/conf.d/laravel-worker.conf

# restart nginx
service nginx restart

# Start supervisor
service supervisor start

# Clear caches first (before config cache to avoid stale data)
php /home/site/wwwroot/artisan cache:clear || true

# Run artisan commands
php /home/site/wwwroot/artisan down --refresh=15

# Run migrations
php /home/site/wwwroot/artisan migrate --force || true

# Cache routes, config, and views
php /home/site/wwwroot/artisan config:cache
php /home/site/wwwroot/artisan route:cache
php /home/site/wwwroot/artisan view:cache

# Create storage link
php /home/site/wwwroot/artisan storage:link || true

# Turn off maintenance mode
php /home/site/wwwroot/artisan up

