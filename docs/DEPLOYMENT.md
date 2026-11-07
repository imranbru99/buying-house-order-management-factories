# Deployment guide

## Requirements

- PHP 8.2+ with extensions: `pdo`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`
- Composer 2.x
- Node 20+ (Vite assets for Filament)
- Redis (queues/cache recommended)
- MySQL 8+ **or** SQLite (demo only)

## Environment

1. Copy `.env.example` to `.env` and set `APP_KEY` (`php artisan key:generate`).
2. For production, use MySQL and set `APP_ENV=production`, `APP_DEBUG=false`.
3. Set `QUEUE_CONNECTION=redis` and run a queue worker.
4. Configure `TRADEFLOW_BASE_DOMAIN` for subdomain tenancy.
5. Set `TRADEFLOW_DEMO_ENABLED=false` and `DEMO_SEED=false` in production unless you intentionally host a sandbox.

## Install

```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Web server

- Document root: `public/`
- Rewrite all requests to `public/index.php`
- TLS termination at load balancer or web server

## Workers & scheduler

```bash
php artisan queue:work redis --sleep=3 --tries=3
```

Add cron: `* * * * * php /path/to/artisan schedule:run`

## Demo reset (non-production only)

```bash
php artisan tradeflow:demo-reset --force
```

Allowed only when `APP_ENV` is `local` or `demo`.

## Health

- Laravel health route: `/up`
- After deploy, verify `/app/login` and `GET /api/v1/auth/me` with a valid token.
