# Schooly - School Payment Management System

A comprehensive school payment management system built with Laravel 10, Filament 3, and Livewire. This application helps schools manage student payments, track payment plans, generate reports, and send reminders.

## Features

### Student Management
- Complete student information management
- Parent/guardian contact details
- Class and academic year tracking
- External student support

### Payment Management
- Multiple payment types and methods
- Flexible payment plans with installments
- Division deadlines for payment schedules
- Payment tracking and history
- Payment totals and reporting

### Financial Operations
- Cashier management
- Payment method tracking (Cash, Check, Bank Transfer, etc.)
- Payment receipts with PDF generation
- Financial reports and analytics

### Administrative Features
- Role-based access control
- Activity logging
- Automated payment reminders
- Backup and restore functionality
- Import/Export capabilities
- Advanced filtering and search

### Dashboard & Analytics
- Visual charts and statistics
- Payment trends analysis
- Study year overview
- Quick access to key metrics

## Tech Stack

- **Framework:** Laravel 10
- **Admin Panel:** Filament 3
- **Frontend:** Livewire 3, Alpine.js, Tailwind CSS
- **Authentication:** Laravel Jetstream with Sanctum
- **Database:** MariaDB/MySQL
- **PDF Generation:** DomPDF
- **Charts:** Apex Charts
- **Additional:** Spatie packages (Activity Log, Backup)

## Requirements

- PHP 8.1 or higher
- Composer
- Node.js & NPM
- MariaDB 11.8+ or MySQL 8.0+
- Nginx or Apache web server

## Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd schooly-v2
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Configuration

```bash
# Copy the environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Configure Database

Edit `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=schooly
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Run Migrations and Seeders

```bash
# Create database tables
php artisan migrate

# Seed initial data (admin user and payment methods)
php artisan db:seed
```

### 6. Build Frontend Assets

```bash
# For development
npm run dev

# For production
npm run build
```

### 7. Set Permissions

```bash
# Set proper permissions for storage and cache
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## Usage

### Accessing the Application

- **Main Application (Subdomain):** http://schooly.localhost
- **Admin Panel (Subdomain):** http://schooly.localhost/admin
- **Direct Access (Port 8080):** http://localhost:8080
- **Admin Panel (Direct):** http://localhost:8080/admin

**Note:** The subdomain `schooly.localhost` is configured in `/etc/hosts` to resolve to `127.0.0.1`. This provides a cleaner URL structure.

### Default Credentials

After running the seeders, you can login with:

- **Email:** admin@example.com
- **Password:** password

**Important:** Change the default password immediately after first login!

### Payment Methods

The system includes the following payment methods by default:
- Cash
- Check
- Bank Transfer
- Credit Card
- Mobile Payment

## Development

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature
```

### Code Style

```bash
# Format code using Laravel Pint
./vendor/bin/pint
```

### Debugging

The application includes Laravel Debugbar for development. It's automatically enabled when `APP_DEBUG=true`.

## Key Modules

### Students Module
Manage student records, enrollment, and parent information.

### Payments Module
Process payments, track payment history, and manage payment plans.

### Division Plans & Deadlines
Set up payment schedules and installment plans for different student groups.

### Reminders Module
Automated system for sending payment reminders to parents.

### Reports & Analytics
Generate financial reports, payment summaries, and analytics.

## Configuration

### Backup Configuration

Configure backup settings in `config/backup.php`. The system uses Spatie Laravel Backup for automated backups.

### Printing Configuration

Configure printing options in `config/printing.php` for receipt printing.

## Deployment

### Production Checklist

1. Set `APP_ENV=production` and `APP_DEBUG=false`
2. Configure proper database credentials
3. Set up queue workers for background jobs
4. Configure mail settings for notifications
5. Set up automated backups
6. Configure SSL certificate
7. Optimize application:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### Web Server Configuration

#### Nginx

The application requires Nginx configured to point to the `public` directory. See the included nginx configuration example.

#### Apache

Ensure `mod_rewrite` is enabled and the `.htaccess` file in the public directory is being read.

## Troubleshooting

### Permission Issues

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### Database Connection Issues

- Verify database credentials in `.env`
- Ensure MariaDB/MySQL service is running
- Check database user permissions

### Asset Issues

```bash
# Clear and rebuild assets
npm run build
php artisan view:clear
```

## Contributing

Contributions are welcome! Please follow these guidelines:

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Write/update tests
5. Submit a pull request

## Security

If you discover any security vulnerabilities, please email the development team immediately. Do not create public issues for security vulnerabilities.

## License

This project is licensed under the MIT License.

## Support

For support and questions:
- Create an issue in the repository
- Contact the development team
- Check the documentation

## Acknowledgments

- Laravel Framework
- Filament Admin Panel
- All open-source packages used in this project

---

**Version:** 2.0  
**Last Updated:** December 2025  
**Status:** Active Development
