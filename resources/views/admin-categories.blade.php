<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة التصنيفات | لوحة التحكم</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #1e293b; }
        ::-webkit-scrollbar-thumb { background: #10b981; border-radius: 10px; }
        .sidebar-transition { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .category-card {
            transition: all 0.3s ease;
        }
        .category-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.3);
        }
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
                        <p class="text-[10px] text-emerald-400">إدارة التصنيفات</p>
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
                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-600/20 text-emerald-400 transition-all">
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
                <a href="{{ route('admin.official.index', ['type' => 'government']) }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
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
                            <i class="fas fa-tags text-emerald-400"></i>
                            إدارة التصنيفات
                        </h1>
                        <p class="text-xs text-slate-400 mt-0.5">إدارة أقسام وتصنيفات المنشآت التجارية</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                @if(session('success'))
                    <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 p-4 rounded-xl text-sm mb-6 flex items-center gap-2">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="bg-red-500/10 border border-red-500/30 text-red-400 p-4 rounded-xl text-sm mb-6 flex items-center gap-2">
                        <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                    </div>
                @endif
                
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    
                    {{-- Add Category Form --}}
                    <div class="lg:col-span-1">
                        <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 sticky top-24">
                            <h3 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                                <i class="fas fa-plus-circle text-emerald-400"></i>
                                إضافة تصنيف جديد
                            </h3>
                            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 mb-2">اسم التصنيف *</label>
                                    <input type="text" name="name" required placeholder="مثال: مطاعم، صيدليات..." class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-3 rounded-xl focus:outline-none focus:border-emerald-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 mb-2">الأيقونة</label>
                                    <input type="text" name="icon" placeholder="مثال: 🍔، 💊، 🛋️" class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-3 rounded-xl focus:outline-none focus:border-emerald-500">
                                </div>
                                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl transition-all">
                                    <i class="fas fa-save ml-2"></i> حفظ التصنيف
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    {{-- Categories Grid --}}
                    <div class="lg:col-span-3">
                        <div class="bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-800">
                                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                                    <i class="fas fa-th-large text-emerald-400"></i>
                                    التصنيفات الحالية
                                    <span class="bg-slate-700 text-emerald-400 text-xs px-2 py-0.5 rounded-full mr-2">{{ count($categories) }}</span>
                                </h3>
                            </div>
                            
                            <div class="p-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @forelse($categories as $cat)
                                    <div class="category-card bg-slate-800 rounded-xl overflow-hidden border border-slate-700 hover:border-emerald-500/50 transition-all">
                                        <div class="p-4">
                                            <div class="flex items-center justify-between mb-3">
                                                <div class="text-4xl">{{ $cat->icon ?? '📁' }}</div>
                                                <div class="flex gap-1">
                                                    {{-- زر تعديل --}}
                                                    <button onclick="openEditCategoryModal({{ $cat->id }}, '{{ $cat->name }}', '{{ $cat->icon }}')" 
                                                            class="bg-blue-500/20 hover:bg-blue-500 text-blue-400 hover:text-white p-2 rounded-lg transition-all">
                                                        <i class="fas fa-edit text-xs"></i>
                                                    </button>
                                                    
                                                    {{-- زر حذف --}}
                                                    <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا التصنيف؟')" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="bg-red-500/20 hover:bg-red-500 text-red-400 hover:text-white p-2 rounded-lg transition-all">
                                                            <i class="fas fa-trash text-xs"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                            <h4 class="font-bold text-white text-sm mb-1">{{ $cat->name }}</h4>
                                            <p class="text-[10px] text-slate-500 font-mono mb-2">{{ $cat->slug }}</p>
                                            <div class="flex justify-between items-center pt-2 border-t border-slate-700">
                                                <span class="text-xs text-slate-400">
                                                    <i class="fas fa-store ml-1"></i> {{ $cat->businesses_count ?? $cat->businesses()->count() }} منشأة
                                                </span>
                                                <span class="text-[10px] text-slate-500">ID: {{ $cat->id }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="col-span-full text-center py-12">
                                        <i class="fas fa-folder-open text-5xl text-slate-600 mb-3 block"></i>
                                        <p class="text-slate-500">لا توجد تصنيفات مضافة</p>
                                        <p class="text-slate-600 text-sm mt-1">أضف تصنيفك الأول من النموذج المجاور</p>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    {{-- Modal تعديل التصنيف --}}
    <div id="editCategoryModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-slate-800 rounded-2xl p-6 w-full max-w-md mx-4 transform transition-all scale-95">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-white">تعديل التصنيف</h3>
                <button onclick="closeEditCategoryModal()" class="text-slate-400 hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-xs font-bold text-slate-400 mb-2">اسم التصنيف</label>
                    <input type="text" name="name" id="edit_category_name" class="w-full bg-slate-700 border border-slate-600 text-white rounded-xl p-3 focus:outline-none focus:border-emerald-500">
                </div>
                <div class="mb-4">
                    <label class="block text-xs font-bold text-slate-400 mb-2">الأيقونة</label>
                    <input type="text" name="icon" id="edit_category_icon" class="w-full bg-slate-700 border border-slate-600 text-white rounded-xl p-3 focus:outline-none focus:border-emerald-500">
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 rounded-xl transition-all">حفظ التغييرات</button>
                    <button type="button" onclick="closeEditCategoryModal()" class="flex-1 bg-slate-700 hover:bg-slate-600 text-white font-bold py-2 rounded-xl transition-all">إلغاء</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditCategoryModal(id, name, icon) {
            document.getElementById('editCategoryForm').action = `/admin/categories/${id}`;
            document.getElementById('edit_category_name').value = name;
            document.getElementById('edit_category_icon').value = icon || '';
            document.getElementById('editCategoryModal').classList.add('flex');
            document.getElementById('editCategoryModal').classList.remove('hidden');
        }
        
        function closeEditCategoryModal() {
            document.getElementById('editCategoryModal').classList.add('hidden');
            document.getElementById('editCategoryModal').classList.remove('flex');
        }
        
        // Close modal when clicking outside
        document.getElementById('editCategoryModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditCategoryModal();
            }
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