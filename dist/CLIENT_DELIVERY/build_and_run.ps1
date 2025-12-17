# Build and run helper for the client (PowerShell)
# Usage: Run in repository root (where Dockerfile.prod is located)

$ErrorActionPreference = 'Stop'

Write-Host "Checking Docker..."
try {
    docker info --format '{{.ServerVersion}}' | Out-Null
} catch {
    Write-Error "Docker is not running or not available. Start Docker Desktop and try again."
    exit 1
}

# Build image
Write-Host "Building production image 'laravel-mini-crm:prod-v1.0.0' (this may take several minutes)..."
docker build -f Dockerfile.prod -t laravel-mini-crm:prod-v1.0.0 .

# Start stack from bundled compose
Write-Host "Starting services via docker compose (dist/CLIENT_DELIVERY/docker-compose-run.yml) ..."
docker compose -f dist/CLIENT_DELIVERY/docker-compose-run.yml up -d --remove-orphans

# Run migrations
Write-Host "Running migrations and seeder (RoleAndUserSeeder)..."
docker compose -f dist/CLIENT_DELIVERY/docker-compose-run.yml exec app php artisan migrate --force
docker compose -f dist/CLIENT_DELIVERY/docker-compose-run.yml exec app php artisan db:seed --class=RoleAndUserSeeder

Write-Host "Done. Application should be available at http://localhost:8080"
