#!/bin/bash

# Start FrankenPHP for Filament Application

echo "🚀 Starting Filament with FrankenPHP..."

# Check if environment argument is provided
ENVIRONMENT=${1:-dev}

# Function to show usage
show_usage() {
    echo "Usage: $0 [dev|prod]"
    echo "  dev  - Start development environment (default)"
    echo "  prod - Start production environment"
    exit 1
}

# Validate environment argument
if [[ "$ENVIRONMENT" != "dev" && "$ENVIRONMENT" != "prod" ]]; then
    echo "❌ Invalid environment: $ENVIRONMENT"
    show_usage
fi

# Set the appropriate docker-compose file
if [[ "$ENVIRONMENT" == "prod" ]]; then
    COMPOSE_FILE="docker-compose.frankenphp.yml"
    echo "🏭 Starting production environment..."
else
    COMPOSE_FILE="docker-compose.frankenphp.dev.yml"
    echo "🛠️  Starting development environment..."
fi

# Check if docker-compose file exists
if [[ ! -f "$COMPOSE_FILE" ]]; then
    echo "❌ Docker compose file not found: $COMPOSE_FILE"
    exit 1
fi

# Stop any existing containers
echo "🛑 Stopping existing containers..."
docker-compose -f docker-compose.yml down 2>/dev/null || true
docker-compose -f docker-compose.frankenphp.yml down 2>/dev/null || true
docker-compose -f docker-compose.frankenphp.dev.yml down 2>/dev/null || true

# Build and start the containers
echo "🔨 Building and starting containers..."
docker-compose -f "$COMPOSE_FILE" up --build -d

# Check if containers started successfully
if [[ $? -eq 0 ]]; then
    echo "✅ FrankenPHP started successfully!"
    echo ""
    echo "📍 Application URLs:"
    echo "   • Main application: http://localhost"
    echo "   • Filament admin: http://localhost/admin"
    echo ""
    echo "🔧 Database connection:"
    echo "   • Host: localhost"
    echo "   • Port: 3306"
    echo "   • Database: filament"
    echo "   • Username: filament"
    echo "   • Password: secret"
    echo ""
    echo "📊 To view logs:"
    echo "   docker-compose -f $COMPOSE_FILE logs -f"
    echo ""
    echo "🛑 To stop:"
    echo "   docker-compose -f $COMPOSE_FILE down"
else
    echo "❌ Failed to start FrankenPHP containers"
    exit 1
fi