<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    /**
     * ✅ إضافة تقييم جديد
     */
    public function store(Request $request, Business $business)
    {
        $validated = $request->validate([
            'reviewer_name' => 'required|string|max:150',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5|max:1000',
        ]);
        
        DB::beginTransaction();
        
        try {
            $review = Review::create([
                'business_id' => $business->id,
                'reviewer_name' => $validated['reviewer_name'],
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
                'is_approved' => false, // ✅ تحتاج مراجعة الإدارة
            ]);
            
            // ✅ تحديث متوسط التقييم
            $business->updateAverageRating();
            
            DB::commit();
            
            return redirect()->back()
                ->with('success', '✨ شكراً لك! تم إرسال تقييمك وسيتم نشره بعد المراجعة.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', '❌ حدث خطأ: ' . $e->getMessage());
        }
    }
    
    /**
     * ✅ API: عرض التقييمات (لـ AJAX)
     */
    public function index(Request $request)
    {
        $query = Review::with('business')->latest();
        
        if ($request->has('business_id')) {
            $query->forBusiness($request->business_id);
        }
        
        if ($request->has('rating')) {
            $query->where('rating', $request->rating);
        }
        
        $reviews = $query->paginate(20);
        
        if ($request->ajax()) {
            return response()->json($reviews);
        }
        
        return view('reviews.index', compact('reviews'));
    }
    
    /**
     * ✅ الرد على تقييم (للمدير)
     */
    public function reply(Request $request, $id)
    {
        $validated = $request->validate([
            'reply' => 'required|string|max:500',
        ]);
        
        $review = Review::findOrFail($id);
        $review->addReply($validated['reply']);
        
        return redirect()->back()
            ->with('success', '✅ تم إضافة الرد على التقييم بنجاح.');
    }
}