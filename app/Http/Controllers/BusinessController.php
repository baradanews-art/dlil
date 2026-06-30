<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BusinessController extends Controller
{
    public function create()
    {
        $categories = Category::ordered()->get();
        $governorates = Location::governorates()->ordered()->get();
        
        return view('add-business', compact('categories', 'governorates'));
    }
    
    public function getRegions($governorateId)
    {
        $regions = Location::where('parent_id', $governorateId)
            ->ordered()
            ->get(['id', 'name']);
        
        return response()->json($regions);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'phone' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'governorate_id' => 'required|exists:locations,id',
            'region_id' => 'required|exists:locations,id',
            'description' => 'required|string|min:10',
            'address_detail' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'delivery_available' => 'nullable|boolean',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);
        
        DB::beginTransaction();
        
        try {
            $business = new Business();
            $business->title = $validated['title'];
            $business->slug = $this->generateUniqueSlug($validated['title']);
            $business->phone = $validated['phone'];
            $business->category_id = $validated['category_id'];
            $business->governorate_id = $validated['governorate_id'];
            $business->region_id = $validated['region_id'];
            $business->address_detail = $validated['address_detail'] ?? null;
            $business->description = $validated['description'];
            $business->delivery_available = $request->has('delivery_available');
            $business->latitude = $validated['latitude'] ?? null;
            $business->longitude = $validated['longitude'] ?? null;
            $business->is_approved = false;
            $business->verification_type = 'none';
            $business->price_list = [];
            
            // ============================================================
            // ✅ رفع اللوجو - طريقة مبسطة
            // ============================================================
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $filename = time() . '_' . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
                $destination = 'uploads/logos/';
                
                // مسار التخزين
                $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/dlil/public/' . $destination;
                
                // إنشاء المجلد إذا لم يكن موجوداً
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                // نقل الملف
                $file->move($uploadPath, $filename);
                $business->logo = $destination . $filename;
            }
            
            // ============================================================
            // ✅ رفع الغلاف - طريقة مبسطة
            // ============================================================
            if ($request->hasFile('cover')) {
                $file = $request->file('cover');
                $filename = time() . '_' . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
                $destination = 'uploads/covers/';
                
                $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/dlil/public/' . $destination;
                
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $file->move($uploadPath, $filename);
                $business->cover = $destination . $filename;
            }
            
            $business->save();
            DB::commit();
            
            return redirect()->route('business.create')
                ->with('success', '✅ تم إرسال منشأتك بنجاح! سيتم مراجعتها ونشرها قريباً.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', '❌ حدث خطأ: ' . $e->getMessage());
        }
    }
    
    public function show($slug)
    {
        $business = Business::where('slug', $slug)
            ->with(['category', 'governorate', 'region', 'reviews' => function ($q) {
                $q->latest()->limit(10);
            }])
            ->firstOrFail();
        
        if (!$business->wasRecentlyCreated) {
            $business->incrementViews();
        }
        
        $similarBusinesses = $business->getSimilarBusinesses(4);
        
        return view('business-show', compact('business', 'similarBusinesses'));
    }
    
    private function generateUniqueSlug(string $title): string
    {
        $baseSlug = Str::slug($title, '-', 'ar');
        $slug = $baseSlug ?: 'business';
        $count = Business::where('slug', 'LIKE', "{$slug}%")->count();
        
        return $count ? "{$slug}-{$count}" : $slug;
    }
}