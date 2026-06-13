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
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * ✅ عرض الصفحة الرئيسية مع دعم التخزين المؤقت
     */
    public function index(Request $request)
    {
        // ✅ استخدام التخزين المؤقت لتحسين الأداء
        $cacheKey = 'home_page_' . md5($request->fullUrl());
        
        $data = Cache::remember($cacheKey, now()->addMinutes(15), function () use ($request) {
            $categorySlug = $request->query('category');
            $locationSlug = $request->query('location');
            $searchQuery = $request->query('search');
            $selectedCategory = null;
            
            // ✅ بناء الاستعلام مع with() لتجنب N+1
            $businessesQuery = Business::approved()
                ->with(['category', 'location'])
                ->withAvg('reviews', 'rating');
            
            // ✅ فلترة حسب البحث
            if ($searchQuery) {
                $businessesQuery->search($searchQuery);
            }
            
            // ✅ فلترة حسب التصنيف
            if ($categorySlug) {
                $selectedCategory = Category::where('slug', $categorySlug)->first();
                if ($selectedCategory) {
                    $businessesQuery->where('category_id', $selectedCategory->id);
                }
            }
            
            // ✅ فلترة حسب الموقع
            if ($locationSlug) {
                $location = Location::where('slug', $locationSlug)->first();
                if ($location) {
                    $businessesQuery->where('location_id', $location->id);
                }
            }
            
            // ✅ التصنيفات مع عدد المنشآت
            $categories = Category::withBusinessCount()->ordered()->get();
            
            // ✅ إحصائيات الموقع
            $stats = [
                'total' => Business::approved()->count(),
                'pending' => Business::pending()->count(),
                'categories' => Category::count(),
                'cities' => Location::governorates()->count(),
                'locations' => Location::count(),
                'reviews' => Review::count(),
                'avg_rating' => round(Review::avg('rating') ?? 0, 1),
                'verified_businesses' => Business::approved()->verified()->count(),
                'official_businesses' => Business::approved()->official()->count(),
            ];
            
            // ✅ المنشآت الأكثر تقييماً
            $topRatedBusinesses = Business::approved()
                ->with(['category', 'location'])
                ->withAvg('reviews', 'rating')
                ->having('reviews_avg_rating', '>=', 4)
                ->latest()
                ->take(6)
                ->get();
            
            // ✅ أحدث التقييمات
            $latestReviews = Review::with('business')
                ->latest()
                ->take(5)
                ->get();
            
            // ✅ إعدادات الموقع
            $settings = Setting::getMultiple([
                'site_name', 'site_description', 'footer_text', 'contact_email'
            ]);
            
            return [
                'featuredBusinesses' => $businessesQuery->latest()->paginate(12),
                'categories' => $categories,
                'selectedCategory' => $selectedCategory,
                'searchQuery' => $searchQuery,
                'stats' => $stats,
                'topRatedBusinesses' => $topRatedBusinesses,
                'latestReviews' => $latestReviews,
                'siteName' => $settings['site_name'] ?? 'دليل سوريا التجاري',
                'siteDescription' => $settings['site_description'] ?? 'دليلك الشامل للأعمال في سوريا',
                'footerText' => $settings['footer_text'] ?? 'جميع الحقوق محفوظة',
                'contactEmail' => $settings['contact_email'] ?? 'info@example.com',
            ];
        });
        
        return view('index', $data);
    }
    
    /**
     * ✅ صفحة البحث المتقدم
     */
    public function search(Request $request)
    {
        $query = $request->get('search');
        $locationSlug = $request->get('location');
        $categorySlug = $request->get('category');
        $sort = $request->get('sort', 'latest');
        $deliveryOnly = $request->boolean('delivery');
        
        $businessesQuery = Business::approved()
            ->with(['category', 'location.parent'])
            ->withAvg('reviews', 'rating');
        
        // ✅ البحث النصي
        if ($query) {
            $businessesQuery->search($query);
        }
        
        // ✅ فلترة الموقع
        if ($locationSlug) {
            $location = Location::where('slug', $locationSlug)->first();
            if ($location) {
                $businessesQuery->where('location_id', $location->id);
            }
        }
        
        // ✅ فلترة التصنيف
        if ($categorySlug) {
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                $businessesQuery->where('category_id', $category->id);
            }
        }
        
        // ✅ فلترة التوصيل
        if ($deliveryOnly) {
            $businessesQuery->deliveryAvailable();
        }
        
        // ✅ ترتيب النتائج
        $businessesQuery = match ($sort) {
            'rating' => $businessesQuery->orderBy('reviews_avg_rating', 'desc'),
            'oldest' => $businessesQuery->oldest(),
            default => $businessesQuery->latest(),
        };
        
        $businesses = $businessesQuery->paginate(12);
        
        // ✅ بيانات الفلترة
        $governorates = Location::governorates()->ordered()->get();
        $categories = Category::ordered()->get();
        
        return view('search', compact('businesses', 'query', 'governorates', 'categories'));
    }
    
    /**
     * ✅ عرض الصور بأمان (بدون الوصول المباشر للمجلدات)
     */
    public function showImage($folder, $filename)
    {
        $path = public_path("uploads/{$folder}/{$filename}");
        
        if (!file_exists($path) || !is_file($path)) {
            abort(404);
        }
        
        // ✅ التحقق من نوع الملف
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        
        if (!in_array($extension, $allowedExtensions)) {
            abort(404);
        }
        
        return response()->file($path, [
            'Cache-Control' => 'public, max-age=31536000',
            'Content-Type' => mime_content_type($path),
        ]);
    }
    
    /**
     * ✅ خريطة الموقع (Sitemap) لتحسين SEO
     */
    public function sitemap()
    {
        $cacheKey = 'sitemap_xml';
        
        $xml = Cache::remember($cacheKey, now()->addHours(24), function () {
            $businesses = Business::approved()->with(['category', 'location'])->get();
            $categories = Category::all();
            $locations = Location::all();
            $officialEntities = OfficialEntity::active()->get();
            
            return view('sitemap', compact('businesses', 'categories', 'locations', 'officialEntities'))->render();
        });
        
        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
    
    /**
     * ✅ مسح التخزين المؤقت (للإدارة)
     */
    public static function clearCache(): void
    {
        Cache::flush();
    }
}