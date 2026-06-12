<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
use App\Models\Location;
use App\Models\Review;
use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        $businesses = Business::withoutGlobalScopes()
            ->with(['category', 'location'])
            ->latest()
            ->get();

        return view('admin-dashboard', compact('businesses'));
    }

    public function editBusiness($id)
    {
        $business = Business::withoutGlobalScopes()->findOrFail($id);
        $categories = Category::all();
        $locations = Location::all(); 

        return view('admin-business-edit', compact('business', 'categories', 'locations'));
    }

    public function updateBusiness(Request $request, $id)
    {
        $business = Business::withoutGlobalScopes()->findOrFail($id);

        $request->validate([
            'title'             => 'required|string|max:255',
            'status'            => 'required|in:approved,pending',
            'verification_type' => 'required|in:unverified,verified,official',
        ]);

        $business->title = $request->title;
        $business->category_id = $request->category_id;
        $business->location_id = $request->location_id;
        $business->status = $request->status;
        $business->verification_type = $request->verification_type;
        $business->google_maps_url = $request->google_maps_url;
        
        $business->phone = $request->phone;
        $business->whatsapp = $request->whatsapp;
        $business->facebook_url = $request->facebook_url;
        $business->instagram_url = $request->instagram_url;
        $business->delivery_available = $request->delivery_available ?? 0;
        $business->description = $request->description;
        $business->price_list = $request->price_list; 

        $business->save();

        return redirect()->route('admin.dashboard')->with('success', 'تم تحديث واعتماد بيانات المنشأة بالكامل وبنجاح واقتدار!');
    }

    public function deleteBusiness($id)
    {
        $business = Business::withoutGlobalScopes()->findOrFail($id);
        $business->delete();
        return redirect()->back()->with('success', 'تم إزالة النشاط التجاري من النظام نهائياً.');
    }

    /* --- إدارة الأقسام والتصنيفات التجارية --- */
    public function categoriesIndex() {
        $categories = Category::all();
        return view('admin-categories', compact('categories'));
    }

    public function categoriesStore(Request $request) {
        $request->validate(['name' => 'required|string|max:255']);
        
        $category = new Category();
        $category->name = $request->name;
        // إصلاح السيو لدعم توليد الـ Slug باللغة العربية بشكل صحيح
        $category->slug = Str::slug($request->name, '-', 'ar') ?: 'cat-' . rand(10, 99);
        $category->save();
        
        return redirect()->back()->with('success', 'تم إضافة القسم الجديد بنجاح!');
    }

    public function categoriesDestroy($id) {
        Category::destroy($id);
        return redirect()->back()->with('success', 'تم إزالة التصنيف بنجاح.');
    }

    /* --- إدارة شجرة المواقع الجغرافية (محافظات / مناطق) --- */
    public function locationsIndex() {
        $locations = Location::with('parent')->get();
        $parentLocations = Location::whereNull('parent_id')->get();
        return view('admin-locations', compact('locations', 'parentLocations'));
    }

    public function locationsStore(Request $request) {
        $request->validate(['name' => 'required|string|max:255']);
        
        $location = new Location();
        $location->name = $request->name;
        $location->parent_id = $request->parent_id ?: null;
        
        // إصلاح بناء الرابط الجغرافي العربي الصديق لمحركات البحث
        $slugLoc = Str::slug($request->name, '-', 'ar');
        $location->slug = ($slugLoc ? $slugLoc : 'loc') . '-' . rand(10, 99);
        $location->save();
        
        return redirect()->back()->with('success', 'تم حفظ التوزيع الجغرافي الجديد!');
    }

    public function locationsDestroy($id) {
        Location::destroy($id);
        return redirect()->back()->with('success', 'تم إزالة الموقع من الشجرة الجغرافية.');
    }

    /* --- الرقابة الإعلانية ومراجعات المنصة --- */
    public function adsIndex() {
        $ads = Ad::all();
        return view('admin-ads', compact('ads'));
    }

    public function adsStore(Request $request) {
        $request->validate([
            'title'    => 'required|string|max:255', 
            'image'    => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'position' => 'nullable|string'
        ]);
        
        $ad = new Ad();
        $ad->title = $request->title;
        // تصحيح تعيين الحقل الفعلي للرابط
        $ad->link_url = $request->link;
        $ad->position = $request->position ?? 'sidebar';
        $ad->is_active = true;
        
        if($request->hasFile('image')) {
            // تصحيح تعيين الحقل الفعلي لمسار الصورة المعتمد في الـ Model
            $ad->image_path = $request->file('image')->store('ads', 'public');
        }
        $ad->save();
        
        return redirect()->back()->with('success', 'تم نشر الإعلان بنجاح وتوجيهه للمساحة الصحيحة!');
    }

    public function adsDestroy($id) {
        Ad::destroy($id);
        return redirect()->back()->with('success', 'تم إزالة الإعلان بنجاح.');
    }

    public function reviewsIndex() {
        $reviews = Review::with('business')->latest()->get();
        return view('admin-reviews', compact('reviews'));
    }

    public function reviewsDestroy($id) {
        Review::destroy($id);
        return redirect()->back()->with('success', 'تم إزالة المراجعة بنجاح.');
    }
}