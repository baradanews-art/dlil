<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المؤسسات الرسمية | لوحة التحكم</title>
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
                        <p class="text-[10px] text-emerald-400">إدارة المؤسسات الرسمية</p>
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
                <a href="{{ route('admin.ads.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                    <i class="fas fa-ad w-5"></i> <span class="text-sm font-bold">الإعلانات</span>
                </a>
                <a href="{{ route('admin.official.index', ['type' => $type ?? 'government']) }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-600/20 text-emerald-400 transition-all">
                    <i class="fas fa-landmark w-5"></i> <span class="text-sm font-bold">المؤسسات الرسمية</span>
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
                            <i class="fas fa-landmark text-emerald-400"></i>
                            إدارة المؤسسات الرسمية
                        </h1>
                        <p class="text-xs text-slate-400 mt-0.5">إضافة وتعديل وحذف المؤسسات الحكومية والأمنية ومراكز المساعدة</p>
                    </div>
                    <div class="flex gap-2">
                        <div class="flex bg-slate-800 rounded-xl overflow-hidden">
                            <a href="{{ route('admin.official.index', ['type' => 'government']) }}" 
                               class="px-4 py-2 text-xs font-bold {{ ($type ?? 'government') == 'government' ? 'bg-emerald-600 text-white' : 'text-slate-400 hover:text-white' }} transition-all">
                                🏛️ حكومية
                            </a>
                            <a href="{{ route('admin.official.index', ['type' => 'security']) }}" 
                               class="px-4 py-2 text-xs font-bold {{ ($type ?? 'government') == 'security' ? 'bg-emerald-600 text-white' : 'text-slate-400 hover:text-white' }} transition-all">
                                🛡️ أمن ونجدة
                            </a>
                            <a href="{{ route('admin.official.index', ['type' => 'help']) }}" 
                               class="px-4 py-2 text-xs font-bold {{ ($type ?? 'government') == 'help' ? 'bg-emerald-600 text-white' : 'text-slate-400 hover:text-white' }} transition-all">
                                🤝 مراكز مساعدة
                            </a>
                        </div>
                        <a href="{{ route('admin.official.create', ['type' => $type ?? 'government']) }}" 
                           class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl text-xs font-bold transition-all">
                            <i class="fas fa-plus ml-1"></i> إضافة جديدة
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                @if(session('success'))
                    <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 p-4 rounded-xl text-sm mb-6 flex items-center gap-2">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif
                
                <div class="bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-800">
                        <h3 class="text-sm font-bold text-white flex items-center gap-2">
                            <i class="fas fa-list text-emerald-400"></i>
                            قائمة المؤسسات ({{ count($entities ?? []) }})
                        </h3>
                    </div>
                    
                    @if(isset($entities) && count($entities) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-right">
                            <thead class="bg-slate-800/50">
                                <tr class="text-xs text-slate-400 border-b border-slate-800">
                                    <th class="px-6 py-3 font-bold">#</th>
                                    <th class="px-6 py-3 font-bold">الشعار</th>
                                    <th class="px-6 py-3 font-bold">الاسم</th>
                                    <th class="px-6 py-3 font-bold">النوع</th>
                                    <th class="px-6 py-3 font-bold">الهاتف</th>
                                    <th class="px-6 py-3 font-bold">طوارئ</th>
                                    <th class="px-6 py-3 font-bold">المحافظة</th>
                                    <th class="px-6 py-3 font-bold">المنطقة</th>
                                    <th class="px-6 py-3 font-bold text-center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800">
                                @foreach($entities as $index => $entity)
                                <tr class="hover:bg-slate-800/30 transition-colors">
                                    <td class="px-6 py-4 text-xs text-slate-500">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">
                                        @if($entity->logo)
                                            <img src="{{ asset($entity->logo) }}" class="w-10 h-10 rounded-lg object-cover">
                                        @else
                                            <div class="w-10 h-10 bg-slate-700 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-building text-slate-500"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-white">{{ $entity->name }}</td>
                                    <td class="px-6 py-4">
                                        <span class="text-xs px-2 py-1 rounded-full 
                                            @if($entity->type == 'government') bg-green-500/20 text-green-400
                                            @elseif($entity->type == 'security') bg-red-500/20 text-red-400
                                            @else bg-blue-500/20 text-blue-400 @endif">
                                            @if($entity->type == 'government') حكومية
                                            @elseif($entity->type == 'security') أمن ونجدة
                                            @else مراكز مساعدة @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-300">{{ $entity->phone ?? '—' }}</td>
                                    <td class="px-6 py-4 text-xs text-slate-300">{{ $entity->hotline ?? '—' }}</td>
                                    <td class="px-6 py-4 text-xs text-slate-300">{{ $entity->city->name ?? '—' }}</td>
                                    <td class="px-6 py-4 text-xs text-slate-300">{{ $entity->region->name ?? '—' }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('admin.official.edit', $entity->id) }}" 
                                           class="bg-blue-500/20 hover:bg-blue-500 text-blue-400 hover:text-white px-3 py-1.5 rounded-lg text-xs transition-all ml-2">
                                            <i class="fas fa-edit ml-1"></i> تعديل
                                        </a>
                                        <form action="{{ route('admin.official.destroy', $entity->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه المؤسسة؟')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500/20 hover:bg-red-500 text-red-400 hover:text-white px-3 py-1.5 rounded-lg text-xs transition-all">
                                                <i class="fas fa-trash ml-1"></i> حذف
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-12">
                        <i class="fas fa-building text-5xl text-slate-600 mb-3 block"></i>
                        <p class="text-slate-500">لا توجد مؤسسات مضافة حالياً</p>
                        <a href="{{ route('admin.official.create', ['type' => $type ?? 'government']) }}" class="inline-block mt-4 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl text-sm">
                            <i class="fas fa-plus ml-1"></i> أضف أول مؤسسة
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <script>
        // Mobile sidebar
        const sidebar = document.getElementById('sidebar');
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const closeSidebar = document.getElementById('closeSidebar');
        
        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', () => {
                sidebar.classList.remove('sidebar-mobile-hidden');
                sidebar.classList.add('sidebar-mobile-visible');
            });
        }
        
        if (closeSidebar) {
            closeSidebar.addEventListener('click', () => {
                sidebar.classList.add('sidebar-mobile-hidden');
                sidebar.classList.remove('sidebar-mobile-visible');
            });
        }
    </script>
</body>
</html>