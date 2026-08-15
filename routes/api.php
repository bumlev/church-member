<?php

use App\Http\Controllers\Api\AdminUserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CelluleController;
use App\Http\Controllers\Api\DistrictController;
use App\Http\Controllers\Api\FamilyController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\ProvinceController;
use App\Http\Controllers\Api\SectorController;
use App\Http\Controllers\Api\VillageController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // ── Authentication ──────────────────────────────────────────────────────
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:login');
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('password/forgot', [PasswordResetController::class, 'forgot'])->middleware('throttle:login');
    Route::post('password/reset', [PasswordResetController::class, 'reset']);
    Route::post('password/change', [AuthController::class, 'updatePassword'])->middleware('auth:sanctum');

    Route::middleware('auth:sanctum')->group(function () {
        // ── Geographic ──────────────────────────────────────────────────────
        Route::get('provinces',             [ProvinceController::class, 'index']);
        Route::get('/{province}/districts', [DistrictController::class, 'index']);
        Route::get('/{district}/sectors',   [SectorController::class,   'index']);
        Route::get('/{sector}/cellules',    [CelluleController::class,  'index']);
        Route::get('/{cellule}/villages',   [VillageController::class,  'index']);


        Route::prefix('members')->group(function () {
            // ── Live duplicate check (first_name + last_name + date_birthday) ──
            Route::get('exists', [MemberController::class, 'exists']);

            // ── Member form data (lookup dropdowns) ─────────────────────────
            Route::get('occupations',    [MemberController::class, 'occupations']);
            Route::get('talents',        [MemberController::class, 'talents']);
            Route::get('spiritual-gifts', [MemberController::class, 'spiritualGifts']);
            Route::get('educations',     [MemberController::class, 'educations']);
            Route::get('departments',    [MemberController::class, 'departments']);
            Route::get('educations/{education}/faculties', [MemberController::class, 'faculties']);
            Route::get('departments/{department}/church-responsibilities', [MemberController::class, 'churchResponsibilities']);
        });

        // ── Member resource (index, store, show, update) ───────────────────
        Route::apiResource('members', MemberController::class)->except('destroy');

        Route::prefix('families')->group(function () {
            // ── Family membership (add/remove a member from a family) ──────
            Route::post('{family}/members', [FamilyController::class, 'addMember']);
            Route::delete('{family}/members/{member}', [FamilyController::class, 'removeMember']);
        });

        // ── Family resource (index, store, show, update, destroy) ──────────
        Route::apiResource('families', FamilyController::class);
    });

    // ── Admin: user management ─────────────────────────────────────────────────
    Route::prefix('admin')
        ->middleware(['auth:sanctum', 'can:viewAny,App\Models\User'])
        ->group(function () {
            Route::apiResource('users', AdminUserController::class);
            Route::patch('users/{user}/role', [AdminUserController::class, 'updateRole']);
        });
});
