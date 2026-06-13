<?php


// ========== مسارات المصادقة ==========
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);
    
    if (Illuminate\Support\Facades\Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/admin/dashboard');
    }
    
    return back()->withErrors([
        'email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.',
    ]);
})->name('login');

Route::post('/logout', function () {
    Illuminate\Support\Facades\Auth::logout();
    return redirect('/');
})->name('logout');

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\OfficialEntityController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BusinessController as AdminBusinessController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\AdController;
use App\Http\Controllers\Admin\OfficialEntityController as AdminOfficialEntityController;

/*
|--------------------------------------------------------------------------
| مسارات عامة (Public Routes)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search');

Route::get('/get-regions/{governorateId}', [BusinessController::class, 'getRegions'])
    ->whereNumber('governorateId')
    ->name('api.get-regions');

Route::get('/image/{folder}/{filename}', function ($folder, $filename) {
    $path = public_path("uploads/{$folder}/{$filename}");
    if (file_exists($path) && is_file($path)) {
        return response()->file($path);
    }
    abort(404);
})->where('filename', '.*')->name('image.show');

/*
|--------------------------------------------------------------------------
| مسارات المؤسسات الرسمية (Public)
|--------------------------------------------------------------------------
*/
Route::prefix('official')->name('official.')->group(function () {
    Route::get('/government', [OfficialEntityController::class, 'government'])->name('government');
    Route::get('/security', [OfficialEntityController::class, 'security'])->name('security');
    Route::get('/help-centers', [OfficialEntityController::class, 'help'])->name('help');
    Route::get('/{slug}', [OfficialEntityController::class, 'show'])->name('show');
    Route::get('/get-regions/{cityId}', [OfficialEntityController::class, 'getRegions'])->name('get-regions');
});

/*
|--------------------------------------------------------------------------
| مسارات المنشآت التجارية (Public)
|--------------------------------------------------------------------------
*/
Route::prefix('business')->name('business.')->group(function () {
    Route::get('/add', [BusinessController::class, 'create'])->name('create');
    Route::post('/add', [BusinessController::class, 'store'])->name('store');
    Route::get('/{slug}', [BusinessController::class, 'show'])->name('show');
    Route::post('/{business}/review', [ReviewController::class, 'store'])->name('review.store');
});

/*
|--------------------------------------------------------------------------
| مسارات إضافية
|--------------------------------------------------------------------------
*/
Route::get('/health', function () {
    return response()->json(['status' => 'healthy']);
});

Route::post('/logout', function () {
    auth()->logout();
    return redirect('/');
})->name('logout');

/*
|--------------------------------------------------------------------------
| لوحة التحكم الإدارية
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('businesses', AdminBusinessController::class)->except(['show']);
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('locations', LocationController::class)->except(['show']);
    Route::resource('reviews', AdminReviewController::class)->only(['index', 'edit', 'update', 'destroy']);
    Route::resource('ads', AdController::class)->except(['edit', 'update', 'show']);
    
    Route::prefix('official')->name('official.')->group(function () {
        Route::get('/', [AdminOfficialEntityController::class, 'index'])->name('index');
        Route::get('/create', [AdminOfficialEntityController::class, 'create'])->name('create');
        Route::post('/', [AdminOfficialEntityController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AdminOfficialEntityController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminOfficialEntityController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminOfficialEntityController::class, 'destroy'])->name('destroy');
    });
    
    Route::get('/get-regions/{cityId}', [AdminOfficialEntityController::class, 'getRegions'])->name('get-regions');
    Route::post('/clear-cache', [DashboardController::class, 'clearCache'])->name('clear-cache');
});