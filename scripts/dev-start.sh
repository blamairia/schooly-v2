#!/bin/bash

# Laravel Development Server Start Script
# This script starts the Laravel development server

set -e  # Exit on any error

echo "🚀 Starting Laravel development server..."

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: artisan file not found. Please run this script from the Laravel root directory."
    exit 1
fi

# Check if .env exists
if [ ! -f ".env" ]; then
    echo "❌ Error: .env file not found. Please run ./scripts/dev-setup.sh first."
    exit 1
fi

# Check if vendor directory exists
if [ ! -d "vendor" ]; then
    echo "❌ Error: vendor directory not found. Please run ./scripts/dev-setup.sh first."
    exit 1
fi

echo "🌐 Starting Laravel development server on http://0.0.0.0:8000"
echo "   Access from host machine at: http://localhost:8000"
echo ""
echo "💡 Press Ctrl+C to stop the server"
echo ""

# Start the Laravel development server
php artisan serve --host=0.0.0.0 --port=8000