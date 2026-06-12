<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BusinessController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        $governorates = Location::whereNull('parent_id')->get();

        return view('add-business', compact('categories', 'governorates'));
    }

    public function getRegions($governorateId)
    {
        $regions = Location::where('parent_id', $governorateId)->get(['id', 'name']);
        return response()->json($regions);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'phone'       => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'required|exists:locations,id',
            'description' => 'required|string',
            'address_detail' => 'nullable|string|max:255',
            'logo'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'cover'       => 'nullable|image|mimes:jpeg,png,jpg|max:3072',
            'delivery_available' => 'nullable|boolean',
            'latitude'    => 'nullable|numeric',
            'longitude'   => 'nullable|numeric',
        ]);

        try {
            $business = new Business();
            $business->title = $validated['title'];
            
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

            if ($request->hasFile('logo')) {
                $business->logo = $request->file('logo')->store('logos', 'public');
            }
            if ($request->hasFile('cover')) {
                $business->cover = $request->file('cover')->store('covers', 'public');
            }

            $business->save();

            return redirect()->back()->with('success', 'تم إرسال منشأتك بنجاح! سيتم مراجعتها ونشرها قريباً.');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    public function show($slug)
    {
        $business = Business::where('slug', $slug)->firstOrFail();
        $similarBusinesses = $business->getSimilarBusinesses(4);

        return view('business-show', compact('business', 'similarBusinesses'));
    }
}