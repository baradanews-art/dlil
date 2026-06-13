<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    /**
     * ✅ عرض جميع التصنيفات
     */
    public function index()
    {
        $categories = Category::withBusinessCount()->ordered()->get();
        
        return view('admin-categories', compact('categories'));
    }
    
    /**
     * ✅ إضافة تصنيف جديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'icon' => 'nullable|string|max:50',
        ]);
        
        Category::create($validated);
        
        // ✅ مسح التخزين المؤقت
        Cache::forget('categories_list');
        
        return redirect()->back()
            ->with('success', '✅ تم إضافة التصنيف بنجاح!');
    }
    
    /**
     * ✅ تحديث تصنيف
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'icon' => 'nullable|string|max:50',
        ]);
        
        $category->update($validated);
        
        Cache::forget('categories_list');
        
        return redirect()->back()
            ->with('success', '✅ تم تحديث التصنيف بنجاح!');
    }
    
    /**
     * ✅ حذف تصنيف
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        // ✅ التحقق من وجود منشآت تابعة
        if ($category->businesses()->count() > 0) {
            return redirect()->back()
                ->with('error', '⚠️ لا يمكن حذف هذا التصنيف لأنه يحتوي على منشآت تابعة.');
        }
        
        $category->delete();
        
        Cache::forget('categories_list');
        
        return redirect()->back()
            ->with('success', '✅ تم حذف التصنيف بنجاح.');
    }
}