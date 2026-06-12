<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // عرض جميع التقييمات
    public function index()
    {
        $reviews = Review::with('business')->latest()->get();
        return view('admin-reviews', compact('reviews'));
    }

    // ✅ عرض نموذج تعديل التقييم
    public function edit($id)
    {
        $review = Review::with('business')->findOrFail($id);
        return response()->json($review);
    }

    // ✅ تحديث التقييم
    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        
        $validated = $request->validate([
            'reviewer_name' => 'required|string|max:255',
            'rating'        => 'required|integer|min:1|max:5',
            'comment'       => 'required|string|max:1000',
            'reply'         => 'nullable|string|max:500',
        ]);

        $review->update([
            'reviewer_name' => $validated['reviewer_name'],
            'rating'        => $validated['rating'],
            'comment'       => $validated['comment'],
            'reply'         => $validated['reply'] ?? null,
            'replied_at'    => $validated['reply'] ? now() : null,
        ]);

        // تحديث متوسط تقييم المنشأة
        $review->business->updateAverageRating();

        return redirect()->back()->with('success', '✅ تم تحديث التقييم بنجاح!');
    }

    // حذف التقييم
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $business = $review->business;
        $review->delete();
        
        // تحديث متوسط تقييم المنشأة بعد الحذف
        if ($business) {
            $business->updateAverageRating();
        }
        
        return redirect()->back()->with('success', '✅ تم حذف التقييم بنجاح.');
    }
}