<aside class="w-64 bg-slate-900 text-slate-300 fixed h-full right-0 top-0 flex flex-col justify-between border-l border-slate-800 shadow-xl z-50">
    <div>
        <!-- شعار المنصة -->
        <div class="p-6 border-b border-slate-800 flex items-center justify-center gap-2">
            <span class="text-2xl">🇸🇾</span>
            <span class="font-bold text-lg text-white tracking-wide">دليل سوريا التجاري</span>
        </div>

        <!-- القائمة البرمجية للتنقل -->
        <nav class="p-4 space-y-1.5">
            <!-- الرئيسة / الإحصائيات -->
            <a href="/admin/dashboard" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all {{ request()->is('admin/dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'hover:bg-slate-800 hover:text-white' }}">
                <span class="text-lg">📊</span>
                <span>الإحصائيات العامة</span>
            </a>

            <!-- المدن والمناطق -->
            <a href="/admin/locations" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all {{ request()->is('admin/locations*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'hover:bg-slate-800 hover:text-white' }}">
                <span class="text-lg">📍</span>
                <span>المدن والمناطق</span>
            </a>

            <!-- التصنيفات -->
            <a href="/admin/categories" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all {{ request()->is('admin/categories*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'hover:bg-slate-800 hover:text-white' }}">
                <span class="text-lg">📁</span>
                <span>تصنيفات الأنشطة</span>
            </a>

            <!-- مراجعات وتقييمات العملاء -->
            <a href="/admin/reviews" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all {{ request()->is('admin/reviews*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'hover:bg-slate-800 hover:text-white' }}">
                <span class="text-lg">⭐</span>
                <span>إدارة التقييمات</span>
            </a>

            <!-- المساحات الإعلانية -->
            <a href="/admin/ads" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all {{ request()->is('admin/ads*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'hover:bg-slate-800 hover:text-white' }}">
                <span class="text-lg">📢</span>
                <span>المساحات الإعلانية</span>
            </a>
        </nav>
    </div>

    <!-- الجزء السفلي (تسجيل الخروج أو الملف الشخصي) -->
    <div class="p-4 border-t border-slate-800">
        <form action="/logout" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-red-400 hover:bg-red-950/30 hover:text-red-300 transition-all cursor-pointer">
                <span class="text-lg">🚪</span>
                <span>تسجيل الخروج</span>
            </button>
        </form>
    </div>
</aside>