# Laravel Mini CRM - Setup Guide

## Prerequisites

- Docker Desktop installed and running
- Git (optional)

## Installation Steps

### 1. Extract the Project

Extract the project zip file to your desired location.

### 2. Configure Environment

Copy and customize the environment file:

```bash
cp .env.example .env
```

Edit `.env` and update the following if needed:
- `APP_NAME` - Your application name
- `APP_URL` - The URL where your app will run (default: http://localhost:8080)
- `DB_PASSWORD` - Change this to a secure password

### 3. Start Docker Containers

```bash
docker-compose up -d
```

This will:
- Build the Laravel app container
- Start Nginx web server
- Start MySQL database

### 4. Install Dependencies

```bash
# Install PHP dependencies
docker exec laravel_crm_app composer install

```
### 5. Generate Application Key

```bash
docker exec laravel_crm_app php artisan key:generate
```

### 6. Run Database Migrations

```bash
docker exec laravel_crm_app php artisan migrate
```

### 7. (Optional) Seed Sample Data

```bash
docker exec laravel_crm_app php artisan db:seed
```

## Accessing the Application

- **Web Application**: http://localhost:8080
- **API Endpoints**: http://localhost:8080/api
- **MySQL Database**: localhost:3306
  - Username: `crm`
  - Password: (check your `.env` file)

## Common Commands

### View Logs

```bash
docker-compose logs -f app
```

### Access Application Container

```bash
docker exec -it laravel_crm_app bash
```

### Run Artisan Commands

```bash
docker exec laravel_crm_app php artisan [command]
```

### Stop Containers

```bash
docker-compose down
```

### Stop and Remove Database Volume (⚠️ WARNING: Deletes data)

```bash
docker-compose down -v
```

## Troubleshooting

### "Connection refused" when accessing http://localhost:8080

- Ensure Docker Desktop is running
- Run `docker-compose up -d` to start containers
- Check container status: `docker-compose ps`

### Database connection error

- Verify `.env` database credentials match `docker-compose.yml`
- Run `docker-compose logs db` to check database logs
- Ensure MySQL container is running: `docker-compose ps`

### File permissions issues

```bash
docker exec laravel_crm_app chmod -R 775 storage bootstrap/cache
```

### Need to rebuild containers

```bash
docker-compose build --no-cache
docker-compose up -d
```

## Database Backup/Restore

### Backup Database

```bash
docker exec laravel_crm_db mysqldump -u crm -p[PASSWORD] crm > backup.sql
```

### Restore Database

```bash
docker exec -i laravel_crm_db mysql -u crm -p[PASSWORD] crm < backup.sql
```

## Production Deployment

For production, consider:
1. Update `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false` in `.env`
3. Use `Dockerfile.prod` instead of `Dockerfile`
4. Update database password to a strong, secure password
5. Configure proper SSL certificates
6. Set appropriate resource limits in `docker-compose.yml`

## Support

For issues or questions, refer to the [Laravel Documentation](https://laravel.com/docs)
