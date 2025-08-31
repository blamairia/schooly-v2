#!/bin/bash

# Development Environment Check Script
# This script checks if the development environment has all required tools

echo "🔍 Checking development environment..."

# Check for required commands
commands=("php" "composer" "npm" "node")
missing_commands=()

for cmd in "${commands[@]}"; do
    if command -v "$cmd" &> /dev/null; then
        version=$(eval "$cmd --version" 2>/dev/null | head -1)
        echo "✅ $cmd: $version"
    else
        echo "❌ $cmd: Not found"
        missing_commands+=("$cmd")
    fi
done

# Check PHP extensions
echo ""
echo "🔍 Checking PHP extensions..."
required_extensions=("pdo" "pdo_mysql" "mbstring" "zip" "bcmath" "intl")
missing_extensions=()

for ext in "${required_extensions[@]}"; do
    if php -m | grep -i "$ext" &> /dev/null; then
        echo "✅ PHP extension $ext: Available"
    else
        echo "❌ PHP extension $ext: Missing"
        missing_extensions+=("$ext")
    fi
done

# Check directory structure
echo ""
echo "🔍 Checking Laravel application structure..."
required_files=("artisan" "composer.json" "package.json" ".env.example")
missing_files=()

for file in "${required_files[@]}"; do
    if [ -f "$file" ]; then
        echo "✅ $file: Found"
    else
        echo "❌ $file: Missing"
        missing_files+=("$file")
    fi
done

# Summary
echo ""
if [ ${#missing_commands[@]} -eq 0 ] && [ ${#missing_extensions[@]} -eq 0 ] && [ ${#missing_files[@]} -eq 0 ]; then
    echo "🎉 Environment check passed! Ready to run development setup."
    echo ""
    echo "Next steps:"
    echo "  1. Run ./scripts/dev-setup.sh to set up the application"
    echo "  2. Run ./scripts/dev-start.sh to start the development server"
else
    echo "⚠️  Environment check found issues:"
    if [ ${#missing_commands[@]} -gt 0 ]; then
        echo "   Missing commands: ${missing_commands[*]}"
    fi
    if [ ${#missing_extensions[@]} -gt 0 ]; then
        echo "   Missing PHP extensions: ${missing_extensions[*]}"
    fi
    if [ ${#missing_files[@]} -gt 0 ]; then
        echo "   Missing files: ${missing_files[*]}"
    fi
fi