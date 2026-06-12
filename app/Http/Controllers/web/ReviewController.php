<?php
// ملف: ReviewController.php
// المسار: app/Http/Controllers/Web/ReviewController.php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Business $business)
    {
        $validated = $request->validate([
            'reviewer_name' => 'required|string|max:150',
            'rating'        => 'required|integer|min:1|max:5',
            'comment'       => 'required|string|max:1000',
        ]);

        try {
            Review::create([
                'business_id'   => $business->id,
                'reviewer_name' => $validated['reviewer_name'],
                'rating'        => $validated['rating'],
                'comment'       => $validated['comment'],
            ]);

            // تحديث متوسط التقييم للمنشأة (اختياري)
            $business->updateAverageRating();

            return redirect()->back()->with('success', 'شكراً لك! تم نشر تقييمك بنجاح.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }
}