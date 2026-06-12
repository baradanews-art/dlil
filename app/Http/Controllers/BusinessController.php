<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BusinessController extends Controller
{
    /**
     * عرض صفحة إضافة منشأة جديدة
     */
    public function create()
    {
        $categories = Category::all();
        $governorates = Location::whereNull('parent_id')->get();
        return view('add-business', compact('categories', 'governorates'));
    }

    /**
     * API: جلب المناطق حسب المحافظة (للـ AJAX)
     */
    public function getRegions($governorateId)
    {
        $regions = Location::where('parent_id', $governorateId)->get(['id', 'name']);
        return response()->json($regions);
    }

    /**
     * حفظ منشأة جديدة مع رفع الصور إلى public/uploads/
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'phone'             => 'required|string|max:100',
            'category_id'       => 'required|exists:categories,id',
            'location_id'       => 'required|exists:locations,id',
            'description'       => 'required|string',
            'address_detail'    => 'nullable|string|max:255',
            'logo'              => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'cover'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'delivery_available' => 'nullable|boolean',
            'latitude'          => 'nullable|numeric',
            'longitude'         => 'nullable|numeric',
        ]);

        try {
            $business = new Business();
            $business->title = $validated['title'];
            
            // توليد Slug فريد
            $baseSlug = Str::slug($validated['title'], '-', 'ar');
            $business->slug = $baseSlug ? $baseSlug . '-' . rand(100, 999) : 'shop-' . rand(1000, 9999);
            
            $business->phone = $validated['phone'];
            $business->category_id = $validated['category_id'];
            $business->location_id = $validated['location_id'];
            $business->address_detail = $validated['address_detail'] ?? null;
            $business->description = $validated['description'];
            $business->delivery_available = $request->has('delivery_available');
            $business->latitude = $validated['latitude'] ?? null;
            $business->longitude = $validated['longitude'] ?? null;
            $business->is_approved = 0;
            $business->verification_type = 'none';
            $business->price_list = [];

            // ============================================================
            // 🖼️ رفع الصور مباشرة إلى public/uploads/ (بدون storage link)
            // ============================================================
            
            // إنشاء المجلدات إذا لم تكن موجودة
            if (!file_exists(public_path('uploads/logos'))) {
                mkdir(public_path('uploads/logos'), 0777, true);
            }
            if (!file_exists(public_path('uploads/covers'))) {
                mkdir(public_path('uploads/covers'), 0777, true);
            }
            
            // رفع اللوجو
            if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
                $logoFile = $request->file('logo');
                $logoName = time() . '_logo_' . rand(1000, 9999) . '.' . $logoFile->getClientOriginalExtension();
                $logoFile->move(public_path('uploads/logos'), $logoName);
                $business->logo = 'uploads/logos/' . $logoName;
            }

            // رفع صورة الغلاف
            if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
                $coverFile = $request->file('cover');
                $coverName = time() . '_cover_' . rand(1000, 9999) . '.' . $coverFile->getClientOriginalExtension();
                $coverFile->move(public_path('uploads/covers'), $coverName);
                $business->cover = 'uploads/covers/' . $coverName;
            }

            $business->save();

            return redirect()->back()->with('success', '✅ تم إرسال منشأتك بنجاح! سيتم مراجعتها ونشرها قريباً.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', '❌ حدث خطأ: ' . $e->getMessage());
        }
    }

    /**
     * عرض صفحة منشأة مع تفاصيلها
     */
    public function show($slug)
    {
        $business = Business::where('slug', $slug)
            ->with(['category', 'location.parent', 'reviews'])
            ->firstOrFail();
        
        // زيادة عدد المشاهدات
        $business->incrementViews();
        
        $similarBusinesses = $business->getSimilarBusinesses(4);
        
        return view('business-show', compact('business', 'similarBusinesses'));
    }
}