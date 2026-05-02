
# Laravel 12 Installation Guide — Local and Docker from GitHub
This document shows step‑by‑step instructions to clone a Laravel 12 project from GitHub and run it locally (native PHP + Composer) and dockerized (Docker Compose). It includes a ready set of files and commands you can copy into your repo so any developer can reproduce the environment.

##  Prerequisites
- For local install
  - PHP 8.3+ installed and on PATH.

  - Composer installed globally.

  - Node.js 18+ and npm or pnpm for frontend assets.

  - A local database (MySQL 8 / MariaDB / PostgreSQL) or use SQLite for quick testing.

- For Docker

  - Docker Desktop (Windows/macOS) or Docker Engine + Docker Compose (Linux).

  - Basic familiarity with Docker volumes and networking.

- Git to clone the repository.

## Local Installation
1. Clone the GitHub Repository
```bash
git clone https://github.com/your-org/your-laravel12-repo.git my-laravel-app
cd my-laravel-app
```

2. Install PHP dependencies
```bash
composer install --no-interaction --prefer-dist
```

3. Environment file and app key

```bash
cp .env.example .env
php artisan key:generate
```
Edit .env and set database credentials and other secrets:

```bash
APP_NAME=MyApp
APP_ENV=local
APP_KEY=base64:...
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=myapp
DB_USERNAME=root
DB_PASSWORD=secret
```

4. Database setup and migrations
```bash
php artisan migrate --seed

```
5. Install frontend dependencies and build
```bash
npm install
npm run dev        # for development
npm run build      # for production assets
```
6. Run the app
```bash
php artisan serve --host=127.0.0.1 --port=8000
# Visit http://127.0.0.1:8000
```
## Dockerized Installation (Docker Compose)

1. Create DockerFile
2. Nginx/Caddy config (optional)
3. Create docker-compose.yml or Compose.yaml
4. Create .env.docker (do not commit secrets):

```bash
APP_NAME=MyApp
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8080

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=myapp
DB_USERNAME=myapp
DB_PASSWORD=secret

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

```
5. Build and Run

```bash
# Build and start containers
docker compose up -d --build

# Install composer dependencies inside container (if not baked in)
docker compose exec app composer install --no-interaction --prefer-dist

# Generate app key
docker compose exec app php artisan key:generate

# Run migrations
docker compose exec app php artisan migrate --seed

# Build frontend assets (runs in node container)
docker compose exec node npm install
docker compose exec node npm run dev

```
