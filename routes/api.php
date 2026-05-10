<?php

use App\Http\Controllers\Api\CelluleController;
use App\Http\Controllers\Api\DistrictController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\ProvinceController;
use App\Http\Controllers\Api\SectorController;
use App\Http\Controllers\Api\VillageController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // ── Geographic ──────────────────────────────────────────────────────────
    Route::get('provinces',             [ProvinceController::class, 'index']);
    Route::get('/{province}/districts', [DistrictController::class, 'index']);
    Route::get('/{district}/sectors',   [SectorController::class,   'index']);
    Route::get('/{sector}/cellules',    [CelluleController::class,  'index']);
    Route::get('/{cellule}/villages',   [VillageController::class,  'index']);

    // ── Member form data (lookup dropdowns) ─────────────────────────────────
    Route::prefix('members')->group(function () {
        Route::get('occupations', [MemberController::class, 'occupations']);
        Route::get('educations',  [MemberController::class, 'educations']);
        Route::get('departments', [MemberController::class, 'departments']);
    });
});









