<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Category;
use App\Models\Location;
use App\Models\Review;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // جلب المنشآت المعلقة مع Pagination
        $pendingBusinesses = Business::where('is_approved', 0)
            ->with(['category', 'location'])
            ->latest()
            ->paginate(10);
        
        // الإحصائيات
        $stats = [
            'total' => Business::count(),
            'approved' => Business::where('is_approved', 1)->count(),
            'pending' => Business::where('is_approved', 0)->count(),
            'categories' => Category::count(),
            'cities' => Location::whereNull('parent_id')->count(),
            'locations' => Location::count(),
            'reviews' => Review::count(),
            'avg_rating' => Review::avg('rating') ?? 0,
        ];
        
        return view('admin-dashboard', compact('pendingBusinesses', 'stats'));
    }
}