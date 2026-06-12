<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $guarded = [];

    // دالة استدعاء سريعة وذكية لجلب أي إعداد بمرونة كاملة مع حماية من الأخطاء
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return ($setting && !empty($setting->value)) ? $setting->value : $default;
    }
}