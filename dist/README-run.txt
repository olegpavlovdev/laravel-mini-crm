Package contents:
- laravel-crm-prod-v1.0.0.tar.gz  (gzipped image tar)
- laravel-crm-prod-v1.0.0.sha256  (SHA256 checksum)
- docker-compose-run.yml (compose file that runs the app and a MySQL DB for quick local setup)

Quick start (client-side):
1) Load the image:
   docker load -i laravel-crm-prod-v1.0.0.tar.gz

2) Start the stack:
   docker compose -f docker-compose-run.yml up -d

3) Run migrations & seeders inside the app container (one-time):
   docker compose -f docker-compose-run.yml exec app php artisan migrate --force --seed

4) Open the app in your browser: http://localhost:8080

Notes:
- Do NOT include any production secrets in the image. Provide env values at runtime via env files or docker-compose overrides.
- Verify the checksum after download: sha256sum laravel-crm-prod-v1.0.0.tar.gz
