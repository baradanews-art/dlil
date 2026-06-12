<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Ad;
use App\Models\Category;
use App\Models\Setting;

class HomeController extends Controller
{
    public function index()
    {
        $featuredBusinesses = Business::where('is_approved', 1)
            ->with(['category', 'location'])
            ->latest()
            ->take(10)
            ->get();

        $sidebarAds = Ad::where('is_active', 1)
            ->where('position', 'sidebar')
            ->get();

        $topAds = Ad::where('is_active', 1)
            ->where('position', 'home_top')
            ->get();

        $categories = Category::all();

        return view('index', compact('featuredBusinesses', 'sidebarAds', 'topAds', 'categories'));
    }
}