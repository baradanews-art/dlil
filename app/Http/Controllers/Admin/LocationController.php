<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LocationController extends Controller
{
    // عرض جميع المواقع
    public function index()
    {
        $locations = Location::with('parent')->get();
        $governorates = Location::whereNull('parent_id')->get();
        
        return view('admin-locations', compact('locations', 'governorates'));
    }

    // إضافة موقع جديد
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'parent_id' => 'nullable|exists:locations,id',
        ]);

        Location::create([
            'name'      => $validated['name'],
            'slug'      => Str::slug($validated['name'], '-', 'ar') . '-' . rand(10, 99),
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        return redirect()->back()->with('success', '✅ تم إضافة الموقع بنجاح!');
    }

    // ✅ عرض نموذج تعديل الموقع (AJAX)
    public function edit($id)
    {
        $location = Location::with('parent')->findOrFail($id);
        $governorates = Location::whereNull('parent_id')->where('id', '!=', $id)->get();
        
        return response()->json([
            'location' => $location,
            'governorates' => $governorates
        ]);
    }

    // ✅ تحديث الموقع
    public function update(Request $request, $id)
    {
        $location = Location::findOrFail($id);
        
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'parent_id' => 'nullable|exists:locations,id',
        ]);

        // منع جعل الموقع أباً لنفسه
        if ($validated['parent_id'] == $id) {
            return redirect()->back()->with('error', '⚠️ لا يمكن جعل الموقع تابعاً لنفسه.');
        }

        $location->update([
            'name'      => $validated['name'],
            'slug'      => Str::slug($validated['name'], '-', 'ar') . '-' . rand(10, 99),
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        return redirect()->back()->with('success', '✅ تم تحديث الموقع بنجاح!');
    }

    // حذف الموقع
    public function destroy($id)
    {
        $location = Location::findOrFail($id);
        
        if ($location->businesses()->count() > 0) {
            return redirect()->back()->with('error', '⚠️ لا يمكن حذف هذا الموقع لأنه يحتوي على منشآت تابعة.');
        }
        
        // حذف المناطق الفرعية أولاً
        foreach ($location->children as $child) {
            $child->delete();
        }
        
        $location->delete();
        return redirect()->back()->with('success', '✅ تم حذف الموقع بنجاح.');
    }
}