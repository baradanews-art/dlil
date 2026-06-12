<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use Illuminate\Http\Request;

class AdController extends Controller
{
    public function index()
    {
        $ads = Ad::latest()->get();
        return view('admin-ads', compact('ads'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'    => 'required|string|max:255',
            'image'    => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'link_url' => 'nullable|url|max:255',
            'position' => 'required|in:home_top,sidebar',
        ]);

        $ad = new Ad();
        $ad->title = $validated['title'];
        $ad->link_url = $validated['link_url'] ?? null;
        $ad->position = $validated['position'];
        $ad->is_active = true;

        if ($request->hasFile('image')) {
            $ad->image_path = $request->file('image')->store('ads', 'public');
        }

        $ad->save();

        return redirect()->back()->with('success', 'تم إضافة الإعلان بنجاح!');
    }

    public function destroy($id)
    {
        $ad = Ad::findOrFail($id);
        $ad->delete();
        
        return redirect()->back()->with('success', 'تم حذف الإعلان بنجاح.');
    }
}