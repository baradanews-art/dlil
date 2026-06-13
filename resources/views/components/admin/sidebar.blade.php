<aside id="sidebar" class="fixed lg:static inset-y-0 right-0 z-40 w-72 bg-slate-900 border-l border-slate-800 flex flex-col sidebar-transition transform lg:transform-none sidebar-mobile-hidden">
    
    {{-- Sidebar Header --}}
    <div class="p-6 border-b border-slate-800">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-chart-line text-white text-lg"></i>
            </div>
            <div>
                <h2 class="text-sm font-black text-white">لوحة الإشراف</h2>
                <p class="text-[10px] text-emerald-400">دليل سوريا التجاري</p>
            </div>
        </div>
        <button id="closeSidebar" class="lg:hidden absolute top-4 left-4 text-slate-400 hover:text-white">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>
    
    {{-- Admin Info --}}
    <div class="p-4 mx-4 my-4 bg-slate-800/50 rounded-xl border border-slate-700">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-emerald-500/20 rounded-full flex items-center justify-center">
                <i class="fas fa-user-shield text-emerald-400 text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-white">{{ Auth::user()->name ?? 'مدير النظام' }}</p>
                <p class="text-[10px] text-slate-400">{{ Auth::user()->email ?? 'admin@example.com' }}</p>
            </div>
        </div>
    </div>
    
    {{-- Navigation --}}
    <nav class="flex-1 px-4 space-y-1">
        <a href="{{ route('admin.dashboard') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-600/20 text-emerald-400' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fas fa-tachometer-alt w-5"></i>
            <span class="text-sm font-bold">لوحة التحكم</span>
            @if(isset($stats['pending']) && $stats['pending'] > 0)
                <span class="mr-auto bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $stats['pending'] }}</span>
            @endif
        </a>
        
        <a href="{{ route('admin.businesses.index') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.businesses*') ? 'bg-emerald-600/20 text-emerald-400' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fas fa-store w-5"></i>
            <span class="text-sm font-bold">المنشآت التجارية</span>
        </a>
        
        <a href="{{ route('admin.categories.index') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.categories*') ? 'bg-emerald-600/20 text-emerald-400' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fas fa-tags w-5"></i>
            <span class="text-sm font-bold">التصنيفات</span>
        </a>
        
        <a href="{{ route('admin.locations.index') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.locations*') ? 'bg-emerald-600/20 text-emerald-400' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fas fa-map-marker-alt w-5"></i>
            <span class="text-sm font-bold">المواقع الجغرافية</span>
        </a>
        
        <a href="{{ route('admin.reviews.index') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.reviews*') ? 'bg-emerald-600/20 text-emerald-400' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fas fa-star w-5"></i>
            <span class="text-sm font-bold">التقييمات</span>
        </a>
        
        <a href="{{ route('admin.ads.index') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.ads*') ? 'bg-emerald-600/20 text-emerald-400' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fas fa-ad w-5"></i>
            <span class="text-sm font-bold">الإعلانات</span>
        </a>
        
        <div class="pt-2 mt-2 border-t border-slate-800">
            <p class="text-[10px] text-slate-500 px-4 py-2">المؤسسات الرسمية</p>
        </div>
        
        <a href="{{ route('admin.official.index', ['type' => 'government']) }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.official*') && request('type') == 'government' ? 'bg-emerald-600/20 text-emerald-400' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fas fa-landmark w-5"></i>
            <span class="text-sm font-bold">حكومية</span>
        </a>
        
        <a href="{{ route('admin.official.index', ['type' => 'security']) }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.official*') && request('type') == 'security' ? 'bg-emerald-600/20 text-emerald-400' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fas fa-shield-alt w-5"></i>
            <span class="text-sm font-bold">أمن ونجدة</span>
        </a>
        
        <a href="{{ route('admin.official.index', ['type' => 'help']) }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.official*') && request('type') == 'help' ? 'bg-emerald-600/20 text-emerald-400' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fas fa-hand-holding-heart w-5"></i>
            <span class="text-sm font-bold">مراكز مساعدة</span>
        </a>
    </nav>
    
    {{-- Sidebar Footer --}}
    <div class="p-4 border-t border-slate-800 mt-auto">
        <a href="{{ route('home') }}" target="_blank" 
           class="flex items-center justify-center gap-2 w-full bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold py-2.5 rounded-xl transition-all">
            <i class="fas fa-external-link-alt"></i>
            عرض الموقع العام
        </a>
    </div>
</aside>