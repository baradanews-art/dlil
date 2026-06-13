<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BusinessController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\OfficialEntityController;

/*
|--------------------------------------------------------------------------
| API Routes - للإصدارات المستقبلية وتطبيقات الجوال
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    
    // ✅ مسارات عامة (بدون مصادقة)
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{slug}', [CategoryController::class, 'show']);
    
    Route::get('/locations', [LocationController::class, 'index']);
    Route::get('/locations/{slug}', [LocationController::class, 'show']);
    
    Route::get('/businesses', [BusinessController::class, 'index']);
    Route::get('/businesses/{slug}', [BusinessController::class, 'show']);
    Route::get('/businesses/category/{categorySlug}', [BusinessController::class, 'byCategory']);
    Route::get('/businesses/location/{locationSlug}', [BusinessController::class, 'byLocation']);
    
    Route::get('/official', [OfficialEntityController::class, 'index']);
    Route::get('/official/{slug}', [OfficialEntityController::class, 'show']);
    
    // ✅ البحث
    Route::get('/search', [BusinessController::class, 'search']);
    
    // ✅ إذا أردت مصادقة API في المستقبل
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/businesses', [BusinessController::class, 'store']);
        Route::post('/businesses/{business}/reviews', [BusinessController::class, 'addReview']);
        // ... إلخ
    });
});