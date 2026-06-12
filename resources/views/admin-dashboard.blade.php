<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم الإدارية | دليل سوريا التجاري</title>
    
    {{-- Tailwind CSS --}}
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    {{-- Chart.js للرسوم البيانية --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #1e293b; border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: #10b981; border-radius: 10px; }
        
        /* Sidebar Transition */
        .sidebar-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Card Hover Effect */
        .stat-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.2);
        }
        
        /* Table Row Hover */
        .table-row {
            transition: background-color 0.2s ease;
        }
        .table-row:hover {
            background-color: rgba(16, 185, 129, 0.05);
        }
        
        /* Loading Skeleton */
        .skeleton {
            background: linear-gradient(90deg, #1e293b 25%, #334155 50%, #1e293b 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }
        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        /* Mobile Sidebar */
        @media (max-width: 768px) {
            .sidebar-mobile-hidden {
                transform: translateX(100%);
            }
            .sidebar-mobile-visible {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body class="bg-slate-950 font-sans antialiased">

    {{-- ============================================================ --}}
    {{-- 🔥 MOBILE MENU BUTTON --}}
    {{-- ============================================================ --}}
    <button id="mobileMenuBtn" class="lg:hidden fixed top-4 right-4 z-50 bg-emerald-600 text-white p-3 rounded-xl shadow-lg">
        <i class="fas fa-bars text-xl"></i>
    </button>

    <div class="flex min-h-screen">
        
        {{-- ============================================================ --}}
        {{-- 📁 SIDEBAR - القائمة الجانبية المتطورة --}}
        {{-- ============================================================ --}}
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
                        <p class="text-xs font-bold text-white">مدير النظام</p>
                        <p class="text-[10px] text-slate-400">admin@aza-international.com</p>
                    </div>
                </div>
            </div>
            
            {{-- Navigation --}}
            <nav class="flex-1 px-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-600/20 text-emerald-400 transition-all">
                    <i class="fas fa-tachometer-alt w-5"></i>
                    <span class="text-sm font-bold">لوحة التحكم</span>
                    @if(isset($stats['pending']) && $stats['pending'] > 0)
                        <span class="mr-auto bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $stats['pending'] }}</span>
                    @endif
                </a>
                
                <a href="{{ route('admin.businesses.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                    <i class="fas fa-store w-5"></i>
                    <span class="text-sm font-bold">المنشآت التجارية</span>
                </a>
                
                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                    <i class="fas fa-tags w-5"></i>
                    <span class="text-sm font-bold">التصنيفات</span>
                </a>
                
                <a href="{{ route('admin.locations.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                    <i class="fas fa-map-marker-alt w-5"></i>
                    <span class="text-sm font-bold">المواقع الجغرافية</span>
                </a>
                
                <a href="{{ route('admin.reviews.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                    <i class="fas fa-star w-5"></i>
                    <span class="text-sm font-bold">التقييمات</span>
                </a>
                
                <a href="{{ route('admin.ads.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                    <i class="fas fa-ad w-5"></i>
                    <span class="text-sm font-bold">الإعلانات</span>
                </a>
            </nav>
            
            {{-- Sidebar Footer --}}
            <div class="p-4 border-t border-slate-800 mt-auto">
                <a href="{{ route('home') }}" target="_blank" class="flex items-center justify-center gap-2 w-full bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold py-2.5 rounded-xl transition-all">
                    <i class="fas fa-external-link-alt"></i>
                    عرض الموقع العام
                </a>
            </div>
        </aside>

        {{-- ============================================================ --}}
        {{-- 📊 MAIN CONTENT - المحتوى الرئيسي --}}
        {{-- ============================================================ --}}
        <main class="flex-1 overflow-x-hidden">
            
            {{-- Top Bar --}}
            <div class="bg-slate-900 border-b border-slate-800 px-6 py-4 sticky top-0 z-30">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-lg font-black text-white">لوحة التحكم الإدارية</h1>
                        <p class="text-xs text-slate-400 mt-0.5">مرحباً بك، إليك ملخص نشاط المنصة</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-slate-400 hidden md:block">آخر تحديث: {{ now()->format('Y-m-d H:i') }}</span>
                        <button onclick="window.location.reload()" class="bg-slate-800 hover:bg-slate-700 text-slate-300 p-2 rounded-xl transition-all">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                
                {{-- ============================================================ --}}
                {{-- 📈 STATS CARDS - بطاقات الإحصائيات --}}
                {{-- ============================================================ --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-gradient-to-br from-emerald-600 to-emerald-700 rounded-2xl p-5 stat-card shadow-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-emerald-100 text-xs font-bold uppercase tracking-wider">إجمالي المنشآت</p>
                                <p class="text-white text-3xl font-black mt-2">{{ number_format($stats['total'] ?? 0) }}</p>
                                <p class="text-emerald-200 text-xs mt-2">
                                    <i class="fas fa-arrow-up text-xs"></i>
                                    +12% عن الشهر الماضي
                                </p>
                            </div>
                            <div class="bg-white/20 rounded-xl p-3">
                                <i class="fas fa-store text-white text-2xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-t border-emerald-500/30">
                            <div class="flex justify-between text-xs">
                                <span class="text-emerald-100">✅ نشطة: {{ number_format($stats['approved'] ?? 0) }}</span>
                                <span class="text-amber-200">⏳ معلقة: {{ number_format($stats['pending'] ?? 0) }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl p-5 stat-card shadow-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-blue-100 text-xs font-bold uppercase tracking-wider">التصنيفات</p>
                                <p class="text-white text-3xl font-black mt-2">{{ number_format($stats['categories'] ?? 0) }}</p>
                            </div>
                            <div class="bg-white/20 rounded-xl p-3">
                                <i class="fas fa-tags text-white text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-purple-600 to-purple-700 rounded-2xl p-5 stat-card shadow-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-purple-100 text-xs font-bold uppercase tracking-wider">المدن والمحافظات</p>
                                <p class="text-white text-3xl font-black mt-2">{{ number_format($stats['cities'] ?? 0) }}</p>
                            </div>
                            <div class="bg-white/20 rounded-xl p-3">
                                <i class="fas fa-city text-white text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-amber-600 to-amber-700 rounded-2xl p-5 stat-card shadow-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-amber-100 text-xs font-bold uppercase tracking-wider">التقييمات</p>
                                <p class="text-white text-3xl font-black mt-2">{{ number_format($stats['reviews'] ?? 0) }}</p>
                            </div>
                            <div class="bg-white/20 rounded-xl p-3">
                                <i class="fas fa-star text-white text-2xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-t border-amber-500/30">
                            <div class="flex justify-between text-xs">
                                <span class="text-amber-100">⭐ متوسط التقييم: {{ number_format($stats['avg_rating'] ?? 0, 1) }}/5</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- ============================================================ --}}
                {{-- 📊 CHARTS & QUICK ACTIONS --}}
                {{-- ============================================================ --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    
                    {{-- Chart Card --}}
                    <div class="lg:col-span-2 bg-slate-900 rounded-2xl border border-slate-800 p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-sm font-bold text-white">📊 إحصائيات المنشآت</h3>
                            <div class="flex gap-2">
                                <button id="weeklyBtn" class="text-xs px-3 py-1 rounded-lg bg-emerald-600 text-white transition-all">أسبوعي</button>
                                <button id="monthlyBtn" class="text-xs px-3 py-1 rounded-lg bg-slate-700 text-slate-300 hover:bg-slate-600 transition-all">شهري</button>
                            </div>
                        </div>
                        <canvas id="businessChart" height="250"></canvas>
                    </div>
                    
                    {{-- Quick Actions Card --}}
                    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6">
                        <h3 class="text-sm font-bold text-white mb-4">⚡ إجراءات سريعة</h3>
                        <div class="space-y-3">
                            <a href="{{ route('admin.businesses.index') }}" class="flex items-center justify-between bg-slate-800 hover:bg-slate-700 p-3 rounded-xl transition-all">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-emerald-500/20 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-plus text-emerald-400 text-sm"></i>
                                    </div>
                                    <span class="text-sm font-bold text-white">إضافة منشأة جديدة</span>
                                </div>
                                <i class="fas fa-chevron-left text-slate-500 text-xs"></i>
                            </a>
                            <a href="{{ route('admin.categories.index') }}" class="flex items-center justify-between bg-slate-800 hover:bg-slate-700 p-3 rounded-xl transition-all">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-purple-500/20 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-tag text-purple-400 text-sm"></i>
                                    </div>
                                    <span class="text-sm font-bold text-white">إدارة التصنيفات</span>
                                </div>
                                <i class="fas fa-chevron-left text-slate-500 text-xs"></i>
                            </a>
                            <a href="{{ route('admin.locations.index') }}" class="flex items-center justify-between bg-slate-800 hover:bg-slate-700 p-3 rounded-xl transition-all">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-map-marker-alt text-blue-400 text-sm"></i>
                                    </div>
                                    <span class="text-sm font-bold text-white">إدارة المواقع</span>
                                </div>
                                <i class="fas fa-chevron-left text-slate-500 text-xs"></i>
                            </a>
                            <a href="{{ route('admin.ads.index') }}" class="flex items-center justify-between bg-slate-800 hover:bg-slate-700 p-3 rounded-xl transition-all">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-amber-500/20 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-ad text-amber-400 text-sm"></i>
                                    </div>
                                    <span class="text-sm font-bold text-white">إدارة الإعلانات</span>
                                </div>
                                <i class="fas fa-chevron-left text-slate-500 text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                {{-- ============================================================ --}}
                {{-- 🏢 PENDING BUSINESSES TABLE - المنشآت المعلقة --}}
                {{-- ============================================================ --}}
                <div class="bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-800 flex justify-between items-center flex-wrap gap-3">
                        <div>
                            <h3 class="text-sm font-bold text-white flex items-center gap-2">
                                <i class="fas fa-clock text-amber-400"></i>
                                المنشآت بانتظار المراجعة
                            </h3>
                            <p class="text-xs text-slate-400 mt-1">تحتاج إلى مراجعة وتفعيل</p>
                        </div>
                        <div class="relative">
                            <i class="fas fa-search absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-500 text-xs"></i>
                            <input type="text" id="searchInput" placeholder="بحث..." class="bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 pr-9 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500">
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-right">
                            <thead class="bg-slate-800/50">
                                <tr class="text-xs text-slate-400 border-b border-slate-800">
                                    <th class="px-6 py-3 font-bold">#</th>
                                    <th class="px-6 py-3 font-bold">المنشأة</th>
                                    <th class="px-6 py-3 font-bold">التصنيف</th>
                                    <th class="px-6 py-3 font-bold">الموقع</th>
                                    <th class="px-6 py-3 font-bold">رقم الهاتف</th>
                                    <th class="px-6 py-3 font-bold">تاريخ الإضافة</th>
                                    <th class="px-6 py-3 font-bold text-center">إجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800">
                                @forelse($pendingBusinesses ?? [] as $business)
                                <tr class="table-row">
                                    <td class="px-6 py-4 text-xs text-slate-400">{{ $business->id }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            @if($business->logo && file_exists(base_path('storage/app/public/' . $business->logo)))
                                                <img src="{{ url('/storage/' . $business->logo) }}" class="w-8 h-8 rounded-lg object-cover" loading="lazy">
                                            @else
                                                <div class="w-8 h-8 bg-slate-800 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-store text-slate-500 text-xs"></i>
                                                </div>
                                            @endif
                                            <span class="text-sm font-bold text-white">{{ $business->title }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-300">{{ $business->category->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-xs text-slate-300">{{ $business->location->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-xs font-mono text-slate-300">{{ $business->phone ?? '-' }}</td>
                                    <td class="px-6 py-4 text-xs text-slate-400">{{ $business->created_at->format('Y-m-d') }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.businesses.edit', $business->id) }}" 
                                               class="bg-blue-500/20 hover:bg-blue-500 text-blue-400 hover:text-white px-3 py-1.5 rounded-lg text-xs transition-all">
                                                <i class="fas fa-edit ml-1"></i> تعديل
                                            </a>
                                            <form action="{{ route('admin.businesses.destroy', $business->id) }}" method="POST" 
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذه المنشأة؟')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500/20 hover:bg-red-500 text-red-400 hover:text-white px-3 py-1.5 rounded-lg text-xs transition-all">
                                                    <i class="fas fa-trash ml-1"></i> حذف
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center gap-2">
                                            <i class="fas fa-check-circle text-emerald-500 text-4xl"></i>
                                            <p class="text-slate-400 text-sm">لا توجد منشآت بانتظار المراجعة</p>
                                            <p class="text-slate-500 text-xs">جميع المنشآت تمت مراجعتها وتفعيلها</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                {{-- Pagination --}}
                @if(isset($pendingBusinesses) && method_exists($pendingBusinesses, 'links'))
                    <div class="mt-6">
                        {{ $pendingBusinesses->links() }}
                    </div>
                @endif
                
            </div>
        </main>
    </div>

    {{-- ============================================================ --}}
    {{-- 📈 Chart.js Script --}}
    {{-- ============================================================ --}}
    <script>
        let chart;
        
        function initChart(type = 'weekly') {
            const ctx = document.getElementById('businessChart').getContext('2d');
            
            let labels = [];
            let data = [];
            
            if (type === 'weekly') {
                labels = ['السبت', 'الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة'];
                data = [12, 19, 15, 17, 14, 23, 18];
            } else {
                labels = ['الأسبوع 1', 'الأسبوع 2', 'الأسبوع 3', 'الأسبوع 4'];
                data = [45, 62, 58, 71];
            }
            
            if (chart) {
                chart.destroy();
            }
            
            chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'المنشآت الجديدة',
                        data: data,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            labels: { color: '#94a3b8', font: { size: 11 } }
                        }
                    },
                    scales: {
                        y: {
                            grid: { color: '#1e293b' },
                            ticks: { color: '#94a3b8' }
                        },
                        x: {
                            grid: { color: '#1e293b' },
                            ticks: { color: '#94a3b8' }
                        }
                    }
                }
            });
        }
        
        document.getElementById('weeklyBtn').addEventListener('click', () => initChart('weekly'));
        document.getElementById('monthlyBtn').addEventListener('click', () => initChart('monthly'));
        
        initChart('weekly');
        
        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
        
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