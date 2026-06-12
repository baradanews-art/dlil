<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // عرض جميع التصنيفات
    public function index()
    {
        $categories = Category::withCount('businesses')->get();
        return view('admin-categories', compact('categories'));
    }

    // إضافة تصنيف جديد
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'icon' => 'nullable|string|max:50',
        ]);

        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name'], '-', 'ar'),
            'icon' => $validated['icon'] ?? null,
        ]);

        return redirect()->back()->with('success', '✅ تم إضافة التصنيف بنجاح!');
    }

    // ✅ عرض نموذج تعديل التصنيف (AJAX أو صفحة منفصلة)
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    // ✅ تحديث التصنيف
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'icon' => 'nullable|string|max:50',
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name'], '-', 'ar'),
            'icon' => $validated['icon'] ?? null,
        ]);

        return redirect()->back()->with('success', '✅ تم تحديث التصنيف بنجاح!');
    }

    // حذف التصنيف
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        if ($category->businesses()->count() > 0) {
            return redirect()->back()->with('error', '⚠️ لا يمكن حذف هذا التصنيف لأنه يحتوي على منشآت تابعة.');
        }
        
        $category->delete();
        return redirect()->back()->with('success', '✅ تم حذف التصنيف بنجاح.');
    }
}