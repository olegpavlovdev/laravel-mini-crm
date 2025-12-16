# API Documentation (OpenAPI)

This project includes an OpenAPI (Swagger) file at `docs/openapi.yaml` describing the public API endpoints:

- `POST /api/tickets` — create a ticket (multipart/form-data, supports `files[]`).
- `GET /api/tickets/statistics` — return ticket counts for day/week/month.

How to view locally

- With Docker/Swagger UI (quick):

```bash
docker run --rm -p 8080:8080 -v "$PWD/docs:/usr/share/nginx/html" swaggerapi/swagger-ui
# then open http://localhost:8080/index.html?url=/openapi.yaml
```

- Or use any OpenAPI viewer (Swagger Editor, Redoc, etc.), or add the YAML to an existing Swagger UI instance.

Local Swagger UI (static)

I added a simple static Swagger UI page at `docs/swagger_ui/index.html` that loads the `docs/openapi.yaml` file. You can serve the `docs` folder and open the page in a browser, or use the helper script `scripts/serve_openapi_docs.ps1` which will:

- Use Docker (preferred): run the official `swaggerapi/swagger-ui` container mounting your `docs` folder and exposing port 8080.
- Fall back to Python's `http.server` if Docker isn't available.

Example (PowerShell):

```powershell
.\\scripts\\serve_openapi_docs.ps1 -Port 8080
# then open: http://localhost:8080/swagger_ui/index.html
```

Notes

- The `POST /api/tickets` endpoint is rate-limited by `check.ticket.rate` middleware and may return 429 responses.
 - The `POST /api/tickets` endpoint is rate-limited by `check.ticket.rate` middleware and may return 429 responses. The current policy is **no more than 1 request per second** from the same phone number or email address.
- Validation rules are defined in `app/Http/Requests/StoreTicketRequest.php`.

Security

- A `BearerAuth` (HTTP Bearer token, e.g. JWT) security scheme is defined in the OpenAPI file under `components.securitySchemes`. Endpoints are public by default in this project, but you can apply `security` requirements in the YAML to require authentication for specific endpoints.

Example cURL (with optional bearer token):

```bash
curl -X POST "http://localhost/api/tickets" \
	-H "Authorization: Bearer <TOKEN>" \
	-F "name=John Doe" \
	-F "email=john@example.com" \
	-F "phone=+12345678901" \
	-F "subject=Help" \
	-F "message=I have a problem" \
	-F "files[]=@./doc.pdf"
```

Please review the YAML and tell me if you want additional endpoints, examples, or security definitions added.