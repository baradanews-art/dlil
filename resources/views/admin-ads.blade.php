<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الإعلانات | لوحة التحكم</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #1e293b; }
        ::-webkit-scrollbar-thumb { background: #10b981; border-radius: 10px; }
        .sidebar-transition { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        @media (max-width: 768px) {
            .sidebar-mobile-hidden { transform: translateX(100%); }
            .sidebar-mobile-visible { transform: translateX(0); }
        }
    </style>
</head>
<body class="bg-slate-950 font-sans antialiased">

    <button id="mobileMenuBtn" class="lg:hidden fixed top-4 right-4 z-50 bg-emerald-600 text-white p-3 rounded-xl shadow-lg">
        <i class="fas fa-bars text-xl"></i>
    </button>

    <div class="flex min-h-screen">
        
        {{-- Sidebar --}}
        <aside id="sidebar" class="fixed lg:static inset-y-0 right-0 z-40 w-72 bg-slate-900 border-l border-slate-800 flex flex-col sidebar-transition transform lg:transform-none sidebar-mobile-hidden">
            <div class="p-6 border-b border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-line text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-black text-white">لوحة الإشراف</h2>
                        <p class="text-[10px] text-emerald-400">إدارة الإعلانات</p>
                    </div>
                </div>
                <button id="closeSidebar" class="lg:hidden absolute top-4 left-4 text-slate-400 hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <nav class="flex-1 px-4 space-y-1 mt-4">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                    <i class="fas fa-tachometer-alt w-5"></i> <span class="text-sm font-bold">لوحة التحكم</span>
                </a>
                <a href="{{ route('admin.businesses.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                    <i class="fas fa-store w-5"></i> <span class="text-sm font-bold">المنشآت التجارية</span>
                </a>
                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                    <i class="fas fa-tags w-5"></i> <span class="text-sm font-bold">التصنيفات</span>
                </a>
                <a href="{{ route('admin.locations.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                    <i class="fas fa-map-marker-alt w-5"></i> <span class="text-sm font-bold">المواقع الجغرافية</span>
                </a>
                <a href="{{ route('admin.reviews.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                    <i class="fas fa-star w-5"></i> <span class="text-sm font-bold">التقييمات</span>
                </a>
                <a href="{{ route('admin.ads.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-600/20 text-emerald-400 transition-all">
                    <i class="fas fa-ad w-5"></i> <span class="text-sm font-bold">الإعلانات</span>
                </a>
            </nav>
            
            <div class="p-4 border-t border-slate-800 mt-auto">
                <a href="{{ route('home') }}" target="_blank" class="flex items-center justify-center gap-2 w-full bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold py-2.5 rounded-xl transition-all">
                    <i class="fas fa-external-link-alt"></i> عرض الموقع العام
                </a>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 overflow-x-hidden">
            <div class="bg-slate-900 border-b border-slate-800 px-6 py-4 sticky top-0 z-30">
                <div class="flex justify-between items-center flex-wrap gap-3">
                    <div>
                        <h1 class="text-lg font-black text-white flex items-center gap-2">
                            <i class="fas fa-ad text-emerald-400"></i>
                            إدارة الإعلانات
                        </h1>
                        <p class="text-xs text-slate-400 mt-0.5">إضافة وإدارة البنرات الإعلانية</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                @if(session('success'))
                    <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 p-4 rounded-xl text-sm mb-6 flex items-center gap-2">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    {{-- Add Ad Form --}}
                    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6">
                        <h3 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                            <i class="fas fa-plus-circle text-emerald-400"></i>
                            إضافة إعلان جديد
                        </h3>
                        <form action="{{ route('admin.ads.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-slate-400 mb-2">عنوان الإعلان *</label>
                                <input type="text" name="title" required placeholder="مثال: إعلان مفروشات النور" class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-3 rounded-xl focus:outline-none focus:border-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 mb-2">رابط التوجيه</label>
                                <input type="url" name="link_url" placeholder="https://example.com" class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-3 rounded-xl focus:outline-none focus:border-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 mb-2">مكان الظهور *</label>
                                <select name="position" class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-3 rounded-xl focus:outline-none focus:border-emerald-500">
                                    <option value="sidebar">📱 القائمة الجانبية (Sidebar)</option>
                                    <option value="home_top">📺 أعلى الصفحة الرئيسية (Top Banner)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 mb-2">صورة الإعلان *</label>
                                <input type="file" name="image" required accept="image/*" class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-2 rounded-xl focus:outline-none focus:border-emerald-500">
                                <p class="text-[10px] text-slate-500 mt-1">JPG, PNG, WEBP (max 2MB)</p>
                            </div>
                            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl transition-all">
                                <i class="fas fa-upload ml-2"></i> رفع الإعلان
                            </button>
                        </form>
                    </div>
                    
                    {{-- Ads List --}}
                    <div class="lg:col-span-2 bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-800">
                            <h3 class="text-sm font-bold text-white flex items-center gap-2">
                                <i class="fas fa-list text-emerald-400"></i>
                                الإعلانات النشطة ({{ count($ads) }})
                            </h3>
                        </div>
                        <div class="divide-y divide-slate-800">
                            @forelse($ads as $ad)
                            <div class="p-4 flex flex-wrap items-center gap-4 hover:bg-slate-800/30 transition-all">
                                <div class="w-32 h-20 bg-slate-800 rounded-xl overflow-hidden flex-shrink-0">
                                    @if($ad->image_path && file_exists(base_path('storage/app/public/' . $ad->image_path)))
                                        <img src="{{ url('/storage/' . $ad->image_path) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-slate-700 flex items-center justify-center">
                                            <i class="fas fa-image text-slate-500 text-2xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-bold text-white">{{ $ad->title }}</h4>
                                    <div class="flex flex-wrap gap-3 mt-1">
                                        <span class="text-xs text-slate-400">
                                            <i class="fas fa-link ml-1"></i> {{ Str::limit($ad->link_url ?? 'بدون رابط', 40) }}
                                        </span>
                                        <span class="text-xs {{ $ad->position == 'home_top' ? 'text-emerald-400' : 'text-sky-400' }}">
                                            <i class="fas {{ $ad->position == 'home_top' ? 'fa-tv' : 'fa-bars' }} ml-1"></i>
                                            {{ $ad->position == 'home_top' ? 'أعلى الصفحة' : 'القائمة الجانبية' }}
                                        </span>
                                        <span class="text-xs text-slate-500">
                                            <i class="far fa-calendar-alt ml-1"></i> {{ $ad->created_at->format('Y-m-d') }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <form action="{{ route('admin.ads.destroy', $ad->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الإعلان؟')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500/20 hover:bg-red-500 text-red-400 hover:text-white px-3 py-2 rounded-lg transition-all">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @empty
                            <div class="p-12 text-center text-slate-500">
                                <i class="fas fa-ad text-5xl mb-3 block opacity-50"></i>
                                <p>لا توجد إعلانات مضافة حالياً</p>
                                <p class="text-xs mt-1">أضف إعلانك الأول من النموذج المجاور</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            document.getElementById('mobileMenuBtn')?.addEventListener('click', () => {
                sidebar.classList.remove('sidebar-mobile-hidden');
                sidebar.classList.add('sidebar-mobile-visible');
            });
            document.getElementById('closeSidebar')?.addEventListener('click', () => {
                sidebar.classList.add('sidebar-mobile-hidden');
                sidebar.classList.remove('sidebar-mobile-visible');
            });
        });
    </script>
</body>
</html>