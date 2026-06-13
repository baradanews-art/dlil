<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BusinessController extends Controller
{
    public function index(Request $request)
    {
        $query = Business::with(['category', 'location']);
        
        if ($request->filled('status')) {
            if ($request->status === 'approved') {
                $query->approved();
            } elseif ($request->status === 'pending') {
                $query->pending();
            }
        }
        
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }
        
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        
        $businesses = $query->latest()->paginate(20);
        $categories = Category::ordered()->get();
        $locations = Location::ordered()->get();
        
        return view('admin-businesses', compact('businesses', 'categories', 'locations'));
    }
    
    public function edit($id)
    {
        $business = Business::findOrFail($id);
        $categories = Category::ordered()->get();
        $locations = Location::with('parent')->ordered()->get();
        
        return view('admin-business-edit', compact('business', 'categories', 'locations'));
    }
    
    public function update(Request $request, $id)
    {
        $business = Business::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'phone' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'required|exists:locations,id',
            'description' => 'required|string',
            'address_detail' => 'nullable|string|max:255',
            'status' => 'required|in:approved,pending',
            'verification_type' => 'required|in:none,verified,official',
            'delivery_available' => 'nullable|boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'facebook_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'google_maps_url' => 'nullable|url|max:255',
        ]);
        
        DB::beginTransaction();
        
        try {
            $business->title = $validated['title'];
            $business->phone = $validated['phone'];
            $business->category_id = $validated['category_id'];
            $business->location_id = $validated['location_id'];
            $business->description = $validated['description'];
            $business->address_detail = $validated['address_detail'] ?? null;
            $business->is_approved = $validated['status'] === 'approved';
            $business->verification_type = $validated['verification_type'];
            $business->delivery_available = $request->has('delivery_available');
            $business->latitude = $validated['latitude'] ?? null;
            $business->longitude = $validated['longitude'] ?? null;
            $business->facebook_url = $validated['facebook_url'] ?? null;
            $business->instagram_url = $validated['instagram_url'] ?? null;
            
            // تم تعديلها لتطابق المسمى القياسي للمتغيرات إن وجد في قاعدة البيانات
            if (\Schema::hasColumn('businesses', 'google_maps_url')) {
                $business->google_maps_url = $validated['google_maps_url'] ?? null;
            } else {
                $business->Maps_url = $validated['google_maps_url'] ?? null;
            }
            
            // ✅ تحديث اللوجو عبر دالة لارافيل المعتمدة
            if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
                $file = $request->file('logo');
                $filename = time() . '_logo_' . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
                $destination = public_path('uploads/logos');
                
                // حذف الصورة القديمة إذا كانت موجودة فعلياً
                if ($business->logo && file_exists(public_path($business->logo))) {
                    @unlink(public_path($business->logo));
                }
                
                $file->move($destination, $filename);
                $business->logo = 'uploads/logos/' . $filename;
            }
            
            // ✅ تحديث الغلاف عبر دالة لارافيل المعتمدة
            if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
                $file = $request->file('cover');
                $filename = time() . '_cover_' . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
                $destination = public_path('uploads/covers');
                
                // حذف الصورة القديمة إذا كانت موجودة فعلياً
                if ($business->cover && file_exists(public_path($business->cover))) {
                    @unlink(public_path($business->cover));
                }
                
                $file->move($destination, $filename);
                $business->cover = 'uploads/covers/' . $filename;
            }
            
            // ✅ تحديث قائمة الأسعار
            if ($request->has('price_list') && is_array($request->price_list)) {
                $business->price_list = array_values(array_filter($request->price_list, function ($item) {
                    return !empty($item['name']);
                }));
            }
            
            $business->save();
            DB::commit();
            
            return redirect()->route('admin.businesses.index')
                ->with('success', '✅ تم تحديث المنشأة بنجاح!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', '❌ حدث خطأ: ' . $e->getMessage());
        }
    }
    
    public function destroy($id)
    {
        $business = Business::findOrFail($id);
        
        // حذف الصور
        if ($business->logo && file_exists(public_path($business->logo))) {
            @unlink(public_path($business->logo));
        }
        
        if ($business->cover && file_exists(public_path($business->cover))) {
            @unlink(public_path($business->cover));
        }
        
        $business->delete();
        
        return redirect()->route('admin.businesses.index')
            ->with('success', '✅ تم حذف المنشأة بنجاح.');
    }
}