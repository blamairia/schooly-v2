#!/bin/bash

# Docker cleanup script for schooly-v2
# This script helps resolve port conflicts and cleans up orphaned containers

echo "🧹 Cleaning up Docker containers and networks for schooly-v2..."

# Stop and remove all containers for this project
echo "📦 Stopping containers..."
docker compose down --remove-orphans

# Remove any containers that might be using port 8000
echo "🔍 Checking for processes using port 8000..."
PROCESS_USING_8000=$(lsof -ti:8000)
if [ ! -z "$PROCESS_USING_8000" ]; then
    echo "⚠️  Found process(es) using port 8000: $PROCESS_USING_8000"
    echo "You may need to stop these processes manually"
    lsof -i:8000
else
    echo "✅ Port 8000 is available"
fi

# Check for any dangling schooly containers
echo "🔍 Removing any orphaned schooly containers..."
docker ps -a --filter "name=schooly" --format "table {{.Names}}\t{{.Status}}" | grep -v "NAME"
docker rm -f $(docker ps -a --filter "name=schooly" -q) 2>/dev/null || echo "No orphaned containers found"

# Clean up networks
echo "🌐 Cleaning up networks..."
docker network prune -f

# Clean up volumes (be careful with this)
echo "📁 Listing volumes (not removing automatically):"
docker volume ls | grep schooly || echo "No schooly volumes found"

echo ""
echo "✨ Cleanup complete! You can now run 'docker compose up --build' to start fresh."
echo ""
echo "If you still encounter port conflicts, you may need to:"
echo "1. Restart Docker daemon: sudo systemctl restart docker"
echo "2. Check for other services using port 8000: sudo lsof -i:8000"
echo "3. Change the port in docker-compose.yml if needed"