#!/bin/bash

# Docker startup script for Laravel application

echo "🚀 Starting Laravel application..."

# Export PATH to include composer and npm
export PATH=/usr/local/bin:$PATH

# Check if tools are available
echo "🔍 Checking available tools..."
which composer >/dev/null 2>&1 && echo "✅ Composer found" || echo "❌ Composer not found"
which npm >/dev/null 2>&1 && echo "✅ NPM found" || echo "❌ NPM not found"
which node >/dev/null 2>&1 && echo "✅ Node.js found" || echo "❌ Node.js not found"
which php >/dev/null 2>&1 && echo "✅ PHP found" || echo "❌ PHP not found"

echo "📦 Installing dependencies..."

# Install PHP dependencies if composer.json exists
if [ -f composer.json ]; then
    echo "Installing Composer dependencies..."
    composer install --no-interaction --prefer-dist --optimize-autoloader || echo "Composer install failed, continuing..."
else
    echo "No composer.json found"
fi

# Install Node.js dependencies if package.json exists
if [ -f package.json ]; then
    echo "Installing NPM dependencies..."
    npm install --no-audit --no-progress || echo "NPM install failed, continuing..."
else
    echo "No package.json found"
fi

# Build assets if NPM build script exists
if [ -f package.json ] && npm run --silent 2>/dev/null | grep -q "build"; then
    echo "Building assets..."
    npm run build 2>/dev/null || echo "NPM build failed, continuing..."
fi

# Generate application key if needed
if [ -f artisan ]; then
    echo "Generating application key..."
    php artisan key:generate --ansi || echo "Key generation failed, continuing..."
    
    echo "Running migrations..."
    php artisan migrate --force || echo "Migration failed, continuing..."
    
    # Try to seed database
    echo "Seeding database..."
    php artisan db:seed --class=PaymentMethodsSeeder || echo "Seeding failed, continuing..."
    
    echo "🎉 Starting Laravel development server..."
    php artisan serve --host=0.0.0.0 --port=8000
else
    echo "❌ No artisan file found, running bash instead"
    bash
fi