#!/bin/bash

# Laravel Development Setup Script
# This script sets up the Laravel application for development inside Docker

set -e  # Exit on any error

echo "🚀 Setting up Laravel development environment..."

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: artisan file not found. Please run this script from the Laravel root directory."
    exit 1
fi

# Step 1: Copy environment file if it doesn't exist
if [ ! -f ".env" ]; then
    echo "📋 Copying .env.example to .env..."
    cp .env.example .env
else
    echo "✅ .env file already exists"
fi

# Step 2: Install Composer dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader

# Step 3: Generate application key if not set
echo "🔑 Generating application key..."
php artisan key:generate --ansi

# Step 4: Install NPM dependencies
echo "🎨 Installing NPM dependencies..."
npm install --no-audit --no-progress

# Step 5: Build frontend assets
echo "🏗️  Building frontend assets..."
npm run build

# Step 6: Set up storage permissions
echo "📁 Setting up storage permissions..."
chmod -R 775 storage bootstrap/cache

# Step 7: Check if database connection is available (optional, non-blocking)
echo "🗄️  Checking database connection..."
if php artisan migrate:status 2>/dev/null; then
    echo "✅ Database connection successful"
    
    # Run migrations
    echo "🗄️  Running database migrations..."
    php artisan migrate --force
    
    # Seed database if needed
    echo "🌱 Seeding database..."
    php artisan db:seed --class=PaymentMethodsSeeder --force 2>/dev/null || echo "⚠️  PaymentMethodsSeeder not found or already seeded"
else
    echo "⚠️  Database connection not available. Run migrations manually when database is ready:"
    echo "   php artisan migrate"
    echo "   php artisan db:seed --class=PaymentMethodsSeeder"
fi

echo ""
echo "✅ Laravel development environment setup complete!"
echo ""
echo "🎯 Next steps:"
echo "   1. Start the development server: ./scripts/dev-start.sh"
echo "   2. Or run manually: php artisan serve --host=0.0.0.0 --port=8000"
echo "   3. Open your browser to: http://localhost:8000"
echo ""