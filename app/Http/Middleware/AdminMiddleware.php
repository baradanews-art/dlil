<?php
// ملف: AdminMiddleware.php
// المسار: app/Http/Middleware/AdminMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // التحقق من أن المستخدم مسجل الدخول وإما user_type = 'admin'
        // يمكنك تعديل الشرط حسب هيكل جدول users لديك
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // مثال: إذا كان لديك حقل is_admin في جدول users
        if (!Auth::user()->is_admin) {
            abort(403, 'غير مصرح لك بالدخول إلى لوحة التحكم.');
        }

        return $next($request);
    }
}