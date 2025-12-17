Client Delivery: Laravel Mini-CRM

Purpose
- This package contains everything the client needs to build and run the Laravel Mini-CRM in Docker on their machine.

Prerequisites
- Docker Desktop (Windows/Mac) or Docker Engine + Docker Compose (Linux). Ensure Docker is running and has internet access.
- At least 2 CPUs and 4 GB RAM allocated to Docker recommended.

Two delivery options

Option A — Client builds the image locally (recommended)
1. Open a terminal in the project root (where `Dockerfile.prod` is located).
2. Build the production image:

```powershell
docker build -f Dockerfile.prod -t laravel-mini-crm:prod-v1.0.0 .
```

3. Start the stack using the provided compose file:

```powershell
docker compose -f dist/CLIENT_DELIVERY/docker-compose-run.yml up -d
```

4. Run database migrations and seed the manager user:

```powershell
docker compose -f dist/CLIENT_DELIVERY/docker-compose-run.yml exec app php artisan migrate --force
docker compose -f dist/CLIENT_DELIVERY/docker-compose-run.yml exec app php artisan db:seed --class=RoleAndUserSeeder
```

5. Open the app in a browser: http://localhost:8080

Option B — Use a pre-built image (if provided separately)
- If you receive an image tar/gz from us (e.g. `laravel-crm-prod-v1.0.0.tar.gz`), load it locally:

Linux / WSL / macOS:
```bash
docker load < laravel-crm-prod-v1.0.0.tar.gz
```

Windows PowerShell (if you have the tar file):
```powershell
docker load -i .\laravel-crm-prod-v1.0.0.tar
```

Then start the stack as in step 3.

Notes & Troubleshooting
- If the build fails during Composer install, ensure Docker has enough memory and CPUs, and that the machine has outbound internet access (Composer will download packages).
- If you prefer faster builds in CI, use a persistent composer cache or a registry.
- If ports 8080 or 3306 are in use, edit `dist/CLIENT_DELIVERY/docker-compose-run.yml` to change host port mappings.

Contact
- If you want, I can instead produce and deliver a compressed image tar.gz for you to hand to the client. Reply and I will build and export the image when your Docker daemon is available.