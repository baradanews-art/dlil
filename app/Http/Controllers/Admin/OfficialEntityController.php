<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OfficialEntity;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OfficialEntityController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'government');
        $entities = OfficialEntity::where('type', $type)->orderBy('sort_order')->get();
        
        return view('admin.official.index', compact('entities', 'type'));
    }
    
    public function create()
    {
        $type = request()->get('type', 'government');
        $cities = Location::whereNull('parent_id')->orderBy('name')->get();
        
        return view('admin.official.create', compact('type', 'cities'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:government,security,help',
            'sub_type' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'hotline' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'city_id' => 'nullable|exists:locations,id',
            'region_id' => 'nullable|exists:locations,id',
            'working_hours' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $validated['slug'] = Str::slug($validated['name'], '-', 'ar');
        $validated['is_active'] = true;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        
        if ($request->hasFile('logo')) {
            if (!file_exists(public_path('uploads/official'))) {
                mkdir(public_path('uploads/official'), 0755, true);
            }
            $logoName = time() . '_logo_' . rand(1000, 9999) . '.' . $request->file('logo')->getClientOriginalExtension();
            $request->file('logo')->move(public_path('uploads/official'), $logoName);
            $validated['logo'] = 'uploads/official/' . $logoName;
        }
        
        OfficialEntity::create($validated);
        
        return redirect()->route('admin.official.index', ['type' => $validated['type']])
            ->with('success', '✅ تم إضافة المؤسسة بنجاح!');
    }
    
    public function edit($id)
    {
        $entity = OfficialEntity::findOrFail($id);
        $cities = Location::whereNull('parent_id')->orderBy('name')->get();
        
        return view('admin.official.edit', compact('entity', 'cities'));
    }
    
    public function update(Request $request, $id)
    {
        $entity = OfficialEntity::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:government,security,help',
            'sub_type' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'hotline' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'city_id' => 'nullable|exists:locations,id',
            'region_id' => 'nullable|exists:locations,id',
            'working_hours' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $validated['slug'] = Str::slug($validated['name'], '-', 'ar');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        
        if ($request->hasFile('logo')) {
            if ($entity->logo && file_exists(public_path($entity->logo))) {
                unlink(public_path($entity->logo));
            }
            if (!file_exists(public_path('uploads/official'))) {
                mkdir(public_path('uploads/official'), 0755, true);
            }
            $logoName = time() . '_logo_' . rand(1000, 9999) . '.' . $request->file('logo')->getClientOriginalExtension();
            $request->file('logo')->move(public_path('uploads/official'), $logoName);
            $validated['logo'] = 'uploads/official/' . $logoName;
        }
        
        $entity->update($validated);
        
        return redirect()->route('admin.official.index', ['type' => $entity->type])
            ->with('success', '✅ تم تحديث المؤسسة بنجاح!');
    }
    
    public function destroy($id)
    {
        $entity = OfficialEntity::findOrFail($id);
        $type = $entity->type;
        
        if ($entity->logo && file_exists(public_path($entity->logo))) {
            unlink(public_path($entity->logo));
        }
        
        $entity->delete();
        
        return redirect()->route('admin.official.index', ['type' => $type])
            ->with('success', '✅ تم حذف المؤسسة بنجاح!');
    }
    
    // ✅ API لجلب المناطق
    public function getRegions($cityId)
    {
        $regions = Location::where('parent_id', $cityId)
            ->orderBy('name')
            ->get(['id', 'name']);
        return response()->json($regions);
    }
}