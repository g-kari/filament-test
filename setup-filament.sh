#!/bin/bash

set -e

# Check if running inside the container
if [ ! -f /.dockerenv ]; then
    echo "Please run this script inside the Docker container"
    echo "docker-compose exec app bash -c './setup-filament.sh'"
    exit 1
fi

echo "Setting up Laravel and Filament..."

# Make sure we are in the correct directory
cd /var/www

# Clear existing files except for docker and setup files
find . -mindepth 1 -not -path "./docker*" -not -name "docker-compose.yml" -not -name "setup-filament.sh" -not -path "./.git*" -not -path "./storage*" -delete

# Create a new Laravel project
echo "Creating a new Laravel project..."
composer create-project laravel/laravel .

# Configure .env file
echo "Configuring .env for database connection..."
sed -i "s/DB_HOST=127.0.0.1/DB_HOST=db/" .env
sed -i "s/DB_DATABASE=laravel/DB_DATABASE=filament/" .env
sed -i "s/DB_USERNAME=root/DB_USERNAME=filament/" .env
sed -i "s/DB_PASSWORD=/DB_PASSWORD=secret/" .env
sed -i "s/REDIS_HOST=127.0.0.1/REDIS_HOST=valkey/" .env

# Install Filament
composer require filament/filament:"^3.0-stable" -W

# Run migrations and Filament installation
echo "Running migrations..."
php artisan migrate

echo "Installing Filament..."
php artisan filament:install --panels

# Create a sample Filament model and resource
echo "Creating a sample model and Filament resource..."
php artisan make:model Post -m

# Edit the migration file for Posts table
cat > database/migrations/$(ls -t database/migrations | grep create_posts_table | head -n 1) << 'EOL'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('status')->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
EOL

# Edit the Post model
cat > app/Models/Post.php << 'EOL'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'status'];
}
EOL

# Create a Filament resource for the Post model
echo "Creating Filament resource for Post model..."
php artisan make:filament-resource Post --generate

# Run migrations again to create the posts table
echo "Running migrations for Posts table..."
php artisan migrate

# Create a Filament user
echo "Creating Filament user..."
php artisan make:filament-user

# Set proper permissions
echo "Setting proper permissions..."
chown -R www:www /var/www
find /var/www -type f -exec chmod 644 {} \;
find /var/www -type d -exec chmod 755 {} \;
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

echo "Laravel and Filament have been set up successfully!"
echo "You can access the Filament admin panel at: http://localhost/admin"