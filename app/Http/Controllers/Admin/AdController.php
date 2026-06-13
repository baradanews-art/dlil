<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdController extends Controller
{
    /**
     * ✅ عرض جميع الإعلانات
     */
    public function index()
    {
        $ads = Ad::latest()->get();
        
        return view('admin-ads', compact('ads'));
    }
    
    /**
     * ✅ إضافة إعلان جديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
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
        
        return redirect()->back()
            ->with('success', '✅ تم إضافة الإعلان بنجاح!');
    }
    
    /**
     * ✅ حذف إعلان
     */
    public function destroy($id)
    {
        $ad = Ad::findOrFail($id);
        
        // ✅ حذف الصورة
        if ($ad->image_path && Storage::disk('public')->exists($ad->image_path)) {
            Storage::disk('public')->delete($ad->image_path);
        }
        
        $ad->delete();
        
        return redirect()->back()
            ->with('success', '✅ تم حذف الإعلان بنجاح.');
    }
    
    /**
     * ✅ تبديل حالة الإعلان (نشط/غير نشط)
     */
    public function toggle($id)
    {
        $ad = Ad::findOrFail($id);
        $ad->is_active = !$ad->is_active;
        $ad->save();
        
        return redirect()->back()
            ->with('success', $ad->is_active ? '✅ تم تفعيل الإعلان.' : '⏸️ تم إيقاف الإعلان.');
    }
}