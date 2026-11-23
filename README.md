# Twina Safaris

Tourism / safari booking website for Twina Safaris (Tanzania), built with Laravel 13 + PHP 8.3, Tailwind CSS and Alpine.js.

Features: tour & safari catalog, online bookings with manual payment tracking, custom safari inquiries, trip plans, client proposals (PDF), blog, gallery, newsletter (double opt-in), AI-assisted content/SEO tools, multi-language UI and multi-currency price display.

## Requirements

- PHP 8.3+ with `pdo_sqlite` (or MySQL), `gd`, `mbstring`, `dom`, `fileinfo`
- Composer
- Node.js + npm (frontend assets)

## Local setup

```bash
composer install
cp .env.example .env
php artisan key:generate

# SQLite (default): create the database file, then migrate + seed
touch database/database.sqlite        # Windows: New-Item database/database.sqlite
php artisan migrate --seed

npm install
npm run build                         # or: npm run dev

php artisan storage:link
php artisan serve
```

The seeder creates an admin account. By default a random password is generated and
printed once in the console. To choose your own, set these in `.env` before seeding:

```dotenv
ADMIN_SEED_EMAIL=admin@yourdomain.com
ADMIN_SEED_PASSWORD=a-strong-password
```

Admin panel: `/admin` (login with the seeded account).

## Queue worker (required for email campaigns)

Newsletter campaigns are dispatched to the queue (`QUEUE_CONNECTION=database`).
They are only sent while a worker is running:

```bash
php artisan queue:work --tries=3 --timeout=600
```

- **Local (Windows):** keep a second terminal open with the command above.
  Without it, campaigns stay queued and are not delivered.
- **Production (Linux):** use supervisor — see [`deploy/supervisor-queue.conf`](deploy/supervisor-queue.conf).
  After each deploy run `php artisan queue:restart` so workers pick up new code.
- Failed jobs land in `failed_jobs`; inspect with `php artisan queue:failed`
  and retry with `php artisan queue:retry all`.

## Currency display

Tour prices are stored in **USD**. Visitors can switch display currency
(USD / EUR / GBP / TZS / KES / UGX) from the navbar; prices are converted using
rates managed in **Admin → Settings → Currency Exchange Rates** (sensible
defaults are built in). Bookings are charged in the currency selected at checkout.
Update the rates regularly — they are manual, not live market rates.

## Newsletter

Subscriptions use double opt-in: the subscriber receives a confirmation email and
only becomes active after clicking the link. Every campaign email contains a
personal unsubscribe link. Campaigns are sent from **Admin → Email Campaigns**.

## Tests & code style

```bash
php artisan test          # feature + unit tests (SQLite in-memory)
vendor/bin/pint --test    # code style check
vendor/bin/pint           # auto-fix style
```

CI (`.github/workflows/ci.yml`) runs Pint, migrations and the full test suite on
every push / pull request.

## Deployment checklist

- `APP_ENV=production`, `APP_DEBUG=false`
- Real `MAIL_*` credentials (SMTP) and a running queue worker
- `php artisan migrate --force`, `php artisan storage:link`
- `php artisan config:cache && php artisan route:cache && php artisan view:cache`
- `php artisan queue:restart` after each deploy
- Keep `database/database.sqlite` (or your MySQL DB) backed up before migrating
