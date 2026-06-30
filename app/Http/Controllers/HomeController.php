<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
use App\Models\Location;
use App\Models\Review;
use App\Models\OfficialEntity;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * الصفحة الرئيسية
     */
    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $cacheKey = 'home_page_v6_' . $page . '_' . md5($request->fullUrl());

        $data = Cache::remember($cacheKey, now()->addMinutes(15), function () use ($request) {
            $categorySlug = $request->query('category');
            $locationSlug = $request->query('location');
            $searchQuery = $request->query('search');
            $selectedCategory = null;

            $businessesQuery = Business::approved()
                ->with(['category', 'governorate', 'region'])
                ->withAvg('reviews', 'rating');

            if ($searchQuery) {
                $businessesQuery->where(function ($q) use ($searchQuery) {
                    $q->where('title', 'LIKE', "%{$searchQuery}%")
                      ->orWhere('description', 'LIKE', "%{$searchQuery}%")
                      ->orWhere('phone', 'LIKE', "%{$searchQuery}%");
                });
            }

            if ($categorySlug) {
                $selectedCategory = Category::where('slug', $categorySlug)->first();
                if ($selectedCategory) {
                    $businessesQuery->where('category_id', $selectedCategory->id);
                }
            }

            // ✅ فلترة حسب الموقع (محافظة فقط)
            if ($locationSlug) {
                $location = Location::where('slug', $locationSlug)->first();
                if ($location) {
                    // إذا كان الموقع المختار هو محافظة (ليس له أب)
                    if ($location->parent_id === null) {
                        $businessesQuery->where('governorate_id', $location->id);
                    } else {
                        // إذا كان الموقع المختار هو منطقة فرعية
                        $businessesQuery->where('region_id', $location->id);
                    }
                }
            }

            $featuredBusinesses = $businessesQuery->latest()->paginate(12);
            $categories = Category::withBusinessCount()->ordered()->take(10)->get();
            $governorates = Location::governorates()->ordered()->get();

            // منشآت موثقة رسمياً
            $officialBusinesses = Business::approved()
                ->whereIn('verification_type', ['official', 'verified'])
                ->with(['category', 'governorate', 'region'])
                ->withAvg('reviews', 'rating')
                ->latest()
                ->take(8)
                ->get();

            // الأكثر تقييماً
            $topRatedBusinesses = Business::approved()
                ->with(['category', 'governorate', 'region'])
                ->withAvg('reviews', 'rating')
                ->having('reviews_avg_rating', '>=', 4)
                ->orderBy('reviews_avg_rating', 'desc')
                ->take(10)
                ->get();

            // توصيات اليوم
            $recommendedBusinesses = Business::approved()
                ->with(['category', 'governorate', 'region'])
                ->withAvg('reviews', 'rating')
                ->having('reviews_avg_rating', '>=', 4)
                ->inRandomOrder()
                ->take(6)
                ->get();

            // ✅ أخترنا لك - تم إصلاح مشكلة random()
            $pickedForYou = Business::approved()
                ->with(['category', 'governorate', 'region'])
                ->latest()
                ->take(50)
                ->get();

            // ✅ التحقق من وجود عناصر كافية قبل استخدام random()
            if ($pickedForYou->count() >= 6) {
                $pickedForYou = $pickedForYou->random(6);
            } else {
                // إذا كان عدد العناصر أقل من 6، نأخذ كل ما هو متاح
                $pickedForYou = $pickedForYou->count() > 0 ? $pickedForYou : collect();
            }

            // أحدث التقييمات
            $latestReviews = Review::with('business')
                ->latest()
                ->take(6)
                ->get();

            // إحصائيات
            $stats = [
                'total' => Business::approved()->count(),
                'categories' => Category::count(),
                'cities' => Location::governorates()->count(),
                'reviews' => Review::count(),
                'avg_rating' => round(Review::avg('rating') ?? 0, 1),
                'hospitals' => OfficialEntity::where('type', 'help')->where('sub_type', 'hospital')->count(),
                'government_entities' => OfficialEntity::where('type', 'government')->count(),
                'security_centers' => OfficialEntity::where('type', 'security')->count(),
            ];

            return [
                'featuredBusinesses' => $featuredBusinesses,
                'categories' => $categories,
                'governorates' => $governorates,
                'selectedCategory' => $selectedCategory,
                'searchQuery' => $searchQuery,
                'stats' => $stats,
                'topRatedBusinesses' => $topRatedBusinesses,
                'latestReviews' => $latestReviews,
                'officialBusinesses' => $officialBusinesses,
                'recommendedBusinesses' => $recommendedBusinesses,
                'pickedForYou' => $pickedForYou,
            ];
        });

        // AJAX request for load more
        if ($request->ajax()) {
            return view('partials.business_grid', ['featuredBusinesses' => $data['featuredBusinesses']])->render();
        }

        return view('index', $data);
    }

    /**
     * ✅ صفحة البحث المتقدم - المعدلة
     */
    public function search(Request $request)
    {
        $query = $request->get('search');
        $locationSlug = $request->get('location');
        $regionSlug = $request->get('region');
        $categorySlug = $request->get('category');
        $sort = $request->get('sort', 'latest');
        $deliveryOnly = $request->boolean('delivery');
        $verifiedOnly = $request->boolean('verified');

        $businessesQuery = Business::approved()
            ->with(['category', 'governorate', 'region'])
            ->withAvg('reviews', 'rating');

        // ✅ البحث النصي
        if ($query) {
            $businessesQuery->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhere('phone', 'LIKE', "%{$query}%")
                  ->orWhere('address_detail', 'LIKE', "%{$query}%");
            });
        }

        // ✅ فلترة المحافظة
        if ($locationSlug) {
            $location = Location::where('slug', $locationSlug)->first();
            if ($location && $location->parent_id === null) {
                $businessesQuery->where('governorate_id', $location->id);
            }
        }

        // ✅ فلترة المنطقة
        if ($regionSlug) {
            $region = Location::where('slug', $regionSlug)->first();
            if ($region) {
                $businessesQuery->where('region_id', $region->id);
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

        // ✅ فلترة الموثقين
        if ($verifiedOnly) {
            $businessesQuery->whereIn('verification_type', ['verified', 'official']);
        }

        // ✅ الترتيب
        $businessesQuery = match ($sort) {
            'rating' => $businessesQuery->orderBy('reviews_avg_rating', 'desc'),
            'oldest' => $businessesQuery->oldest(),
            default => $businessesQuery->latest(),
        };

        $businesses = $businessesQuery->paginate(12);

        $governorates = Location::governorates()->ordered()->get();
        $categories = Category::ordered()->get();

        return view('search', compact('businesses', 'query', 'governorates', 'categories'));
    }

    /**
     * عرض الصور بأمان
     */
    public function serveImage($folder, $filename)
    {
        $path = public_path("uploads/{$folder}/{$filename}");
        if (!file_exists($path) || !is_file($path)) {
            abort(404);
        }
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (!in_array($extension, $allowedExtensions)) {
            abort(404);
        }
        return response()->file($path, [
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }

    /**
     * خريطة الموقع Sitemap.xml
     */
    public function sitemap()
    {
        $cacheKey = 'sitemap_xml_v3';
        $xml = Cache::remember($cacheKey, now()->addHours(24), function () {
            $businesses = Business::approved()->with(['category', 'governorate', 'region'])->get();
            $categories = Category::all();
            $locations = Location::all();
            $officialEntities = OfficialEntity::active()->get();
            return view('sitemap', compact('businesses', 'categories', 'locations', 'officialEntities'))->render();
        });
        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    /**
     * مسح التخزين المؤقت (للإدارة)
     */
    public static function clearCache(): void
    {
        Cache::flush();
    }
}