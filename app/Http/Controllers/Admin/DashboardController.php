<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Category;
use App\Models\Location;
use App\Models\Review;
use App\Models\Ad;
use App\Models\OfficialEntity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    /**
     * ✅ عرض لوحة التحكم الرئيسية مع إحصائيات محسنة
     */
    public function index(Request $request)
    {
        // ✅ استخدام التخزين المؤقت للإحصائيات
        $stats = Cache::remember('admin_dashboard_stats', now()->addMinutes(30), function () {
            return [
                'total' => Business::count(),
                'approved' => Business::approved()->count(),
                'pending' => Business::pending()->count(),
                'categories' => Category::count(),
                'cities' => Location::governorates()->count(),
                'locations' => Location::count(),
                'reviews' => Review::count(),
                'pending_reviews' => Review::where('is_approved', false)->count(),
                'avg_rating' => round(Review::avg('rating') ?? 0, 1),
                'ads' => Ad::count(),
                'active_ads' => Ad::active()->count(),
                'official_entities' => OfficialEntity::count(),
                'verified_businesses' => Business::verified()->count(),
                'official_businesses' => Business::official()->count(),
                'delivery_available' => Business::deliveryAvailable()->count(),
            ];
        });
        
        // ✅ المنشآت المعلقة (بدون تخزين مؤقت لأنها تتغير كثيراً)
        $pendingBusinesses = Business::pending()
            ->with(['category', 'location'])
            ->latest()
            ->paginate(10);
        
        // ✅ أحدث التقييمات
        $latestReviews = Review::with('business')
            ->latest()
            ->take(10)
            ->get();
        
        // ✅ أحدث المنشآت
        $latestBusinesses = Business::with(['category', 'location'])
            ->latest()
            ->take(10)
            ->get();
        
        return view('admin-dashboard', compact(
            'stats', 'pendingBusinesses', 'latestReviews', 'latestBusinesses'
        ));
    }
    
    /**
     * ✅ مسح التخزين المؤقت من لوحة التحكم
     */
    public function clearCache()
    {
        Cache::flush();
        
        return redirect()->back()
            ->with('success', '✅ تم مسح التخزين المؤقت بنجاح.');
    }
}