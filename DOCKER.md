# Docker Setup for Schooly V2

This repository includes Docker configuration for easy development and deployment.

## Requirements

- Docker
- Docker Compose

## Quick Start

1. Clone the repository
2. Run the cleanup script if you encounter port conflicts:
   ```bash
   ./docker-cleanup.sh
   ```
3. Start the application:
   ```bash
   docker compose up --build
   ```

The application will be available at http://localhost:8000

## Services

- **app**: Laravel application (PHP 8.2 with Composer and Node.js)
- **db**: MySQL 8.0 database

## Port Configuration

- Application: `8000` (configurable in docker-compose.yml)
- Database: `3306` (configurable in docker-compose.yml)

## Troubleshooting Port Conflicts

If you encounter "port already allocated" errors:

1. Run the cleanup script:
   ```bash
   ./docker-cleanup.sh
   ```

2. Check for processes using port 8000:
   ```bash
   sudo lsof -i:8000
   ```

3. If needed, change the port in docker-compose.yml:
   ```yaml
   ports:
     - "8001:8000"  # Use port 8001 instead
   ```

## Docker Commands

- Start services: `docker compose up -d`
- Stop services: `docker compose down`
- View logs: `docker compose logs app`
- Rebuild: `docker compose build --no-cache`
- Clean up: `./docker-cleanup.sh`

## Development

The application code is mounted as a volume, so changes are reflected immediately without rebuilding the container.

## Environment Variables

Copy `.env.example` to `.env` and modify as needed. The Docker setup includes default values for database connectivity.