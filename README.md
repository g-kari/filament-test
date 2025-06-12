# Filament-test

## Docker Development Environment

This repository includes a complete Docker-based development environment with PHP-FPM, Nginx, MySQL, and Valkey (Redis fork) for testing Filament Admin Panel.

### Prerequisites

- Docker and Docker Compose installed on your system
- Git

### Getting Started

1. Clone this repository:
   ```
   git clone https://github.com/g-kari/filament-test.git
   cd filament-test
   ```

2. Start the Docker containers:
   ```
   docker-compose up -d
   ```

3. Access the Filament admin panel in your browser:
   ```
   http://localhost/admin
   ```

### Environment Details

- **PHP-FPM**: PHP 8.2 with common extensions required for Laravel/Filament
- **Nginx**: Latest stable version configured to serve PHP applications
- **MySQL**: Version 8.0 with persistent storage
- **Valkey**: Redis-compatible in-memory data store

### Database Connection

- Host: `localhost` (from host) or `db` (from containers)
- Port: `3306`
- Username: `filament`
- Password: `secret`
- Database: `filament`

### Valkey Connection

- Host: `localhost` (from host) or `valkey` (from containers)
- Port: `6379`

### Laravel & Filament Setup

To set up a new Laravel project with Filament:

1. Execute bash in the PHP container:
   ```
   docker-compose exec app bash
   ```

2. Create a new Laravel project (if not already existing):
   ```
   composer create-project laravel/laravel .
   ```

3. Install Filament:
   ```
   composer require filament/filament:"^3.0-stable" -W
   ```

4. Run the Filament installation:
   ```
   php artisan filament:install --panels
   ```

5. Create a user for Filament:
   ```
   php artisan make:filament-user
   ```

### Troubleshooting

If you encounter any issues:

1. Check container logs:
   ```
   docker-compose logs
   ```

2. Verify all containers are running:
   ```
   docker-compose ps
   ```

3. Restart all containers:
   ```
   docker-compose restart
   ```

## Production Hosting

For production deployment considerations, hosting service recommendations, and deployment strategies, see [HOSTING.md](HOSTING.md).