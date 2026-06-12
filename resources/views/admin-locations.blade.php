<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المواقع الجغرافية | لوحة التحكم</title>
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
                        <p class="text-[10px] text-emerald-400">إدارة المواقع</p>
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
                <a href="{{ route('admin.locations.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-600/20 text-emerald-400 transition-all">
                    <i class="fas fa-map-marker-alt w-5"></i> <span class="text-sm font-bold">المواقع الجغرافية</span>
                </a>
                <a href="{{ route('admin.reviews.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                    <i class="fas fa-star w-5"></i> <span class="text-sm font-bold">التقييمات</span>
                </a>
                <a href="{{ route('admin.ads.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                    <i class="fas fa-ad w-5"></i> <span class="text-sm font-bold">الإعلانات</span>
                </a>
                <a href="{{ route('admin.official.index') }}?type=government" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
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
                            <i class="fas fa-map-marker-alt text-emerald-400"></i>
                            إدارة المواقع الجغرافية
                        </h1>
                        <p class="text-xs text-slate-400 mt-0.5">إدارة المحافظات والمدن والمناطق</p>
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
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    {{-- Add Location Form --}}
                    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6">
                        <h3 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                            <i class="fas fa-plus-circle text-emerald-400"></i>
                            إضافة موقع جديد
                        </h3>
                        <form action="{{ route('admin.locations.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-slate-400 mb-2">اسم الموقع *</label>
                                <input type="text" name="name" required placeholder="مثال: ريف دمشق، قدسيا، حلب..." class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-3 rounded-xl focus:outline-none focus:border-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 mb-2">التبعية (اختر أب)</label>
                                <select name="parent_id" class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-3 rounded-xl focus:outline-none focus:border-emerald-500">
                                    <option value="">🚫 محافظة رئيسية (بدون أب)</option>
                                    @foreach($governorates ?? [] as $gov)
                                        <option value="{{ $gov->id }}">📌 تابعة لـ: {{ $gov->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl transition-all">
                                <i class="fas fa-save ml-2"></i> حفظ الموقع
                            </button>
                        </form>
                    </div>
                    
                    {{-- Locations List --}}
                    <div class="lg:col-span-2 bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-800">
                            <h3 class="text-sm font-bold text-white flex items-center gap-2">
                                <i class="fas fa-tree text-emerald-400"></i>
                                شجرة المواقع ({{ count($locations) }})
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-right">
                                <thead class="bg-slate-800/50">
                                    <tr class="text-xs text-slate-400 border-b border-slate-800">
                                        <th class="px-6 py-3 font-bold">#</th>
                                        <th class="px-6 py-3 font-bold">اسم الموقع</th>
                                        <th class="px-6 py-3 font-bold">النوع</th>
                                        <th class="px-6 py-3 font-bold">التابعة لـ</th>
                                        <th class="px-6 py-3 font-bold text-center">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-800">
                                    @forelse($locations as $loc)
                                    <tr class="hover:bg-slate-800/30 transition-colors">
                                        <td class="px-6 py-4 text-xs text-slate-500">{{ $loc->id }}</td>
                                        <td class="px-6 py-4 text-sm font-bold text-white">{{ $loc->name }}</td>
                                        <td class="px-6 py-4">
                                            @if($loc->parent_id)
                                                <span class="text-xs bg-sky-500/20 text-sky-400 px-2 py-1 rounded-full">🏙️ منطقة</span>
                                            @else
                                                <span class="text-xs bg-emerald-500/20 text-emerald-400 px-2 py-1 rounded-full">👑 محافظة</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-xs text-slate-400">{{ $loc->parent->name ?? '—' }}</td>
                                        <td class="px-6 py-4 text-center">
                                            {{-- زر تعديل --}}
                                            <button onclick="openEditLocationModal({{ $loc->id }}, '{{ $loc->name }}', {{ $loc->parent_id ?? 'null' }})" 
                                                    class="bg-blue-500/20 hover:bg-blue-500 text-blue-400 hover:text-white px-3 py-1.5 rounded-lg text-xs transition-all ml-2">
                                                <i class="fas fa-edit ml-1"></i> تعديل
                                            </button>
                                            
                                            {{-- زر حذف --}}
                                            <form action="{{ route('admin.locations.destroy', $loc->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الموقع؟')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500/20 hover:bg-red-500 text-red-400 hover:text-white px-3 py-1.5 rounded-lg text-xs transition-all">
                                                    <i class="fas fa-trash ml-1"></i> حذف
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                            <i class="fas fa-map-marked-alt text-4xl mb-2 block"></i>
                                            لا توجد مواقع مضافة
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    {{-- Modal تعديل الموقع --}}
    <div id="editLocationModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-slate-800 rounded-2xl p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-bold text-white mb-4">تعديل الموقع</h3>
            <form id="editLocationForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-xs font-bold text-slate-400 mb-2">اسم الموقع</label>
                    <input type="text" name="name" id="edit_location_name" class="w-full bg-slate-700 border border-slate-600 text-white rounded-xl p-3">
                </div>
                <div class="mb-4">
                    <label class="block text-xs font-bold text-slate-400 mb-2">التبعية</label>
                    <select name="parent_id" id="edit_location_parent" class="w-full bg-slate-700 border border-slate-600 text-white rounded-xl p-3">
                        <option value="">🚫 محافظة رئيسية (بدون أب)</option>
                    </select>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 rounded-xl">حفظ</button>
                    <button type="button" onclick="closeEditLocationModal()" class="flex-1 bg-slate-700 hover:bg-slate-600 text-white font-bold py-2 rounded-xl">إلغاء</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const governorates = @json($governorates);
        
        function openEditLocationModal(id, name, parentId) {
            document.getElementById('editLocationForm').action = `/admin/locations/${id}`;
            document.getElementById('edit_location_name').value = name;
            
            const select = document.getElementById('edit_location_parent');
            select.innerHTML = '<option value="">🚫 محافظة رئيسية (بدون أب)</option>';
            
            governorates.forEach(gov => {
                if (gov.id !== id) {
                    const option = document.createElement('option');
                    option.value = gov.id;
                    option.textContent = `📌 تابعة لـ: ${gov.name}`;
                    if (parentId === gov.id) option.selected = true;
                    select.appendChild(option);
                }
            });
            
            document.getElementById('editLocationModal').classList.add('flex');
            document.getElementById('editLocationModal').classList.remove('hidden');
        }
        
        function closeEditLocationModal() {
            document.getElementById('editLocationModal').classList.add('hidden');
            document.getElementById('editLocationModal').classList.remove('flex');
        }
        
        // Mobile sidebar
        const sidebar = document.getElementById('sidebar');
        document.getElementById('mobileMenuBtn')?.addEventListener('click', () => {
            sidebar.classList.remove('sidebar-mobile-hidden');
            sidebar.classList.add('sidebar-mobile-visible');
        });
        document.getElementById('closeSidebar')?.addEventListener('click', () => {
            sidebar.classList.add('sidebar-mobile-hidden');
            sidebar.classList.remove('sidebar-mobile-visible');
        });
    </script>
</body>
</html>