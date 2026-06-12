<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    public function index()
    {
        $businesses = Business::with(['category', 'location'])->latest()->get();
        return view('admin-dashboard', compact('businesses'));
    }

    public function edit($id)
    {
        $business = Business::findOrFail($id);
        $categories = Category::all();
        $locations = Location::all();

        return view('admin-business-edit', compact('business', 'categories', 'locations'));
    }

    public function update(Request $request, $id)
    {
        $business = Business::findOrFail($id);

        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'phone'             => 'required|string|max:100',
            'category_id'       => 'required|exists:categories,id',
            'location_id'       => 'required|exists:locations,id',
            'description'       => 'required|string',
            'status'            => 'required|in:approved,pending',
            'verification_type' => 'required|in:none,verified,official',
            'delivery_available' => 'nullable|boolean',
            'price_list'        => 'nullable|array',
            'latitude'          => 'nullable|numeric',
            'longitude'         => 'nullable|numeric',
        ]);

        $business->is_approved = $validated['status'] === 'approved' ? 1 : 0;
        $business->title = $validated['title'];
        $business->phone = $validated['phone'];
        $business->category_id = $validated['category_id'];
        $business->location_id = $validated['location_id'];
        $business->description = $validated['description'];
        $business->verification_type = $validated['verification_type'];
        $business->delivery_available = $request->has('delivery_available');
        $business->price_list = $request->price_list ?? [];
        $business->address_detail = $request->address_detail;
        $business->facebook_url = $request->facebook_url;
        $business->instagram_url = $request->instagram_url;
        $business->Maps_url = $request->google_maps_url;
        $business->latitude = $request->latitude;
        $business->longitude = $request->longitude;

        if ($request->hasFile('logo')) {
            $business->logo = $request->file('logo')->store('logos', 'public');
        }
        if ($request->hasFile('cover')) {
            $business->cover = $request->file('cover')->store('covers', 'public');
        }

        $business->save();

        return redirect()->route('admin.dashboard')->with('success', 'تم تحديث المنشأة بنجاح!');
    }

    public function destroy($id)
    {
        $business = Business::findOrFail($id);
        $business->delete();

        return redirect()->back()->with('success', 'تم حذف المنشأة بنجاح.');
    }
}