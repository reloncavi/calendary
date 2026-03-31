# CLAUDE.md — Calendary Codebase Guide

This file provides context for AI assistants working on this repository.

---

## Project Overview

**Calendary** is a Laravel 11 calendar application generated with QuickAdminPanel, featuring a multi-source calendar (Events and Meetings displayed together), venue management, equipment loan tracking, role-based access control, and a RESTful API. The UI is bilingual (English/Spanish), with Spanish as the primary language.

---

## Technology Stack

| Layer | Technology |
|-------|-----------|
| Language | PHP 8.2+ |
| Framework | Laravel 11.0 |
| Database | MySQL |
| Auth | Session (web) + Laravel Sanctum (API) |
| Frontend JS | jQuery 3.7, flatpickr 4.6, Select2 4.1, DataTables |
| CSS | Bootstrap 5.3 |
| Build | Vite 5.0 |
| Testing | PHPUnit 11 + Mockery + Laravel Dusk 8 |
| Code Style | Laravel Pint |

**Removed from original scaffold**: Vue.js 2, CoreUI 2, Laravel Mix/Webpack, Laravel Passport, FullCalendar.

---

## Repository Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/               # Admin CRUD controllers (web, session auth)
│   │   ├── Api/V1/Admin/        # REST API controllers (Sanctum-protected)
│   │   ├── Auth/                # Login, register, password reset
│   │   ├── CalendarController.php   # Public calendar view (/Calendary, no auth)
│   │   └── HomeController.php       # Redirects to admin.systemCalendar
│   ├── Middleware/
│   │   ├── AuthGates.php        # Loads roles/permissions into Gate on every request
│   │   └── SetLocale.php        # Session-based language switching
│   ├── Requests/                # Form request validation classes (Events, Meetings,
│   │                            #   Venues, Permissions, Roles, Users only — NOT Equipment)
│   └── Resources/Admin/         # API JSON transformers
├── Event.php, Meeting.php, Venue.php, Equipment.php, EquipmentLoan.php
├── User.php, Role.php, Permission.php
config/
├── panel.php                    # date_format, time_format, primary_language
database/
├── migrations/                  # Schema definitions (all tables use soft deletes)
├── seeders/                     # Initial admin user, roles, permissions
resources/
├── js/                          # JS entry point + bootstrap.js
├── lang/en/ lang/es/            # Localization strings
├── sass/                        # SASS stylesheets (Bootstrap 5)
└── views/                       # Blade templates (layouts, admin, auth, partials)
routes/
├── web.php                      # Session-authenticated web routes
└── api.php                      # Sanctum-authenticated API routes (/api/v1/)
tests/
├── Feature/, Unit/              # PHPUnit tests
└── Browser/                     # Laravel Dusk browser tests
vite.config.js                   # Vite build config: resources/js/app.js → public/build/
                                 #                    resources/sass/app.scss → public/build/
```

---

## Core Domain Models

### Event
- Fields: `name`, `start_time`, `end_time`, `venue_id` (FK)
- Relations: `belongsTo(Venue)`
- Uses soft deletes and Carbon mutators for date formatting
- **Conflict detection**: Overlapping events at the same venue are blocked in `EventsController`

### Meeting
- Fields: `attendees`, `start_time`, `end_time`
- Note: `end_time` was added in migration `2024_01_01_000001`; it is now present
- No venue relation; soft deletes; Carbon mutators

### Venue
- Fields: `name`, `address`
- Relations: `hasMany(Event)`
- Soft deletes

### Equipment — NEW
- Fields: `name`, `type`, `code` (nullable), `description` (nullable)
- Constants: `TYPES = ['proyector', 'notebook', 'micrófono', 'otro']`
- Relations: `hasMany(EquipmentLoan)`, `activeLoans()` scope
- Soft deletes

### EquipmentLoan — NEW
- Fields: `equipment_id` (FK), `borrower_name`, `purpose` (nullable), `start_time`, `end_time`, `returned_at` (nullable), `notes` (nullable)
- Relations: `belongsTo(Equipment)`
- Carbon mutators for date fields; soft deletes
- `isReturned()` helper method checks `returned_at`
- **Conflict detection**: Overlapping loans for the same equipment are blocked
- **Custom route**: `PATCH /admin/equipment-loans/{equipmentLoan}/return` marks equipment as returned

### User
- Fields: `name`, `email`, `password`, `email_verified_at`
- Relations: `belongsToMany(Role)`
- Uses `HasApiTokens` (Sanctum)
- Soft deletes

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
- **API routes** (`routes/api.php`): Authenticated via `auth:sanctum`, versioned under `/api/v1/`
- **API HTTP status codes**: 201 (store), 202 (update), 204 (destroy), 403 (unauthorized)
- **Mass destroy**: Each resource has a `DELETE /admin/{resource}/destroy` route with a `MassDestroyRequest`
- **Equipment return**: `PATCH /admin/equipment-loans/{equipmentLoan}/return`

### Web Route Groups (summary)

```
GET  /Calendary                         # Public calendar (no auth)
GET  /home                              # Redirects to admin.systemCalendar

/admin/ (auth middleware):
  system-calendar                       # SystemCalendarController
  permissions, roles, users             # RBAC management
  venues, events, meetings              # Core calendar entities
  equipment, equipment-loans            # Equipment management (NEW)
```

---

## API Endpoints

All endpoints are under `/api/v1/` and require `Authorization: Bearer <sanctum-token>`:

```
GET/POST   /api/v1/permissions
GET/PUT/DELETE /api/v1/permissions/{id}

GET/POST   /api/v1/roles
GET/POST   /api/v1/users
GET/POST   /api/v1/venues
GET/POST   /api/v1/events
GET/POST   /api/v1/meetings
GET/POST   /api/v1/equipment          (NEW)
GET/POST   /api/v1/equipment-loans    (NEW)
```

---

## Date & Time Handling

- **Storage format**: `Y-m-d H:i:s` (MySQL datetime)
- **Display format**: Configured in `config/panel.php` — `date_format = 'Y-m-d'`, `time_format = 'H:i:s'`
- Models use Carbon mutators (`getStartTimeAttribute` / `setStartTimeAttribute`) to apply the configured format
- **Date picker in forms**: flatpickr (replaces jQuery DateTimePicker) — initialized in `resources/js/app.js` with Spanish locale

---

## Localization

- **Primary language**: Spanish (`es`) — set in `config/panel.php` and `config/app.php`
- **Available languages**: `en` (English), `es` (Spanish)
- Language is switched via `?change_language=en` query param, stored in session
- `SetLocale` middleware applies `App::setLocale()` on every request
- User-facing flash messages in controllers are written directly in Spanish

---

## Frontend

- **Entry point**: `resources/js/app.js` — initializes flatpickr (Spanish locale) and Select2
- **No Vue.js**: Vue components were removed; no `#app` mount point
- **jQuery**: Available globally; used for DataTables, Select2, flatpickr
- **CSRF**: Token in `<meta name="csrf-token">` tag; Axios reads it via `resources/js/bootstrap.js`
- **CSS**: Bootstrap 5.3 via `resources/sass/app.scss`

Build commands:
```bash
npm run dev        # Development build (Vite)
npm run build      # Production build (Vite, minified)
```

Output goes to `public/build/` (Vite manifest-based asset versioning).

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

Default admin credentials (seeded): `admin@admin.com` / `password`

### Sanctum Setup

Sanctum is pre-configured. To issue API tokens use `$user->createToken('token-name')->plainTextToken`. No `php artisan passport:install` needed.

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

**Browser tests** in `tests/Browser/` cover: Events, Meetings, Venues, Users, Roles, Permissions.

**Gap**: Equipment and EquipmentLoan entities do not yet have browser tests.

---

## Key Conventions

### Code Style
- PSR-4 autoloading under `App\` namespace
- Models: singular PascalCase (`Event`, `Venue`, `Equipment`)
- Controllers: plural PascalCase (`EventsController`, `EquipmentController`)
- Database columns: snake_case (`start_time`, `venue_id`, `returned_at`)
- Methods: camelCase; classes: PascalCase
- Run `./vendor/bin/pint` to auto-format (Laravel Pint)

### Flash Messages
User feedback uses session flash messages displayed in views:
```php
Session()->flash('message', 'Evento creado con exito');
Session()->flash('alert-class', 'alert-success');
// alert-class options: alert-success, alert-danger, alert-warning
```

### Soft Deletes
All main tables include `deleted_at`. Do not hard-delete records; use `$model->delete()`. Use `withTrashed()` or `onlyTrashed()` when querying deleted records.

### Mass Assignment
All models define `$fillable` arrays explicitly. Always add new fields to `$fillable` before using `create()` or `fill()`.

### API Resources
Resources in `app/Http/Resources/Admin/` override `toArray(Request $request)` to return specific fields. Do not return raw `parent::toArray()` — define each exposed field explicitly.

### Validation — Known Inconsistency
Most entities use dedicated Form Request classes in `app/Http/Requests/`:
- Pattern: `Store{Entity}Request`, `Update{Entity}Request`, `MassDestroy{Entity}Request`
- Gate authorization check belongs in the `authorize()` method of the request class

**Exception**: `Equipment` and `EquipmentLoan` controllers currently use inline `$request->validate()` instead of dedicated Form Request classes. New entities should follow the Form Request pattern.

---

## Migrations Reference

| Migration | Table | Notes |
|-----------|-------|-------|
| 2014_10_12_100000 | `password_resets` | |
| 2019_11_13_000001 | `permissions` | soft deletes |
| 2019_11_13_000002 | `roles` | soft deletes |
| 2019_11_13_000003 | `users` | soft deletes, email_verified_at |
| 2019_11_13_000004 | `venues` | soft deletes |
| 2019_11_13_000005 | `events` | soft deletes |
| 2019_11_13_000006 | `meetings` | soft deletes (no end_time yet) |
| 2019_11_13_000007 | `permission_role` | pivot |
| 2019_11_13_000008 | `role_user` | pivot |
| 2019_11_13_000009 | `events` | adds `venue_id` FK |
| 2024_01_01_000001 | `meetings` | adds `end_time` column |
| 2024_01_01_000002 | `equipment` | new table; type, code, description; soft deletes |
| 2024_01_01_000003 | `equipment_loans` | new table; FK to equipment, returned_at; soft deletes |

---

## Common Artisan Commands

```bash
php artisan migrate                  # Run new migrations
php artisan migrate:rollback         # Rollback last migration batch
php artisan db:seed                  # Run all seeders
php artisan tinker                   # Interactive REPL
php artisan route:list               # Show all registered routes
php artisan make:model Foo -mcr      # Model + migration + resource controller
php artisan make:request StoreFooRequest
./vendor/bin/pint                    # Auto-format code (Laravel Pint)
```

---

## Error Tracking

Bugsnag is integrated (`bugsnag/bugsnag-laravel ^2.29`). Set `BUGSNAG_API_KEY` in `.env` to enable error reporting in production.
