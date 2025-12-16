<#
Simple helper to serve the docs.
- If Docker is available, it runs the official Swagger UI container and mounts ./docs.
- Otherwise it will attempt to serve the `docs` folder via Python's http.server.
#>

param(
    [int]$Port = 8080
)

function HasCommand($name) {
    try { Get-Command $name -ErrorAction Stop | Out-Null; return $true } catch { return $false }
}

if (HasCommand 'docker') {
    Write-Host "Starting Swagger UI via Docker on http://localhost:$Port (CTRL+C to stop)..." -ForegroundColor Green
    docker run --rm -p $Port:8080 -v "${PWD}\docs:/usr/share/nginx/html" swaggerapi/swagger-ui
    exit 0
}

if (HasCommand 'python') {
    Write-Host "Docker not found. Serving ./docs via Python http.server on http://localhost:$Port (CTRL+C to stop)..." -ForegroundColor Yellow
    python -m http.server $Port --directory "${PWD}\docs"
    exit 0
}

Write-Host "Neither Docker nor Python found in PATH. Please install one of them, or serve the ./docs directory with a static server." -ForegroundColor Red
exit 1
