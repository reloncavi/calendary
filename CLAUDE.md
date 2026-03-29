# CLAUDE.md — Calendary Codebase Guide

This file provides context for AI assistants working on this repository.

---

## Project Overview

**Calendary** is a Laravel 6 calendar application generated with QuickAdminPanel, featuring a multi-source calendar (Events and Meetings displayed together), venue management, role-based access control, and a RESTful API. The UI is bilingual (English/Spanish), with Spanish as the primary language.

---

## Technology Stack

| Layer | Technology |
|-------|-----------|
| Language | PHP 7.2+ |
| Framework | Laravel 6.2 |
| Database | MySQL |
| Auth | Session (web) + Laravel Passport OAuth2 (API) |
| Frontend JS | Vue.js 2, jQuery 3, FullCalendar 3, Axios |
| CSS | Bootstrap 4, CoreUI 2 |
| Build | Laravel Mix 4 (Webpack wrapper) |
| Testing | PHPUnit 8 + Mockery + Laravel Dusk |

---

## Repository Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # Admin CRUD controllers
│   │   ├── Api/V1/Admin/   # REST API controllers (Passport-protected)
│   │   ├── Auth/           # Login, register, password reset
│   │   ├── CalendarController.php   # Public calendar view
│   │   └── HomeController.php
│   ├── Middleware/
│   │   ├── AuthGates.php   # Loads roles/permissions into Gate on every request
│   │   └── SetLocale.php   # Session-based language switching
│   ├── Requests/           # Form request validation classes
│   └── Resources/Admin/    # API JSON transformers
├── Event.php, Meeting.php, Venue.php, User.php, Role.php, Permission.php
config/
├── panel.php               # date_format, time_format, primary_language
database/
├── migrations/             # Schema definitions (all tables use soft deletes)
├── seeds/                  # Initial admin user, roles, permissions
resources/
├── js/                     # Vue.js app entry + components
├── lang/en/ lang/es/       # Localization strings
├── sass/                   # SASS stylesheets
└── views/                  # Blade templates (layouts, admin, auth, partials)
routes/
├── web.php                 # Session-authenticated web routes
└── api.php                 # Passport-authenticated API routes (/api/v1/)
tests/
├── Feature/, Unit/         # PHPUnit tests
└── Browser/                # Laravel Dusk browser tests
webpack.mix.js              # Compiles resources/js/app.js → public/js/app.js
                            #           resources/sass/app.scss → public/css/app.css
```

---

## Core Domain Models

### Event
- Fields: `name`, `start_time`, `end_time`, `venue_id` (FK)
- Relations: `belongsTo(Venue)`
- Uses soft deletes and Carbon mutators for date formatting
- **Conflict detection**: Overlapping events at the same venue are blocked (see `EventsController`)

### Meeting
- Fields: `attendees`, `start_time`
- No `end_time` field — this is intentional
- No venue relation

### Venue
- Fields: `name`, `address`
- Relations: `hasMany(Event)`

### User
- Fields: `name`, `email`, `password`
- Relations: `belongsToMany(Role)`
- Uses Laravel Passport for API token issuance

### Role / Permission
- Many-to-many pivot tables: `role_user`, `role_permission`
- Default roles seeded: Admin (all permissions), User (event/venue/meeting only)

---

## Authorization System

Permissions are loaded once per request in `AuthGates` middleware:

```php
// app/Http/Middleware/AuthGates.php
Gate::define('event_create', fn($user) => $user->roles->flatMap->permissions->contains('title', 'event_create'));
```

In controllers, authorization is checked with:
```php
abort_if(Gate::denies('event_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
```

**Permission titles follow the pattern**: `{model}_{action}` — e.g., `event_create`, `event_edit`, `event_delete`, `event_access`, `user_management_access`.

---

## Routing Conventions

- **Web routes** (`routes/web.php`): Authenticated via `auth` middleware, grouped under `/admin/`
- **API routes** (`routes/api.php`): Authenticated via `auth:api` (Passport), versioned under `/api/v1/admin/`
- **API HTTP status codes**: 201 (store), 202 (update), 204 (destroy), 403 (unauthorized)

---

## Date & Time Handling

- **Storage format**: `Y-m-d H:i:s` (MySQL datetime)
- **Display format**: Configured in `config/panel.php` — `date_format = 'Y-m-d'`, `time_format = 'H:i:s'`
- Models use Carbon mutators (`getStartTimeAttribute` / `setStartTimeAttribute`) to apply the configured format
- Date inputs in forms must match the panel format

---

## Localization

- **Primary language**: Spanish (`es`) — set in `config/panel.php`
- **Available languages**: `en` (English), `es` (Spanish)
- Language is switched via `?change_language=en` query param, stored in session
- `SetLocale` middleware applies `App::setLocale()` on every request
- User-facing flash messages in controllers are currently written directly in Spanish

---

## Frontend

- **Entry point**: `resources/js/app.js` — mounts Vue to `#app`
- **Vue components**: Auto-discovery is commented out; register components explicitly in `app.js`
- **FullCalendar 3**: Used in `SystemCalendarController` and `CalendarController`; calendar sources are Events and Meetings JSON endpoints
- **CSRF**: Token in `<meta name="csrf-token">` tag; Axios reads it automatically
- **jQuery**: Available globally via npm; used for DataTables, Select2, DateTimePicker

Build commands:
```bash
npm run dev        # Development build
npm run watch      # Watch + rebuild on change
npm run prod       # Production build (minified)
```

---

## Development Setup

```bash
git clone <repo>
cp .env.example .env
# Edit .env: set DB_DATABASE, DB_USERNAME, DB_PASSWORD
composer install
php artisan key:generate
php artisan migrate --seed
npm install
npm run dev
php artisan serve
```

Default admin credentials (seeded): `admin@ssdr.gob.cl` / `password`

### Laravel Passport Setup (first time)

```bash
php artisan passport:install
```

---

## Testing

```bash
# Run all tests
php artisan test
# or
./vendor/bin/phpunit

# Browser tests (requires running server + chromedriver)
php artisan dusk
```

Test environment uses in-memory drivers: `CACHE_DRIVER=array`, `SESSION_DRIVER=array`, `MAIL_DRIVER=array`, `QUEUE_CONNECTION=sync`.

Browser tests in `tests/Browser/` cover each entity (Events, Meetings, Venues, Users, Roles, Permissions) via Dusk.

---

## Key Conventions

### Code Style
- PSR-4 autoloading under `App\` namespace
- Models: singular PascalCase (`Event`, `Venue`)
- Controllers: plural PascalCase (`EventsController`, `VenuesController`)
- Database columns: snake_case (`start_time`, `venue_id`)
- Methods: camelCase; classes: PascalCase

### Flash Messages
User feedback uses session flash messages displayed in views:
```php
Session()->flash('message', 'Evento creado con exito');
Session()->flash('alert-class', 'alert-success');
// alert-class options: alert-success, alert-danger, alert-warning
```

### Soft Deletes
All main tables include `deleted_at`. Do not hard-delete records; use `$model->delete()` which sets `deleted_at`. Use `withTrashed()` or `onlyTrashed()` scopes when querying deleted records.

### Mass Assignment
All models define `$fillable` arrays explicitly. Always add new fields to `$fillable` before using `create()` or `fill()`.

### API Resources
Resources in `app/Http/Resources/Admin/` currently pass through `parent::toArray()`. When customizing API responses, override `toArray(Request $request)` in the appropriate resource class rather than modifying controller logic.

### Validation
Use Form Request classes in `app/Http/Requests/` for all validation. Gate authorization belongs in the `authorize()` method of the request class, not in controller methods.

---

## Common Artisan Commands

```bash
php artisan migrate              # Run new migrations
php artisan migrate:rollback     # Rollback last migration batch
php artisan db:seed              # Run all seeders
php artisan tinker               # Interactive REPL
php artisan route:list           # Show all registered routes
php artisan make:model Foo -mcr  # Model + migration + resource controller
php artisan make:request StoreFooRequest
```

---

## Error Tracking

Bugsnag is integrated (`bugsnag/bugsnag-laravel`). Set `BUGSNAG_API_KEY` in `.env` to enable error reporting in production.
