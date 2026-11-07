# Package compatibility

## Admin UI

| Package | Status | Notes |
|---------|--------|-------|
| **Filament 3** | **Installed — primary admin** | Tenant workspace at `/app`, platform at `/platform` |
| `imrandevbd/laravel-admin-panel` | **Not installed** | Competing admin UI; only documented as a possible adapter pattern. Do not install alongside Filament without a dedicated bridge. |

## ImranDevBd packages (pinned in `composer.json`)

| Package | Role |
|---------|------|
| `imrandevbd/laravel-ai-hub` | AI assistant (`AIHub` facade, `AIHub::fake()` in tests) |
| `imrandevbd/laravel-ai-catalog` | Optional catalog intelligence (disabled by default) |
| `imrandevbd/laravel-attendance-hub` | Attendance integrations (migrations present) |
| `imrandevbd/laravel-unicode-pdf` | PDF generation for operational documents |
| `imrandevbd/laravel-universal-slug` | Slugs on `Style` and similar models |
| `imrandevbd/drutogoti` | Query debugging (dev-oriented) |

## Laravel ecosystem

- Laravel **12**, PHP **8.2+**
- **Sanctum** — API tokens
- **Spatie Laravel Permission** — roles/permissions (`web` + `sanctum` guards)
- **Pest** — feature and unit tests

## Database

- **SQLite** default for local demo (`database/database.sqlite`)
- **MySQL** supported for staging/production (see `docs/DEPLOYMENT.md`)

## Queues

- Recommended: `QUEUE_CONNECTION=redis` with Redis 6+
- Tests force `sync` via `phpunit.xml`
