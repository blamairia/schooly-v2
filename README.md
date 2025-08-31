# Schooly v2

A modern school management system built with Laravel and Filament.

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## 🚀 Docker Development Setup

### Quick Start with Docker Compose (Recommended)

The easiest way to get started is with Docker Compose, which automatically sets up the database and application:

```bash
# Clone the repository
git clone https://github.com/blamairia/schooly-v2.git
cd schooly-v2

# Start the application with Docker Compose
docker-compose up

# Access the application at http://localhost:8000
```

### Manual Development with Docker Compose

If you prefer a manual setup workflow similar to `docker run`, use the development compose file:

```bash
# Start the container with bash shell (no auto-setup)
docker-compose -f docker-compose.dev.yml up

# In another terminal, enter the container
docker-compose -f docker-compose.dev.yml exec app bash

# Inside the container, run setup scripts
./scripts/dev-check.sh    # Check environment
./scripts/dev-setup.sh    # Set up application  
./scripts/dev-start.sh    # Start server
```

### Manual Docker Setup

If you prefer to run the container manually:

1. **Build the Docker image:**
   ```bash
   docker build -t schooly-v2-app .
   ```

2. **Run the container:**
   ```bash
   # On Windows:
   docker run --name schooly-v2-app-dev --rm -it -v C:\path\to\schooly-v2:/var/www/html -p 8000:8000 schooly-v2-app bash
   
   # On Mac/Linux:
   docker run --name schooly-v2-app-dev --rm -it -v $(pwd):/var/www/html -p 8000:8000 schooly-v2-app bash
   ```

3. **Inside the container, set up the application:**
   ```bash
   # First, check if the environment is ready (optional)
   ./scripts/dev-check.sh
   
   # Run the setup script to install dependencies and configure the app
   ./scripts/dev-setup.sh
   
   # Start the Laravel development server
   ./scripts/dev-start.sh
   ```

4. **Access the application:**
   Open your browser to [http://localhost:8000](http://localhost:8000)

### Manual Setup Steps

If you prefer to run the setup steps manually:

```bash
# Copy environment file
cp .env.example .env

# Install PHP dependencies
composer install

# Generate application key
php artisan key:generate

# Install and build frontend assets
npm install
npm run build

# Set up database (if database is available)
php artisan migrate
php artisan db:seed --class=PaymentMethodsSeeder

# Start the development server
php artisan serve --host=0.0.0.0 --port=8000
```

## 🛠️ Troubleshooting

### Common Issues

**Problem: "docker: Error response from daemon: No such image: schooly-v2-app"**
- Solution: Build the Docker image first: `docker build -t schooly-v2-app .`

**Problem: Cannot access the application on localhost:8000**
- Make sure you used the correct port mapping: `-p 8000:8000`
- Check if another service is using port 8000: `netstat -an | grep 8000`

**Problem: Database connection errors**
- If using Docker manually, you need to set up a database separately or use docker-compose
- Update your `.env` file with correct database credentials

**Problem: Permission denied for scripts**
- Make sure scripts are executable: `chmod +x scripts/*.sh`

### Development Scripts

- `./scripts/dev-check.sh` - Check if environment has all required tools
- `./scripts/dev-setup.sh` - Set up Laravel application (install dependencies, generate keys, etc.)
- `./scripts/dev-start.sh` - Start Laravel development server

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
