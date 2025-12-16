# Laravel Mini-CRM (Test Project)

This repository contains a mini-CRM project (spec) for collecting requests from a website via a feedback widget.

## Quick Docker Setup

Requirements: Docker & Docker Compose.

1. Build and start containers:

```powershell
docker compose up -d --build
```

2. Install PHP dependencies (inside app container):

```powershell
docker compose exec app composer install
docker compose exec app cp .env.example .env
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
```

3. App will be available at: http://localhost:8080

## Widget embed example

Embed the widget page with an iframe:

```html
<iframe src="https://your-domain.com/widget" width="400" height="600"></iframe>
```

## Next steps

- Add Laravel scaffolding (models, migrations, controllers)
- Implement authentication and roles (spatie/laravel-permission)
- Implement API endpoints and widget frontend

## Installing packages and setting up authentication / roles

After composer dependencies are installed inside the `app` container, run:

```powershell
# from host: docker compose exec app sh
composer require spatie/laravel-permission spatie/laravel-medialibrary

# publish vendor assets and migrations then migrate
php artisan vendor:publish --provider="Spatie\\Permission\\PermissionServiceProvider"
php artisan vendor:publish --provider="Spatie\\MediaLibrary\\MediaLibraryServiceProvider" --tag="migrations"
php artisan migrate --seed
```

This repository includes a minimal `config/permission.php` and permission migrations so the `RoleAndUserSeeder` will create a `manager` user automatically (email: `manager@example.com`, password: `password`).

## Integrating file attachments (Spatie MediaLibrary)

This project supports attachments via `spatie/laravel-medialibrary`. Follow these steps inside the `app` container:

```powershell
composer require spatie/laravel-medialibrary
# publish config and (optional) vendor migrations
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="config"
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="migrations"

# create storage symlink so public disk files are accessible
php artisan storage:link

# Run migrations and seeders (media table migration is included in the repo already)
php artisan migrate --seed
```

Notes:
- The `Ticket` model registers an `attachments` media collection using the `public` disk. Uploaded files are stored under `storage/app/public` and are accessible via `/storage` when `php artisan storage:link` is created.
- The widget form supports file uploads; attachments are attached by the `TicketService` using `$ticket->addMedia($file)->toMediaCollection('attachments')`.
- Admin ticket detail view provides download links powered by the repository media objects.

Troubleshooting:
- If attachments do not appear or downloads return 404, make sure the `public` disk is writable and `php artisan storage:link` has been run.
- If `getUrl()` returns null, check the `filesystems.php` disk configuration and ensure the `public` disk points to `storage/app/public` and has `url` configured (typically `APP_URL/storage`).


