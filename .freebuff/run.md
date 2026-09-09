# POS System - Preview Run Doc

## How to Reproduce Artifacts

No build step needed — this is a server-rendered Laravel/Filament app with SQLite.

1. Ensure `.env` exists (it does in the main checkout). The SQLite database is at `database/database.sqlite` and is already migrated and seeded.
2. Ensure PHP 8.3+ and Composer dependencies are installed (`vendor/` exists).

## How to Run the Server

Start the Laravel development server on port 8000:

```bash
php artisan serve --port=8000
```

The server listens on `http://127.0.0.1:8000`. The root URL (`/`) redirects to the Filament login at `/admin/login`.

### Default Login

| Field    | Value           |
|----------|-----------------|
| Email    | admin@pos.local |
| Password | password        |

### Preview Setup

The preview uses an HTML wrapper (`.freebuff/preview.html`) that loads the Laravel app via iframe from `http://127.0.0.1:8000/admin/login`.
