<?php

use App\Http\Controllers\Api\V1\Admin\EventsApiController;
use App\Http\Controllers\Api\V1\Admin\EquipmentApiController;
use App\Http\Controllers\Api\V1\Admin\EquipmentLoansApiController;
use App\Http\Controllers\Api\V1\Admin\MeetingsApiController;
use App\Http\Controllers\Api\V1\Admin\PermissionsApiController;
use App\Http\Controllers\Api\V1\Admin\RolesApiController;
use App\Http\Controllers\Api\V1\Admin\UsersApiController;
use App\Http\Controllers\Api\V1\Admin\VenuesApiController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'as' => 'api.', 'middleware' => ['auth:sanctum']], function () {
    Route::apiResource('permissions', PermissionsApiController::class);
    Route::apiResource('roles', RolesApiController::class);
    Route::apiResource('users', UsersApiController::class);
    Route::apiResource('venues', VenuesApiController::class);
    Route::apiResource('events', EventsApiController::class);
    Route::apiResource('meetings', MeetingsApiController::class);
    Route::apiResource('equipment', EquipmentApiController::class);
    Route::apiResource('equipment-loans', EquipmentLoansApiController::class);
});
