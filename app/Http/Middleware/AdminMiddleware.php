<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // ✅ التحقق من تسجيل الدخول
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }
            return redirect('/');
        }
        
        // ✅ التحقق من صلاحية المدير
        $user = Auth::user();
        
        if (isset($user->is_admin) && $user->is_admin == true) {
            return $next($request);
        }
        
        if (isset($user->role) && in_array($user->role, ['admin', 'super_admin'])) {
            return $next($request);
        }
        
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Forbidden - Admin access required'], 403);
        }
        
        abort(403, 'غير مصرح لك بالدخول إلى لوحة التحكم.');
    }
}