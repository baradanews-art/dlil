<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LocationController extends Controller
{
    /**
     * ✅ عرض جميع المواقع
     */
    public function index()
    {
        $locations = Location::with('parent')->ordered()->get();
        $governorates = Location::governorates()->ordered()->get();
        
        return view('admin-locations', compact('locations', 'governorates'));
    }
    
    /**
     * ✅ إضافة موقع جديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:locations,id',
        ]);
        
        Location::create($validated);
        
        Cache::forget('locations_list');
        Cache::forget('governorates_list');
        
        return redirect()->back()
            ->with('success', '✅ تم إضافة الموقع بنجاح!');
    }
    
    /**
     * ✅ إضافة موقع عبر AJAX (للإضافة السريعة)
     */
    public function ajaxStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:locations,id',
        ]);
        
        $location = Location::create($validated);
        
        Cache::forget('locations_list');
        
        return response()->json([
            'success' => true,
            'location' => $location,
            'message' => 'تم إضافة الموقع بنجاح',
        ]);
    }
    
    /**
     * ✅ تحديث موقع
     */
    public function update(Request $request, $id)
    {
        $location = Location::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:locations,id',
        ]);
        
        // ✅ منع جعل الموقع أباً لنفسه
        if ($validated['parent_id'] == $id) {
            return redirect()->back()
                ->with('error', '⚠️ لا يمكن جعل الموقع تابعاً لنفسه.');
        }
        
        $location->update($validated);
        
        Cache::forget('locations_list');
        Cache::forget('governorates_list');
        
        return redirect()->back()
            ->with('success', '✅ تم تحديث الموقع بنجاح!');
    }
    
    /**
     * ✅ حذف موقع
     */
    public function destroy($id)
    {
        $location = Location::findOrFail($id);
        
        // ✅ التحقق من وجود منشآت تابعة
        if ($location->businesses()->count() > 0) {
            return redirect()->back()
                ->with('error', '⚠️ لا يمكن حذف هذا الموقع لأنه يحتوي على منشآت تابعة.');
        }
        
        // ✅ حذف المناطق الفرعية
        foreach ($location->children as $child) {
            if ($child->businesses()->count() > 0) {
                return redirect()->back()
                    ->with('error', "⚠️ لا يمكن حذف الموقع لأن منطقة '{$child->name}' تحتوي على منشآت تابعة.");
            }
            $child->delete();
        }
        
        $location->delete();
        
        Cache::forget('locations_list');
        Cache::forget('governorates_list');
        
        return redirect()->back()
            ->with('success', '✅ تم حذف الموقع بنجاح.');
    }
}