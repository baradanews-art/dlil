<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    
    <title>{{ $siteName ?? 'دليل سوريا التجاري' }} | دليل الأعمال في سوريا</title>
    <meta name="description" content="{{ $siteDescription ?? 'دليلك الشامل للفعاليات والأنشطة التجارية في سوريا' }}">
    <meta name="keywords" content="دليل سوريا, دليل تجاري, محلات سوريا, مطاعم سوريا, خدمات سوريا">
    <meta name="robots" content="index, follow">
    
    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $siteName ?? 'دليل سوريا التجاري' }}">
    <meta property="og:description" content="{{ $siteDescription ?? 'دليلك الشامل للأعمال في سوريا' }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">
    
    {{-- Preconnect for faster loading --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    
    <style>
        :root { --primary: #10b981; --primary-dark: #059669; }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInLeft {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        .animate-fadeInUp { animation: fadeInUp 0.6s ease-out forwards; }
        .animate-fadeInLeft { animation: fadeInLeft 0.6s ease-out forwards; }
        .animate-fadeInRight { animation: fadeInRight 0.6s ease-out forwards; }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }
        
        .business-card { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        .business-card:hover { transform: translateY(-8px); box-shadow: 0 25px 40px -12px rgba(0, 0, 0, 0.25); }
        
        .category-card { transition: all 0.3s ease; }
        .category-card:hover { transform: translateY(-5px); background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .category-card:hover .category-icon,
        .category-card:hover .category-name,
        .category-card:hover .category-count { color: white !important; }
        
        .floating { animation: float 3s ease-in-out infinite; }
        
        .scroll-top {
            position: fixed; bottom: 30px; right: 30px;
            width: 50px; height: 50px; background: var(--primary);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            cursor: pointer; opacity: 0; visibility: hidden; transition: all 0.3s ease; z-index: 100;
        }
        .scroll-top.show { opacity: 1; visibility: visible; }
        .scroll-top:hover { background: var(--primary-dark); transform: translateY(-3px); }
        
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 10px; }
        
        .swiper { direction: ltr; }
        .swiper-wrapper { direction: rtl; }
        
        .line-clamp-1 { display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    </style>
</head>
<body class="bg-gradient-to-b from-slate-50 to-white font-sans antialiased">

    {{-- HERO SECTION --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 min-h-[600px] flex items-center">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-20 left-10 w-72 h-72 bg-emerald-500 rounded-full filter blur-3xl animate-pulse"></div>
            <div class="absolute bottom-20 right-10 w-96 h-96 bg-blue-500 rounded-full filter blur-3xl animate-pulse delay-1000"></div>
        </div>
        
        <div class="absolute top-20 right-20 text-4xl floating opacity-20">🏪</div>
        <div class="absolute bottom-20 left-20 text-5xl floating opacity-20" style="animation-delay: 1s;">📍</div>
        
        <div class="relative max-w-7xl mx-auto px-4 py-20 text-center z-10">
            <div class="inline-flex items-center gap-2 bg-emerald-500/10 backdrop-blur-sm rounded-full px-4 py-1.5 mb-6 border border-emerald-500/20 animate-fadeInUp">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span class="text-emerald-400 text-xs font-bold tracking-wider">✨ دليلك الموثوق في سوريا ✨</span>
            </div>
            
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-black text-white tracking-tight animate-fadeInUp">
                {{ $siteName ?? 'دليل سوريا التجاري' }}
            </h1>
            
            <div class="mt-6 text-lg md:text-xl text-slate-300 max-w-3xl mx-auto animate-fadeInUp delay-100">
                <span class="typed-text"></span><span class="cursor-blink">|</span>
            </div>
            
            <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center animate-fadeInUp delay-200">
                <a href="{{ route('business.create') }}" 
                   class="inline-flex items-center justify-center gap-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-base px-8 py-4 rounded-2xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-emerald-500/25">
                    <i class="fas fa-plus-circle"></i> أضف منشأتك مجاناً
                </a>
                <a href="#businesses" 
                   class="inline-flex items-center justify-center gap-3 bg-white/10 backdrop-blur-sm hover:bg-white/20 text-white font-bold text-base px-8 py-4 rounded-2xl transition-all duration-300 border border-white/20">
                    <i class="fas fa-store"></i> استكشف المنشآت <i class="fas fa-arrow-down"></i>
                </a>
            </div>
            
            {{-- Search Bar --}}
            <div class="mt-12 max-w-2xl mx-auto animate-fadeInUp delay-300">
                <form action="{{ route('search') }}" method="GET" class="relative">
                    <i class="fas fa-search absolute right-5 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="search" id="globalSearch" 
                           placeholder="ابحث عن منشأة، خدمة، أو تصنيف..." 
                           class="w-full bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl py-4 pr-12 pl-5 text-white placeholder-slate-400 focus:outline-none focus:border-emerald-500 transition-all">
                    <button type="submit" class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl text-sm transition-all">
                        <i class="fas fa-search ml-1"></i> بحث
                    </button>
                </form>
                <div class="flex flex-wrap gap-2 justify-center mt-4">
                    <span class="text-xs text-slate-400">الأكثر بحثاً:</span>
                    <a href="{{ route('search', ['search' => 'مطاعم']) }}" class="text-xs text-slate-300 hover:text-emerald-400 transition-colors">مطاعم</a>
                    <a href="{{ route('search', ['search' => 'صيدليات']) }}" class="text-xs text-slate-300 hover:text-emerald-400 transition-colors">صيدليات</a>
                    <a href="{{ route('search', ['search' => 'مفروشات']) }}" class="text-xs text-slate-300 hover:text-emerald-400 transition-colors">مفروشات</a>
                    <a href="{{ route('search', ['search' => 'توصيل']) }}" class="text-xs text-slate-300 hover:text-emerald-400 transition-colors">توصيل</a>
                </div>
            </div>
        </div>
        
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 64L60 69.3C120 75 240 85 360 80C480 75 600 53 720 48C840 43 960 53 1080 58.7C1200 64 1320 64 1380 64L1440 64V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0V64Z" fill="#f8fafc"/>
            </svg>
        </div>
    </section>

    {{-- STATS SECTION --}}
    <section class="relative -mt-10 z-20 max-w-7xl mx-auto px-4">
        <div class="bg-white rounded-3xl shadow-2xl p-6 md:p-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div class="stat-item group cursor-pointer" data-count="{{ $stats['total'] ?? 0 }}">
                    <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-3 group-hover:bg-emerald-600 transition-colors">
                        <i class="fas fa-store text-emerald-600 text-2xl group-hover:text-white transition-colors"></i>
                    </div>
                    <div class="stat-number text-2xl md:text-3xl font-black text-slate-900">0</div>
                    <div class="text-xs text-slate-500 mt-1">منشأة تجارية</div>
                </div>
                <div class="stat-item group cursor-pointer" data-count="{{ $stats['categories'] ?? 0 }}">
                    <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-3 group-hover:bg-blue-600 transition-colors">
                        <i class="fas fa-tags text-blue-600 text-2xl group-hover:text-white transition-colors"></i>
                    </div>
                    <div class="stat-number text-2xl md:text-3xl font-black text-slate-900">0</div>
                    <div class="text-xs text-slate-500 mt-1">تصنيف خدمي</div>
                </div>
                <div class="stat-item group cursor-pointer" data-count="{{ $stats['cities'] ?? 0 }}">
                    <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-3 group-hover:bg-purple-600 transition-colors">
                        <i class="fas fa-city text-purple-600 text-2xl group-hover:text-white transition-colors"></i>
                    </div>
                    <div class="stat-number text-2xl md:text-3xl font-black text-slate-900">0</div>
                    <div class="text-xs text-slate-500 mt-1">مدينة ومحافظة</div>
                </div>
                <div class="stat-item group cursor-pointer" data-count="{{ $stats['reviews'] ?? 0 }}">
                    <div class="w-14 h-14 bg-amber-100 rounded-2xl flex items-center justify-center mx-auto mb-3 group-hover:bg-amber-500 transition-colors">
                        <i class="fas fa-star text-amber-500 text-2xl group-hover:text-white transition-colors"></i>
                    </div>
                    <div class="stat-number text-2xl md:text-3xl font-black text-slate-900">0</div>
                    <div class="text-xs text-slate-500 mt-1">تقييم حقيقي</div>
                </div>
            </div>
        </div>
    </section>

    {{-- CATEGORIES SECTION --}}
    @if(isset($categories) && count($categories) > 0)
    <section class="max-w-7xl mx-auto px-4 py-12">
        <div class="text-center mb-8 animate-fadeInUp">
            <h2 class="text-2xl md:text-3xl font-black text-slate-900">📂 تصفح حسب التصنيف</h2>
            <p class="text-slate-500 mt-2">أكثر من {{ count($categories) }} تصنيف لتجد ما تبحث عنه</p>
        </div>
        
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($categories as $index => $category)
            <a href="{{ route('home', ['category' => $category->slug]) }}" 
               class="category-card group bg-white rounded-2xl p-4 text-center transition-all duration-300 shadow-sm hover:shadow-xl animate-fadeInUp"
               style="animation-delay: {{ $index * 0.03 }}s">
                <div class="category-icon text-4xl mb-2 transition-all duration-300 group-hover:scale-110 group-hover:-translate-y-1">
                    {{ $category->icon ?? '📁' }}
                </div>
                <h3 class="category-name text-xs font-bold text-slate-700 transition-colors duration-300">
                    {{ $category->name }}
                </h3>
                <p class="category-count text-[10px] text-slate-400 mt-1 transition-colors duration-300">
                    {{ $category->businesses_count ?? 0 }} منشأة
                </p>
            </a>
            @endforeach
        </div>
        
        @if(isset($selectedCategory) && $selectedCategory)
        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 font-bold text-sm">
                <i class="fas fa-arrow-right"></i> عرض جميع المنشآت
            </a>
        </div>
        @endif
    </section>
    @endif

    {{-- OFFICIAL SERVICES SECTION --}}
    <section class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('official.government') }}" class="group relative overflow-hidden rounded-2xl shadow-lg transition-all duration-300 hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-br from-green-700 to-green-800"></div>
                <div class="relative p-8 text-center text-white z-10">
                    <div class="w-20 h-20 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-landmark text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">مؤسسات حكومية</h3>
                    <p class="text-green-100 text-sm">وزارات، دوائر، مديريات رسمية</p>
                    <div class="mt-4 inline-flex items-center gap-2 text-sm bg-white/20 rounded-full px-4 py-1.5 group-hover:bg-white/30 transition-all">
                        <span>استعرض</span> <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                </div>
            </a>
            
            <a href="{{ route('official.security') }}" class="group relative overflow-hidden rounded-2xl shadow-lg transition-all duration-300 hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-br from-red-700 to-red-800"></div>
                <div class="relative p-8 text-center text-white z-10">
                    <div class="w-20 h-20 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-shield-alt text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">الأمن والنجدة</h3>
                    <p class="text-red-100 text-sm">شرطة، دفاع مدني، إسعاف، طوارئ</p>
                    <div class="mt-4 inline-flex items-center gap-2 text-sm bg-white/20 rounded-full px-4 py-1.5 group-hover:bg-white/30 transition-all">
                        <span>استعرض</span> <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                </div>
            </a>
            
            <a href="{{ route('official.help') }}" class="group relative overflow-hidden rounded-2xl shadow-lg transition-all duration-300 hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-700 to-blue-800"></div>
                <div class="relative p-8 text-center text-white z-10">
                    <div class="w-20 h-20 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-hand-holding-heart text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">مراكز مساعدة</h3>
                    <p class="text-blue-100 text-sm">جمعيات خيرية، دعم اجتماعي، إغاثة</p>
                    <div class="mt-4 inline-flex items-center gap-2 text-sm bg-white/20 rounded-full px-4 py-1.5 group-hover:bg-white/30 transition-all">
                        <span>استعرض</span> <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                </div>
            </a>
        </div>
    </section>

    {{-- TOP RATED BUSINESSES SLIDER --}}
    @if(isset($topRatedBusinesses) && count($topRatedBusinesses) > 0)
    <section class="bg-slate-50 py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center mb-8 flex-wrap gap-4">
                <div>
                    <h2 class="text-2xl md:text-3xl font-black text-slate-900 flex items-center gap-2">
                        <i class="fas fa-fire-flame text-orange-500 animate-pulse"></i> المنشآت الأكثر تقييماً
                    </h2>
                    <p class="text-slate-500 mt-1">الأكثر طلباً وتفاعلاً في دليلنا</p>
                </div>
                <div class="flex gap-2">
                    <button class="trending-prev w-10 h-10 bg-white rounded-full shadow-md hover:bg-emerald-600 hover:text-white transition-all"><i class="fas fa-chevron-right"></i></button>
                    <button class="trending-next w-10 h-10 bg-white rounded-full shadow-md hover:bg-emerald-600 hover:text-white transition-all"><i class="fas fa-chevron-left"></i></button>
                </div>
            </div>
            <div class="swiper trending-swiper overflow-hidden px-2">
                <div class="swiper-wrapper">
                    @foreach($topRatedBusinesses as $bus)
                    <div class="swiper-slide">
                        <div class="business-card bg-white rounded-2xl overflow-hidden shadow-lg group">
                            <div class="relative h-48 overflow-hidden bg-slate-200">
                                <img src="{{ $bus->cover_url }}" alt="{{ $bus->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy" onerror="this.src='https://placehold.co/600x400/1e293b/10b981?text=🏪'">
                                @if($bus->rating_avg > 0)
                                <div class="absolute bottom-3 left-3 bg-black/60 backdrop-blur-sm rounded-full px-2 py-1">
                                    <i class="fas fa-star text-amber-400 text-xs"></i>
                                    <span class="text-white text-xs font-bold">{{ number_format($bus->rating_avg, 1) }}</span>
                                </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-slate-900 group-hover:text-emerald-600 transition-colors line-clamp-1">
                                    <a href="{{ route('business.show', $bus->slug) }}">{{ $bus->title }}</a>
                                </h3>
                                <p class="text-xs text-slate-500 mt-2 line-clamp-2">{{ Str::limit($bus->description, 60) }}</p>
                                <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-100">
                                    <span class="text-xs text-slate-500"><i class="fas fa-map-marker-alt ml-1"></i> {{ $bus->location->name ?? 'سوريا' }}</span>
                                    <a href="{{ route('business.show', $bus->slug) }}" class="text-emerald-600 text-xs font-bold hover:underline">تفاصيل <i class="fas fa-arrow-left mr-1"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- MAIN BUSINESSES GRID --}}
    <section id="businesses" class="max-w-7xl mx-auto px-4 py-12">
        <div class="mb-8">
            <h2 class="text-2xl md:text-3xl font-black text-slate-900 flex items-center gap-2">
                <i class="fas fa-store text-emerald-600"></i>
                @if(isset($selectedCategory) && $selectedCategory) منشآت {{ $selectedCategory->name }} @else أحدث المنشآت @endif
            </h2>
            <p class="text-slate-500 mt-1">اكتشف أحدث الأنشطة التجارية المضافة إلى دليلنا</p>
        </div>
        
        <div id="businesses-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @include('partials.business-cards', ['businesses' => $featuredBusinesses])
        </div>
        
        <div id="loading-spinner" class="text-center py-8 hidden"><div class="loader mx-auto w-10 h-10 border-4 border-slate-200 border-t-emerald-600 rounded-full animate-spin"></div><p class="text-slate-500 text-sm mt-3">جاري تحميل المزيد...</p></div>
        
        @if($featuredBusinesses->hasMorePages())
        <div class="text-center mt-10">
            <button id="load-more" data-page="2" class="inline-flex items-center gap-2 bg-white border-2 border-emerald-600 text-emerald-600 hover:bg-emerald-600 hover:text-white font-bold px-8 py-3 rounded-xl transition-all duration-300 transform hover:scale-105">
                <i class="fas fa-sync-alt"></i> تحميل المزيد <i class="fas fa-arrow-down"></i>
            </button>
        </div>
        @endif
    </section>

    {{-- LATEST REVIEWS SLIDER --}}
    @if(isset($latestReviews) && count($latestReviews) > 0)
    <section class="bg-gradient-to-r from-emerald-600 to-emerald-700 py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-8">
                <h2 class="text-2xl md:text-3xl font-black text-white flex items-center justify-center gap-2">
                    <i class="fas fa-star text-amber-300"></i> آراء الزوار <i class="fas fa-star text-amber-300"></i>
                </h2>
                <p class="text-emerald-100 mt-1">ما يقوله الناس عن المنشآت في دليلنا</p>
            </div>
            <div class="swiper reviews-swiper overflow-hidden">
                <div class="swiper-wrapper">
                    @foreach($latestReviews as $review)
                    <div class="swiper-slide">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 m-2">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center"><i class="fas fa-user text-white text-lg"></i></div>
                                <div><div class="font-bold text-white">{{ $review->reviewer_name ?? 'زائر' }}</div>
                                <div class="text-amber-300 text-xs">@for($i=1;$i<=5;$i++)<i class="fas fa-star {{ $i <= $review->rating ? 'text-amber-400' : 'text-white/30' }} text-xs"></i>@endfor</div></div>
                            </div>
                            <p class="text-white/90 text-sm leading-relaxed">" {{ Str::limit($review->comment, 120) }} "</p>
                            <div class="mt-3 text-xs text-white/50"><i class="far fa-clock ml-1"></i> {{ $review->created_at->diffForHumans() }} <span class="mx-2">•</span> <a href="{{ route('business.show', $review->business->slug ?? '#') }}" class="text-emerald-300 hover:text-white transition-colors">{{ $review->business->title ?? 'منشأة' }}</a></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="swiper-pagination !static mt-6"></div>
            </div>
        </div>
    </section>
    @endif

    {{-- NEWSLETTER SECTION --}}
    <section class="max-w-7xl mx-auto px-4 py-12">
        <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-3xl overflow-hidden shadow-xl">
            <div class="grid grid-cols-1 lg:grid-cols-2">
                <div class="p-8 md:p-12 text-center lg:text-right">
                    <div class="inline-flex items-center gap-2 bg-emerald-500/20 rounded-full px-3 py-1 mb-4"><i class="fas fa-bell text-emerald-400 text-xs"></i><span class="text-emerald-400 text-xs font-bold">لا تفوت الفرص</span></div>
                    <h3 class="text-2xl md:text-3xl font-black text-white mb-3">اشترك في النشرة البريدية</h3>
                    <p class="text-slate-400 mb-6">احصل على أحدث المنشآت والعروض الحصرية مباشرة إلى بريدك الإلكتروني</p>
                    <form class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto lg:mx-0">
                        <input type="email" placeholder="بريدك الإلكتروني" class="flex-1 bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-slate-400 focus:outline-none focus:border-emerald-500">
                        <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-6 py-3 rounded-xl transition-all">اشترك الآن</button>
                    </form>
                </div>
                <div class="relative bg-emerald-600 p-8 md:p-12 flex items-center justify-center overflow-hidden">
                    <i class="fas fa-envelope-open-text absolute text-8xl text-white/10"></i>
                    <div class="relative z-10 text-center"><div class="text-5xl mb-3 floating">📧</div><p class="text-white/90 text-sm">نرسل مرة واحدة أسبوعياً، يمكنك إلغاء الاشتراك في أي وقت</p></div>
                </div>
            </div>
        </div>
    </section>

    {{-- SCROLL TO TOP BUTTON --}}
    <div class="scroll-top" id="scrollTop"><i class="fas fa-arrow-up text-white"></i></div>

    {{-- FOOTER --}}
    <footer class="bg-slate-900 text-slate-400 pt-16 pb-8 mt-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                <div><div class="flex items-center gap-2 mb-4"><div class="w-8 h-8 bg-emerald-600 rounded-lg flex items-center justify-center"><i class="fas fa-chart-line text-white text-sm"></i></div><h3 class="text-white font-bold text-lg">{{ $siteName ?? 'دليل سوريا التجاري' }}</h3></div><p class="text-sm leading-relaxed">دليلك الشامل للأعمال والخدمات في سوريا. نساعدك على اكتشاف أفضل المنشآت بالقرب منك.</p></div>
                <div><h4 class="text-white font-bold text-sm mb-4">روابط سريعة</h4><ul class="space-y-2 text-sm"><li><a href="{{ route('home') }}" class="hover:text-white transition-colors"><i class="fas fa-chevron-left ml-2 text-[10px]"></i> الرئيسية</a></li><li><a href="{{ route('business.create') }}" class="hover:text-white transition-colors"><i class="fas fa-chevron-left ml-2 text-[10px]"></i> أضف منشأتك</a></li><li><a href="{{ route('search') }}" class="hover:text-white transition-colors"><i class="fas fa-chevron-left ml-2 text-[10px]"></i> البحث المتقدم</a></li></ul></div>
                <div><h4 class="text-white font-bold text-sm mb-4">خدمات رسمية</h4><ul class="space-y-2 text-sm"><li><a href="{{ route('official.government') }}" class="hover:text-green-400 transition-colors">🏛️ مؤسسات حكومية</a></li><li><a href="{{ route('official.security') }}" class="hover:text-red-400 transition-colors">🛡️ الأمن والنجدة</a></li><li><a href="{{ route('official.help') }}" class="hover:text-blue-400 transition-colors">🤝 مراكز مساعدة</a></li></ul></div>
                <div><h4 class="text-white font-bold text-sm mb-4">تواصل معنا</h4><ul class="space-y-2 text-sm"><li class="flex items-center gap-2"><i class="fas fa-envelope text-emerald-400"></i><a href="mailto:{{ $contactEmail ?? 'info@example.com' }}" class="hover:text-white transition-colors">{{ $contactEmail ?? 'info@example.com' }}</a></li></ul></div>
            </div>
            <div class="border-t border-slate-800 pt-8 text-center text-xs"><p>{{ $footerText ?? 'جميع الحقوق محفوظة © دليل سوريا التجاري' }}</p></div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Typed Text
            const typedElement = document.querySelector('.typed-text');
            const texts = ['اكتشف أفضل المنشآت في سوريا', 'دليلك الموثوق للأعمال والخدمات', 'أضف منشأتك وتواصل مع آلاف العملاء'];
            let textIndex = 0, charIndex = 0, isDeleting = false;
            function typeText() {
                const currentText = texts[textIndex];
                typedElement.textContent = isDeleting ? currentText.substring(0, charIndex - 1) : currentText.substring(0, charIndex + 1);
                isDeleting ? charIndex-- : charIndex++;
                if (!isDeleting && charIndex === currentText.length) { isDeleting = true; setTimeout(typeText, 2000); }
                else if (isDeleting && charIndex === 0) { isDeleting = false; textIndex = (textIndex + 1) % texts.length; setTimeout(typeText, 500); }
                else { setTimeout(typeText, isDeleting ? 50 : 100); }
            }
            typeText();
            
            // Counter Animation
            const statItems = document.querySelectorAll('.stat-item');
            const counterObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const element = entry.target, target = parseInt(element.dataset.count), numberElement = element.querySelector('.stat-number');
                        let current = 0; const increment = target / 50;
                        const timer = setInterval(() => {
                            current += increment;
                            if (current >= target) { numberElement.textContent = target.toLocaleString(); clearInterval(timer); }
                            else { numberElement.textContent = Math.floor(current).toLocaleString(); }
                        }, 20);
                        counterObserver.unobserve(element);
                    }
                });
            }, { threshold: 0.5 });
            statItems.forEach(item => counterObserver.observe(item));
            
            // Swipers
            new Swiper('.trending-swiper', { slidesPerView: 1, spaceBetween: 20, loop: true, autoplay: { delay: 4000, disableOnInteraction: false }, navigation: { nextEl: '.trending-next', prevEl: '.trending-prev' }, breakpoints: { 640: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } } });
            new Swiper('.reviews-swiper', { slidesPerView: 1, spaceBetween: 20, loop: true, autoplay: { delay: 5000, disableOnInteraction: false }, pagination: { el: '.swiper-pagination', clickable: true }, breakpoints: { 768: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } } });
            
            // Load More AJAX
            let currentPage = 2; const loadMoreBtn = document.getElementById('load-more'); const container = document.getElementById('businesses-container'); const spinner = document.getElementById('loading-spinner');
            if (loadMoreBtn) {
                loadMoreBtn.addEventListener('click', async function() {
                    const url = new URL(window.location.href); url.searchParams.set('page', currentPage);
                    loadMoreBtn.disabled = true; spinner.classList.remove('hidden');
                    try {
                        const response = await fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                        const html = await response.text(); const tempDiv = document.createElement('div'); tempDiv.innerHTML = html;
                        const newCards = tempDiv.querySelector('#businesses-container').innerHTML;
                        container.insertAdjacentHTML('beforeend', newCards); currentPage++;
                        if (!html.includes('load-more')) loadMoreBtn.remove();
                    } catch (error) { console.error('Error:', error); }
                    finally { loadMoreBtn.disabled = false; spinner.classList.add('hidden'); }
                });
            }
            
            // Scroll to Top
            const scrollTopBtn = document.getElementById('scrollTop');
            window.addEventListener('scroll', () => { scrollTopBtn.classList.toggle('show', window.scrollY > 300); });
            scrollTopBtn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
        });
    </script>
</body>
</html>