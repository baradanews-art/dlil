<?php

use App\Models\Setting;

if (!function_exists('setting')) {
    /**
     * الحصول على قيمة إعداد معين من قاعدة البيانات
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }
}