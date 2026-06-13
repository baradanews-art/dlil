<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $guarded = [];
    
    protected $casts = [
        'value' => 'json',
    ];
    
    // ============================================================
    // ✅ Cache TTL (دقيقة واحدة - يمكن تعديلها حسب الحاجة)
    // ============================================================
    const CACHE_TTL = 3600; // ساعة واحدة
    
    // ============================================================
    // ✅ الحصول على إعداد معين
    // ============================================================
    public static function get(string $key, $default = null)
    {
        return Cache::remember("setting_{$key}", self::CACHE_TTL, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }
    
    // ============================================================
    // ✅ تعيين إعداد معين
    // ============================================================
    public static function set(string $key, $value): self
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
        
        Cache::forget("setting_{$key}");
        
        return $setting;
    }
    
    // ============================================================
    // ✅ حذف إعداد معين
    // ============================================================
    public static function remove(string $key): bool
    {
        Cache::forget("setting_{$key}");
        return self::where('key', $key)->delete();
    }
    
    // ============================================================
    // ✅ الحصول على إعدادات متعددة دفعة واحدة
    // ============================================================
    public static function getMultiple(array $keys, $default = null): array
    {
        $results = [];
        foreach ($keys as $key) {
            $results[$key] = self::get($key, $default);
        }
        return $results;
    }
    
    // ============================================================
    // ✅ مسح جميع الإعدادات من التخزين المؤقت
    // ============================================================
    public static function clearCache(): void
    {
        $keys = self::pluck('key');
        foreach ($keys as $key) {
            Cache::forget("setting_{$key}");
        }
    }
}