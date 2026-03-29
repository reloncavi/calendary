<?php

use App\Http\Controllers\Admin\EquipmentController;
use App\Http\Controllers\Admin\EquipmentLoansController;
use App\Http\Controllers\Admin\EventsController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\MeetingsController;
use App\Http\Controllers\Admin\PermissionsController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\SystemCalendarController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\VenuesController;
use App\Http\Controllers\CalendarController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/Calendary');

Route::get('/home', function () {
    return redirect()->route('admin.systemCalendar');
});

Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth']], function () {
    // Permissions
    Route::delete('permissions/destroy', [PermissionsController::class, 'massDestroy'])->name('permissions.massDestroy');
    Route::resource('permissions', PermissionsController::class);

    // Roles
    Route::delete('roles/destroy', [RolesController::class, 'massDestroy'])->name('roles.massDestroy');
    Route::resource('roles', RolesController::class);

    // Users
    Route::delete('users/destroy', [UsersController::class, 'massDestroy'])->name('users.massDestroy');
    Route::resource('users', UsersController::class);

    // Venues
    Route::delete('venues/destroy', [VenuesController::class, 'massDestroy'])->name('venues.massDestroy');
    Route::resource('venues', VenuesController::class);

    // Events
    Route::delete('events/destroy', [EventsController::class, 'massDestroy'])->name('events.massDestroy');
    Route::resource('events', EventsController::class);

    // Meetings
    Route::delete('meetings/destroy', [MeetingsController::class, 'massDestroy'])->name('meetings.massDestroy');
    Route::resource('meetings', MeetingsController::class);

    // Equipment
    Route::delete('equipment/destroy', [EquipmentController::class, 'massDestroy'])->name('equipment.massDestroy');
    Route::resource('equipment', EquipmentController::class);

    // Equipment Loans
    Route::delete('equipment-loans/destroy', [EquipmentLoansController::class, 'massDestroy'])->name('equipment-loans.massDestroy');
    Route::patch('equipment-loans/{equipmentLoan}/return', [EquipmentLoansController::class, 'markReturned'])->name('equipment-loans.return');
    Route::resource('equipment-loans', EquipmentLoansController::class);

    // System Calendar
    Route::get('system-calendar', [SystemCalendarController::class, 'index'])->name('systemCalendar');
});

Route::get('Calendary', [CalendarController::class, 'index'])->name('Calendary');
