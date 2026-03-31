# Copilot Instructions

## Project

**Calendary** — Laravel 11 calendar app with multi-source scheduling (Events, Meetings, Equipment Loans), venue/establishment management, role-based access control, and a REST API. UI is bilingual; Spanish is the primary language.

**Stack**: PHP 8.2+, Laravel 11, MySQL, Bootstrap 5, jQuery 3, flatpickr, select2, Vite, PHPUnit 11 + Mockery + Laravel Dusk 8.

---

## Commands

```bash
# Build
npm run dev          # Vite dev server
npm run build        # Production build

# Tests
php artisan test                           # Full suite
./vendor/bin/phpunit --filter TestName     # Single test
php artisan dusk                           # Browser tests (needs server + chromedriver)

# Linting (Laravel Pint)
./vendor/bin/pint                          # Fix all files
./vendor/bin/pint app/Models/Foo.php       # Fix one file
```

---

## Architecture

### Routing layers
- **Web** (`routes/web.php`): all admin routes under `/admin/` prefix, `auth` middleware, named `admin.*`
- **API** (`routes/api.php`): versioned under `/api/v1/`, `auth:sanctum` middleware, named `api.*`
- **Public**: `GET /Calendary` → `CalendarController@index` (no auth)
- Every resource has an extra `DELETE /{resource}/destroy` → `massDestroy` route

### Calendar architecture (`SystemCalendarController`)
The admin calendar aggregates three models into one FullCalendar feed:

| Source | Color | FullCalendar ID prefix |
|--------|-------|------------------------|
| Event | `#3788d8` | `event_` |
| Meeting | `#28a745` | `meeting_` |
| EquipmentLoan | `#fd7e14` | `loan_` |

- `GET admin/calendar-events` — AJAX JSON; accepts `venue_id[]` and `type[]` query params
- `POST admin/calendar-events` — create from calendar
- `PATCH admin/calendar-events/{id}` — drag-drop update; strips the prefix to get the real DB id

`store()` and `update()` use raw `DB::table()` inserts/updates to **bypass Carbon accessor mutators**.

### Authorization system (`AuthGates` middleware)
Permissions are loaded on every request and registered as Gates dynamically:
- Pattern: `{model}_{action}` — e.g. `event_create`, `venue_edit`, `establishment_access`
- Controllers guard with: `abort_if(Gate::denies('event_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');`
- Gate `authorize()` belongs in Form Request classes, **not** in controllers

### API HTTP status codes
`201` store · `202` update · `204` destroy

---

## Key Conventions

### Carbon mutators & raw DB values
All time-bearing models (Event, Meeting, EquipmentLoan) have `getStartTimeAttribute` / `setStartTimeAttribute` mutators that convert between `Y-m-d H:i:s` (DB) and the display format from `config('panel.date_format') . ' ' . config('panel.time_format')`.

**When you need the raw DB value** (e.g. for FullCalendar ISO strings), use:
```php
$model->getOriginal('start_time')
```
Never read `$model->start_time` for FullCalendar output.

### Flash messages — Spanish, direct strings
```php
Session()->flash('message', 'Registro creado con éxito');
Session()->flash('alert-class', 'alert-success'); // alert-success | alert-danger | alert-warning
```
Flash messages are written directly in Spanish — **not** via translation keys.

### Form Requests
Validation lives in `app/Http/Requests/` (`Store*Request`, `Update*Request`, `MassDestroy*Request`).  
Date field validation rule must match the panel format:
```php
'start_time' => ['date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'), 'nullable'],
```

### Models
- All main tables use **soft deletes** — never hard-delete, always `$model->delete()`
- All models define explicit `$fillable` — add new fields there before using `create()` or `fill()`

### API Resources
Located in `app/Http/Resources/Admin/`. Override `toArray(Request $request)` calling `parent::toArray($request)` to customise output.

### Conflict detection (Events)
`EventsController::store()` checks for venue overlaps before saving:
```php
Event::where('venue_id', $venueId)
    ->where('start_time', '<', $endTime)
    ->where('end_time', '>', $startTime)
    ->exists();
```
Apply the same pattern when adding any bookable resource.
