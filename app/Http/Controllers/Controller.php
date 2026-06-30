<?php

namespace App\Http\Controllers;

use App\Helpers\SeoHelper;
use Illuminate\Support\Facades\Cache;

abstract class Controller
{
    /**
     * إنشاء كائن SEO للصفحة الحالية
     */
    protected function seo(): SeoHelper
    {
        return new SeoHelper();
    }
    
    /**
     * مسح جميع أنواع الكاش (يمكن استدعاؤها عبر route)
     */
    public static function clearAllCache(): void
    {
        // مسح كاش Laravel
        Cache::flush();
        
        // مسح كاش views
        $viewCachePath = storage_path('framework/views');
        if (is_dir($viewCachePath)) {
            array_map('unlink', glob($viewCachePath . '/*.php'));
        }
        
        // إعادة تعيين opcache إذا كان متاحاً
        if (function_exists('opcache_reset')) {
            opcache_reset();
        }
    }
}