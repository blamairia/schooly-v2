#!/bin/bash
# Azure Permission Diagnostic Script
# Run this via Azure SSH if you still have permission issues

echo "=== Laravel Storage Permissions Diagnostic ==="
echo ""

echo "Current user:"
whoami
id
echo ""

echo "PHP-FPM user:"
ps aux | grep php-fpm | head -3
echo ""

echo "Storage directory ownership and permissions:"
ls -la storage/
echo ""

echo "Storage/framework directory:"
ls -la storage/framework/
echo ""

echo "Sessions directory (CRITICAL for login):"
ls -la storage/framework/sessions/
echo ""

echo "Session files (if any):"
ls -la storage/framework/sessions/ | head -10
echo ""

echo "Bootstrap/cache directory:"
ls -la bootstrap/cache/
echo ""

echo "Testing write to session directory:"
if touch storage/framework/sessions/test-write-$$; then
  echo "✅ Session directory is WRITABLE"
  rm -f storage/framework/sessions/test-write-$$
else
  echo "❌ Session directory is NOT WRITABLE - THIS IS THE PROBLEM!"
  echo "   Run: chmod 777 storage/framework/sessions"
fi
echo ""

echo "Environment variable for SESSION_DRIVER:"
echo "SESSION_DRIVER = $SESSION_DRIVER"
echo ""

echo "Laravel .env check:"
grep SESSION_DRIVER .env 2>/dev/null || echo "Not found in .env"
