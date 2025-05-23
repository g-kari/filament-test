#!/bin/bash

# This script is a convenience wrapper to set up and run the Filament environment

set -e

# Check if Docker is running
if ! docker info >/dev/null 2>&1; then
  echo "Docker is not running. Please start Docker and try again."
  exit 1
fi

# Check if docker-compose is installed
if ! command -v docker-compose >/dev/null 2>&1; then
  echo "docker-compose is not installed. Please install docker-compose and try again."
  exit 1
fi

# Display banner
echo "================================"
echo "Filament Evaluation Environment"
echo "================================"

# Start Docker containers
echo "Starting Docker containers..."
docker-compose up -d

# Wait for containers to be ready
echo "Waiting for containers to be ready..."
timeout=60  # Maximum wait time in seconds
interval=5  # Interval between checks in seconds
elapsed=0

while ! docker-compose ps | grep -q "Up"; do
  if [ $elapsed -ge $timeout ]; then
    echo "Timeout reached. Failed to start Docker containers. Please check the logs with 'docker-compose logs'"
    exit 1
  fi
  echo "Containers not ready yet. Retrying in $interval seconds..."
  sleep $interval
  elapsed=$((elapsed + interval))
done

echo "Containers are ready."
# Ask if user wants to run the setup script
read -p "Do you want to run the Filament setup script? (y/n): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
  echo "Running setup script inside the container..."
  docker-compose exec -T app bash -c "chmod +x /var/www/setup-filament.sh && /var/www/setup-filament.sh"

  # Create a Filament user
  echo
  echo "Creating a Filament user..."
  docker-compose exec -T app php artisan make:filament-user
else
  echo "Skipping setup script. You can run it later with:"
  echo "docker-compose exec app bash -c './setup-filament.sh'"
fi

echo
echo "================================"
echo "Setup Complete!"
echo "================================"
echo
echo "You can access the Filament admin panel at: http://localhost/admin"
echo
echo "Database details:"
echo "- Host: 'localhost' (from host) or 'db' (from containers)"
echo "- Port: 3306"
echo "- Username: filament"
echo "- Password: secret"
echo "- Database: filament"
echo
echo "To stop the environment: docker-compose down"
echo "To restart the environment: docker-compose restart"
echo "To check logs: docker-compose logs"