<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * إضافة تقييم جديد لمنشأة معينة
     */
    public function store(Request $request, Business $business)
    {
        // التحقق من صحة البيانات المدخلة
        $validated = $request->validate([
            'reviewer_name' => 'required|string|max:150',
            'rating'        => 'required|integer|min:1|max:5',
            'comment'       => 'required|string|min:3|max:1000',
        ]);

        try {
            // إنشاء تقييم جديد
            $review = Review::create([
                'business_id'   => $business->id,
                'reviewer_name' => $validated['reviewer_name'],
                'rating'        => $validated['rating'],
                'comment'       => $validated['comment'],
            ]);

            // تحديث متوسط التقييمات للمنشأة (اختياري - إذا كانت الدالة موجودة)
            if (method_exists($business, 'updateAverageRating')) {
                $business->updateAverageRating();
            }

            // إعادة التوجيه مع رسالة نجاح
            return redirect()->back()->with('success', '✨ شكراً لك! تم نشر تقييمك بنجاح.');

        } catch (\Exception $e) {
            // في حالة وجود خطأ، نعيد المستخدم مع البيانات المدخلة ورسالة خطأ
            return redirect()->back()
                ->withInput()
                ->with('error', '❌ حدث خطأ أثناء حفظ التقييم: ' . $e->getMessage());
        }
    }

    /**
     * عرض جميع التقييمات (API - يمكن استخدامها للـ AJAX)
     */
    public function index(Request $request)
    {
        $query = Review::with('business')->latest();
        
        // فلترة حسب المنشأة (إذا أرسل business_id)
        if ($request->has('business_id')) {
            $query->where('business_id', $request->business_id);
        }
        
        // فلترة حسب التقييم (إذا أرسل rating)
        if ($request->has('rating')) {
            $query->where('rating', $request->rating);
        }
        
        $reviews = $query->paginate(20);
        
        // إذا كان الطلب AJAX، نعيد JSON
        if ($request->ajax()) {
            return response()->json($reviews);
        }
        
        return view('reviews.index', compact('reviews'));
    }

    /**
     * عرض تقييم محدد (API)
     */
    public function show($id)
    {
        $review = Review::with('business')->findOrFail($id);
        return response()->json($review);
    }

    /**
     * الرد على تقييم (ميزة إضافية للمستقبل)
     */
    public function reply(Request $request, $id)
    {
        $validated = $request->validate([
            'reply' => 'required|string|max:500',
        ]);
        
        $review = Review::findOrFail($id);
        $review->reply = $validated['reply'];
        $review->replied_at = now();
        $review->save();
        
        return redirect()->back()->with('success', '✅ تم إضافة الرد على التقييم بنجاح.');
    }
}