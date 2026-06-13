<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    /**
     * ✅ عرض جميع التقييمات
     */
    public function index(Request $request)
    {
        $query = Review::with('business');
        
        // ✅ فلترة حسب المنشأة
        if ($request->filled('business_id')) {
            $query->where('business_id', $request->business_id);
        }
        
        // ✅ فلترة حسب الحالة
        if ($request->filled('status')) {
            if ($request->status === 'approved') {
                $query->approved();
            } elseif ($request->status === 'pending') {
                $query->where('is_approved', false);
            }
        }
        
        $reviews = $query->latest()->paginate(20);
        
        return view('admin-reviews', compact('reviews'));
    }
    
    /**
     * ✅ تحديث تقييم
     */
    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        
        $validated = $request->validate([
            'reviewer_name' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
            'reply' => 'nullable|string|max:500',
            'is_approved' => 'nullable|boolean',
        ]);
        
        DB::beginTransaction();
        
        try {
            $review->update([
                'reviewer_name' => $validated['reviewer_name'],
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
                'is_approved' => $validated['is_approved'] ?? $review->is_approved,
            ]);
            
            if (isset($validated['reply']) && !empty($validated['reply'])) {
                $review->addReply($validated['reply']);
            }
            
            // ✅ تحديث متوسط تقييم المنشأة
            $review->business->updateAverageRating();
            
            DB::commit();
            
            return redirect()->back()
                ->with('success', '✅ تم تحديث التقييم بنجاح!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', '❌ حدث خطأ: ' . $e->getMessage());
        }
    }
    
    /**
     * ✅ الرد على تقييم (اختصار)
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
    
    /**
     * ✅ حذف تقييم
     */
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $business = $review->business;
        
        $review->delete();
        
        if ($business) {
            $business->updateAverageRating();
        }
        
        return redirect()->back()
            ->with('success', '✅ تم حذف التقييم بنجاح.');
    }
    
    /**
     * ✅ تصدير التقييمات
     */
    public function export()
    {
        $reviews = Review::with('business')->get();
        
        $filename = 'reviews_' . date('Y-m-d') . '.csv';
        $handle = fopen('php://temp', 'w+');
        
        fputcsv($handle, ['ID', 'المراجع', 'المنشأة', 'التقييم', 'التعليق', 'الرد', 'التاريخ']);
        
        foreach ($reviews as $review) {
            fputcsv($handle, [
                $review->id,
                $review->reviewer_name,
                $review->business->title ?? 'محذوف',
                $review->rating . '/5',
                $review->comment,
                $review->reply ?? '',
                $review->created_at->format('Y-m-d'),
            ]);
        }
        
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        
        return response($csv, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename={$filename}");
    }
}