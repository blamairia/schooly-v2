#!/bin/bash

# Integration test for Docker development setup
# This script simulates the user workflow inside a Docker container

echo "🧪 Testing Docker development setup workflow..."

# Create a temporary directory for testing
TEST_DIR="/tmp/schooly-test"
mkdir -p "$TEST_DIR"

# Copy necessary files for testing
cp -r /home/runner/work/schooly-v2/schooly-v2/. "$TEST_DIR/" 2>/dev/null || true
cd "$TEST_DIR"

echo "📁 Test environment: $TEST_DIR"
echo "🔍 Testing environment check..."

# Test 1: Environment check
if ./scripts/dev-check.sh | grep -q "Environment check passed"; then
    echo "✅ Environment check: PASSED"
else
    echo "❌ Environment check: FAILED"
    exit 1
fi

echo "🔍 Testing file structure and permissions..."

# Test 2: Check if scripts are executable
if [ -x ./scripts/dev-setup.sh ] && [ -x ./scripts/dev-start.sh ] && [ -x ./scripts/dev-check.sh ]; then
    echo "✅ Script permissions: PASSED"
else
    echo "❌ Script permissions: FAILED"
    exit 1
fi

# Test 3: Check Laravel structure
required_files=("artisan" "composer.json" "package.json" ".env.example")
for file in "${required_files[@]}"; do
    if [ ! -f "$file" ]; then
        echo "❌ Required file missing: $file"
        exit 1
    fi
done
echo "✅ Laravel structure: PASSED"

# Test 4: Check script syntax
for script in scripts/*.sh; do
    if ! bash -n "$script"; then
        echo "❌ Script syntax error: $script"
        exit 1
    fi
done
echo "✅ Script syntax: PASSED"

echo ""
echo "🎉 All tests passed! Docker development setup is ready."
echo ""
echo "📋 User workflow verification:"
echo "   1. ✅ Environment check script works"
echo "   2. ✅ All required files present"
echo "   3. ✅ Scripts are executable" 
echo "   4. ✅ Script syntax is valid"
echo ""
echo "🚀 Ready for users to run:"
echo "   docker run --name schooly-v2-app-dev --rm -it -v <path>:/var/www/html -p 8000:8000 schooly-v2-app bash"
echo "   ./scripts/dev-check.sh"
echo "   ./scripts/dev-setup.sh"
echo "   ./scripts/dev-start.sh"