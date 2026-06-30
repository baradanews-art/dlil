<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan; // ✅ تم نقله إلى الأعلى مع بقية الـ use
use Illuminate\Http\Request;

// ========== مسارات المصادقة ==========
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);
    
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/admin/dashboard');
    }
    
    return back()->withErrors([
        'email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.',
    ]);
})->name('login');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

// ========== Import Controllers ==========
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

// API جلب المناطق (للمستخدمين)
Route::get('/get-regions/{governorateId}', [BusinessController::class, 'getRegions'])
    ->whereNumber('governorateId')
    ->name('api.get-regions');

// ✅ API جلب ID المحافظة من Slug (للبحث)
Route::get('/api/get-governorate-id', function (Request $request) {
    $slug = $request->get('slug');
    $id = $request->get('id');
    
    if ($id) {
        $location = \App\Models\Location::find($id);
        if ($location && $location->parent_id === null) {
            return response()->json(['id' => $location->id]);
        }
        return response()->json(['id' => null]);
    }
    
    if ($slug) {
        $location = \App\Models\Location::where('slug', $slug)->whereNull('parent_id')->first();
        if ($location) {
            return response()->json(['id' => $location->id]);
        }
    }
    return response()->json(['id' => null]);
});

// ✅ API جلب المناطق حسب المحافظة (للواجهة العامة)
Route::get('/api/regions/{governorateId}', function ($governorateId) {
    $regions = \App\Models\Location::where('parent_id', $governorateId)
        ->ordered()
        ->get(['id', 'name', 'slug']);
    return response()->json($regions);
})->whereNumber('governorateId');

// عرض الصور المحمية
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
| مسارات إضافية (API / Health)
|--------------------------------------------------------------------------
*/
Route::get('/health', function () {
    return response()->json(['status' => 'healthy', 'timestamp' => now()]);
});

/*
|--------------------------------------------------------------------------
| Sitemap & Robots
|--------------------------------------------------------------------------
*/
Route::get('/sitemap.xml', [HomeController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', function () {
    return response("User-agent: *\nAllow: /\nSitemap: " . url('/sitemap.xml'), 200)
        ->header('Content-Type', 'text/plain');
});

/*
|--------------------------------------------------------------------------
| Clear Cache (للمطورين - يمكن حذفه لاحقاً)
|--------------------------------------------------------------------------
*/
Route::get('/clear-cache', function () {
    \App\Http\Controllers\Controller::clearAllCache();
    return '✅ تم مسح الكاش بالكامل بنجاح! <br><a href="' . url('/') . '">العودة للرئيسية</a>';
});

Route::get('/clear-everything', function () {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    return "✅ تم تنظيف كاش الموقع بالكامل بنجاح!";
});

/*
|--------------------------------------------------------------------------
| لوحة التحكم الإدارية (Admin Panel)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // ========== إدارة المنشآت التجارية ==========
    // ✅ استخدام resource مع استثناء show و create (لأن create له مسار منفصل)
    Route::resource('businesses', AdminBusinessController::class)->except(['show']);
    
    // ✅ مسارات التصدير والاستيراد (يجب أن تكون قبل resource أو بعدها مع تحديد المسار الكامل)
    Route::get('businesses/export', [AdminBusinessController::class, 'export'])->name('businesses.export');
    Route::post('businesses/import', [AdminBusinessController::class, 'import'])->name('businesses.import');
    
    // إدارة التصنيفات
    Route::resource('categories', CategoryController::class)->except(['show']);
    
    // إدارة المواقع الجغرافية
    Route::resource('locations', LocationController::class)->except(['show']);
    
    // إدارة التقييمات
    Route::resource('reviews', AdminReviewController::class)->only(['index', 'edit', 'update', 'destroy']);
    
    // إدارة الإعلانات
    Route::resource('ads', AdController::class)->except(['edit', 'update', 'show']);
    
    // ========== إدارة المؤسسات الرسمية ==========
    Route::prefix('official')->name('official.')->group(function () {
        Route::get('/', [AdminOfficialEntityController::class, 'index'])->name('index');
        Route::get('/create', [AdminOfficialEntityController::class, 'create'])->name('create');
        Route::post('/', [AdminOfficialEntityController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AdminOfficialEntityController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminOfficialEntityController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminOfficialEntityController::class, 'destroy'])->name('destroy');
        
        // ✅ تصدير واستيراد البيانات للمؤسسات الرسمية
        Route::get('/export', [AdminOfficialEntityController::class, 'export'])->name('export');
        Route::post('/import', [AdminOfficialEntityController::class, 'import'])->name('import');
    });
    
    // API جلب المناطق للمؤسسات الرسمية (في لوحة التحكم)
    Route::get('/get-regions/{cityId}', [AdminOfficialEntityController::class, 'getRegions'])->name('admin.get-regions');
    
    // مسح الكاش من لوحة التحكم
    Route::post('/clear-cache', [DashboardController::class, 'clearCache'])->name('clear-cache');
});

/*
|--------------------------------------------------------------------------
| PWA Setup (تم تثبيته مؤقتاً)
|--------------------------------------------------------------------------
*/
Route::get('/setup-pwa', function () {
    Artisan::call('erag:install-pwa');
    return 'تم تثبيت ملفات PWA بنجاح!';
});