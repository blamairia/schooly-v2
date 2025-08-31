FROM php:8.2-cli

# Install system packages and PHP extensions required by Laravel
RUN apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -y \
    git curl zip unzip libzip-dev libicu-dev libxml2-dev libpng-dev libonig-dev build-essential ca-certificates gnupg2 \
  && docker-php-ext-install pdo_mysql mbstring zip exif intl pcntl bcmath opcache \
  && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js (LTS 20) and npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
  && apt-get install -y nodejs \
  && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Expose port used by php artisan serve during development
EXPOSE 8000

# Keep container interactive by default
CMD ["bash"]
