<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Ad;
use App\Models\Category;
use App\Models\Location;
use App\Models\Review;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * عرض الصفحة الرئيسية مع دعم التصفية حسب التصنيف والبحث
     */
    public function index(Request $request)
    {
        // جلب معامل التصنيف من الرابط (إن وجد)
        $categorySlug = $request->query('category');
        $locationSlug = $request->query('location');
        $searchQuery = $request->query('search');
        $selectedCategory = null;
        
        // بناء الاستعلام الأساسي للمنشآت
        $businessesQuery = Business::where('is_approved', 1)
            ->with(['category', 'location'])
            ->withAvg('reviews', 'rating');
        
        // فلترة حسب البحث النصي
        if ($searchQuery) {
            $businessesQuery->where(function($q) use ($searchQuery) {
                $q->where('title', 'LIKE', "%{$searchQuery}%")
                  ->orWhere('description', 'LIKE', "%{$searchQuery}%")
                  ->orWhere('phone', 'LIKE', "%{$searchQuery}%")
                  ->orWhere('address_detail', 'LIKE', "%{$searchQuery}%");
            });
        }
        
        // فلترة حسب التصنيف
        if ($categorySlug) {
            $selectedCategory = Category::where('slug', $categorySlug)->first();
            if ($selectedCategory) {
                $businessesQuery->where('category_id', $selectedCategory->id);
            }
        }
        
        // فلترة حسب الموقع
        if ($locationSlug) {
            $location = Location::where('slug', $locationSlug)->first();
            if ($location) {
                $businessesQuery->where('location_id', $location->id);
            }
        }
        
        // جلب المنشآت مع Pagination
        $featuredBusinesses = $businessesQuery->latest()->paginate(12);
        
        // جلب الإعلانات (يمكن تفعيلها لاحقاً)
        $sidebarAds = collect(); // Ad::where('is_active', 1)->where('position', 'sidebar')->get();
        $topAds = collect(); // Ad::where('is_active', 1)->where('position', 'home_top')->get();
        
        // جلب جميع التصنيفات مع عدد المنشآت في كل تصنيف
        $categories = Category::withCount(['businesses' => function($query) {
            $query->where('is_approved', 1);
        }])->orderBy('name')->get();
        
        // ============================================================
        // 📊 إحصائيات الموقع
        // ============================================================
        $stats = [
            'total' => Business::where('is_approved', 1)->count(),
            'pending' => Business::where('is_approved', 0)->count(),
            'categories' => Category::count(),
            'cities' => Location::whereNull('parent_id')->count(),
            'locations' => Location::count(),
            'reviews' => Review::count(),
            'verified_businesses' => Business::where('is_approved', 1)
                ->where('verification_type', 'verified')
                ->count(),
            'official_businesses' => Business::where('is_approved', 1)
                ->where('verification_type', 'official')
                ->count(),
        ];
        
        // ============================================================
        // 🏆 المنشآت الأكثر تقييماً
        // ============================================================
        $topRatedBusinesses = Business::where('is_approved', 1)
            ->with(['category', 'location'])
            ->withAvg('reviews', 'rating')
            ->having('reviews_avg_rating', '>=', 4)
            ->latest()
            ->take(6)
            ->get();
        
        // ============================================================
        // 🆕 أحدث التقييمات
        // ============================================================
        $latestReviews = Review::with('business')
            ->latest()
            ->take(5)
            ->get();
        
        // ============================================================
        // إعدادات الموقع
        // ============================================================
        $siteName = Setting::get('site_name', 'دليل سوريا التجاري');
        $siteDescription = Setting::get('site_description', 'دليلك الشامل للأعمال في سوريا');
        $footerText = Setting::get('footer_text', 'جميع الحقوق محفوظة');
        $contactEmail = Setting::get('contact_email', 'info@example.com');
        
        // ============================================================
        // عرض الصفحة
        // ============================================================
        return view('index', compact(
            'featuredBusinesses',
            'sidebarAds',
            'topAds',
            'categories',
            'selectedCategory',
            'searchQuery',
            'stats',
            'topRatedBusinesses',
            'latestReviews',
            'siteName',
            'siteDescription',
            'footerText',
            'contactEmail'
        ));
    }

    /**
     * صفحة البحث المتقدم
     */
    public function search(Request $request)
    {
        $query = $request->get('search');
        $locationSlug = $request->get('location');
        $categorySlug = $request->get('category');
        $sort = $request->get('sort', 'latest');
        $deliveryOnly = $request->get('delivery', false);
        
        // بناء استعلام البحث
        $businessesQuery = Business::where('is_approved', 1)
            ->with(['category', 'location.parent']);
        
        // البحث النصي (في الاسم والوصف والعنوان والهاتف)
        if ($query) {
            $businessesQuery->where(function($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhere('address_detail', 'LIKE', "%{$query}%")
                  ->orWhere('phone', 'LIKE', "%{$query}%");
            });
        }
        
        // فلترة حسب الموقع
        if ($locationSlug) {
            $location = Location::where('slug', $locationSlug)->first();
            if ($location) {
                $businessesQuery->where('location_id', $location->id);
            }
        }
        
        // فلترة حسب التصنيف
        if ($categorySlug) {
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                $businessesQuery->where('category_id', $category->id);
            }
        }
        
        // فلترة التوصيل
        if ($deliveryOnly) {
            $businessesQuery->where('delivery_available', 1);
        }
        
        // ترتيب النتائج
        switch ($sort) {
            case 'rating':
                $businessesQuery->withAvg('reviews', 'rating')->orderBy('reviews_avg_rating', 'desc');
                break;
            case 'oldest':
                $businessesQuery->oldest();
                break;
            default:
                $businessesQuery->latest();
                break;
        }
        
        $businesses = $businessesQuery->paginate(12);
        
        // جلب البيانات للفلترة
        $governorates = Location::whereNull('parent_id')->get();
        $categories = Category::orderBy('name')->get();
        
        return view('search', compact('businesses', 'query', 'governorates', 'categories'));
    }

    /**
     * مسح Cache الموقع
     */
    public static function clearCache()
    {
        Cache::forget('featured_businesses');
        Cache::forget('sidebar_ads');
        Cache::forget('top_ads');
        Cache::forget('categories_with_count');
        Cache::forget('top_rated_businesses');
        Cache::forget('latest_reviews');
        
        return true;
    }
}