<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <link rel="manifest" href="{{ asset('manifest.json') }}">
<meta name="theme-color" content="#1a73e8">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- SEO Meta Tags --}}
    @hasSection('seo')
        @yield('seo')
    @else
        @isset($seo)
            {!! $seo->render() !!}
        @else
            <title>{{ \App\Models\Setting::get('site_name', 'دليل سوريا التجاري') }}</title>
            <meta name="description" content="{{ \App\Models\Setting::get('site_description', 'دليلك الشامل للأعمال في سوريا') }}">
        @endisset
    @endif
    
    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    {{-- TailwindCSS + Font Awesome --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    {{-- Custom Styles (Animations) --}}
    <style>
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
        .floating { animation: float 3s ease-in-out infinite; }
        
        .business-card { transition: all 0.3s ease; }
        .business-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.2); }
        
        .category-card { transition: all 0.3s ease; }
        .category-card:hover { transform: translateY(-5px); background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .category-card:hover .category-icon,
        .category-card:hover .category-name,
        .category-card:hover .category-count { color: white !important; }
        
        .hero-gradient { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); }
        
        .line-clamp-1 { display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #10b981; border-radius: 10px; }
        
        /* تحسينات للموبايل */
        .mobile-menu {
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
        }
        .mobile-menu.open {
            transform: translateX(0);
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans antialiased">

    {{-- Header --}}
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <nav class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-2xl font-bold text-emerald-600">
                <i class="fas fa-map-marked-alt ml-2"></i>
                {{ \App\Models\Setting::get('site_name', 'دليل سوريا') }}
            </a>
            
            {{-- Desktop Menu --}}
            <div class="hidden md:flex space-x-6 space-x-reverse">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-emerald-600">الرئيسية</a>
                <a href="{{ route('business.create') }}" class="text-gray-700 hover:text-emerald-600">أضف منشأتك</a>
                <a href="{{ route('official.government') }}" class="text-gray-700 hover:text-emerald-600">المؤسسات الحكومية</a>
                <a href="{{ route('official.security') }}" class="text-gray-700 hover:text-emerald-600">الأمن والنجدة</a>
                <a href="{{ route('official.help') }}" class="text-gray-700 hover:text-emerald-600">مراكز المساعدة</a>
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="text-emerald-600 font-semibold">لوحة التحكم</a>
                    @endif
                @endauth
            </div>
            
            <div class="flex items-center space-x-3 space-x-reverse">
                <form action="{{ route('search') }}" method="GET" class="hidden md:block">
                    <input type="text" name="search" placeholder="بحث..." class="border rounded-full px-4 py-1 text-sm">
                </form>
                <button id="mobile-menu-btn" class="md:hidden text-gray-700 focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </nav>
        
       {{-- Mobile Menu Slider --}}
<div id="mobile-menu" class="fixed top-0 left-0 h-full w-64 bg-white shadow-xl z-50 mobile-menu p-6" style="transform: translateX(-100%); transition: transform 0.3s ease-in-out;">
    <div class="flex justify-between items-center mb-6 pb-3 border-b">
        <span class="text-lg font-bold text-emerald-600">القائمة</span>
        <button id="close-mobile-menu" class="text-gray-500 hover:text-red-500">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>
    <ul class="space-y-4">
        <li><a href="{{ route('home') }}" class="block text-gray-700 hover:text-emerald-600">الرئيسية</a></li>
        <li><a href="{{ route('business.create') }}" class="block text-gray-700 hover:text-emerald-600">أضف منشأتك</a></li>
        <li><a href="{{ route('official.government') }}" class="block text-gray-700 hover:text-emerald-600">المؤسسات الحكومية</a></li>
        <li><a href="{{ route('official.security') }}" class="block text-gray-700 hover:text-emerald-600">الأمن والنجدة</a></li>
        <li><a href="{{ route('official.help') }}" class="block text-gray-700 hover:text-emerald-600">مراكز المساعدة</a></li>
        @auth
            @if(auth()->user()->isAdmin())
                <li><a href="{{ route('admin.dashboard') }}" class="block text-emerald-600 font-semibold">لوحة التحكم</a></li>
            @endif
        @endauth
        <li class="pt-4">
            <form action="{{ route('search') }}" method="GET" class="flex">
                <input type="text" name="search" placeholder="بحث..." class="flex-1 border rounded-r-full px-3 py-1 text-sm">
                <button type="submit" class="bg-emerald-600 text-white px-3 rounded-l-full"><i class="fas fa-search"></i></button>
            </form>
        </li>
    </ul>
</div>
<div id="mobile-overlay" class="fixed inset-0 bg-black/50 z-40 hidden"></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const closeBtn = document.getElementById('close-mobile-menu');
        const overlay = document.getElementById('mobile-overlay');
        
        function openMenu() {
            mobileMenu.style.transform = 'translateX(0)';
            overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closeMenu() {
            mobileMenu.style.transform = 'translateX(-100%)';
            overlay.classList.add('hidden');
            document.body.style.overflow = '';
        }
        if(mobileBtn) mobileBtn.addEventListener('click', openMenu);
        if(closeBtn) closeBtn.addEventListener('click', closeMenu);
        if(overlay) overlay.addEventListener('click', closeMenu);
    });
</script>

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-white py-8 mt-12">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">{{ \App\Models\Setting::get('site_name', 'دليل سوريا') }}</h3>
                    <p class="text-gray-400 text-sm">{{ \App\Models\Setting::get('site_description', 'دليلك الشامل للأعمال في سوريا') }}</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-3">روابط سريعة</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('home') }}" class="hover:text-white">الرئيسية</a></li>
                        <li><a href="{{ route('business.create') }}" class="hover:text-white">إضافة منشأة</a></li>
                        <li><a href="/sitemap.xml" class="hover:text-white">خريطة الموقع</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-3">تواصل معنا</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><i class="fas fa-envelope ml-2"></i> {{ \App\Models\Setting::get('contact_email', 'info@example.com') }}</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-3">تابعنا</h4>
                    <div class="flex space-x-4 space-x-reverse">
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-6 pt-4 text-center text-gray-500 text-sm">
                {{ \App\Models\Setting::get('footer_text', 'جميع الحقوق محفوظة') }} &copy; {{ date('Y') }}
            </div>
        </div>
    </footer>
    
    @stack('scripts')
    <script>
  // تسجيل Service Worker
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
      navigator.serviceWorker.register('{{ asset("service-worker.js") }}')
        .then(function(registration) {
          console.log('Service Worker registered successfully');
        })
        .catch(function(error) {
          console.log('Service Worker registration failed:', error);
        });
    });
  }
</script>
</body>
</html>