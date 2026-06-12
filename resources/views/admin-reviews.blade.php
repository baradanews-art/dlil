<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة التقييمات | لوحة التحكم</title>
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
                        <p class="text-[10px] text-emerald-400">إدارة التقييمات</p>
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
                <a href="{{ route('admin.reviews.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-600/20 text-emerald-400 transition-all">
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
                            <i class="fas fa-star text-emerald-400"></i>
                            إدارة التقييمات
                        </h1>
                        <p class="text-xs text-slate-400 mt-0.5">مراقبة وإدارة تقييمات ومراجعات الزوار</p>
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
                    <div class="px-6 py-4 border-b border-slate-800 flex justify-between items-center flex-wrap gap-3">
                        <h3 class="text-sm font-bold text-white flex items-center gap-2">
                            <i class="fas fa-list text-emerald-400"></i>
                            جميع التقييمات ({{ count($reviews) }})
                        </h3>
                        <div class="relative">
                            <i class="fas fa-search absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-500 text-xs"></i>
                            <input type="text" id="searchInput" placeholder="بحث..." class="bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 pr-9 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500">
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-right">
                            <thead class="bg-slate-800/50">
                                <tr class="text-xs text-slate-400 border-b border-slate-800">
                                    <th class="px-6 py-3 font-bold">المراجع</th>
                                    <th class="px-6 py-3 font-bold">المنشأة</th>
                                    <th class="px-6 py-3 font-bold">التقييم</th>
                                    <th class="px-6 py-3 font-bold">التعليق</th>
                                    <th class="px-6 py-3 font-bold">الرد</th>
                                    <th class="px-6 py-3 font-bold">التاريخ</th>
                                    <th class="px-6 py-3 font-bold text-center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800">
                                @forelse($reviews as $rev)
                                <tr class="hover:bg-slate-800/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 bg-slate-800 rounded-full flex items-center justify-center">
                                                <i class="fas fa-user text-slate-500 text-xs"></i>
                                            </div>
                                            <span class="text-sm font-bold text-white">{{ $rev->reviewer_name ?? 'زائر' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('business.show', $rev->business->slug ?? '') }}" target="_blank" class="text-emerald-400 hover:text-emerald-300 text-sm">
                                            {{ $rev->business->title ?? 'منشأة محذوفة' }}
                                            <i class="fas fa-external-link-alt text-[10px] mr-1"></i>
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $rev->rating)
                                                    <i class="fas fa-star text-amber-400 text-xs"></i>
                                                @else
                                                    <i class="far fa-star text-slate-500 text-xs"></i>
                                                @endif
                                            @endfor
                                            <span class="text-xs text-slate-400 mr-2">({{ $rev->rating }}/5)</span>
                                        </div>
                                     </td>
                                    <td class="px-6 py-4 max-w-xs">
                                        <p class="text-xs text-slate-300 line-clamp-2">{{ Str::limit($rev->comment, 60) }}</p>
                                     </td>
                                    <td class="px-6 py-4 max-w-xs">
                                        @if($rev->reply)
                                            <p class="text-xs text-emerald-400 line-clamp-2">{{ Str::limit($rev->reply, 50) }}</p>
                                        @else
                                            <span class="text-xs text-slate-500">—</span>
                                        @endif
                                     </td>
                                    <td class="px-6 py-4 text-xs text-slate-500">{{ $rev->created_at->format('Y-m-d') }}</td>
                                    <td class="px-6 py-4 text-center">
                                        {{-- زر تعديل --}}
                                        <button onclick='openEditReviewModal({{ $rev->id }}, "{{ addslashes($rev->reviewer_name) }}", {{ $rev->rating }}, "{{ addslashes($rev->comment) }}", "{{ addslashes($rev->reply) }}")' 
                                                class="bg-blue-500/20 hover:bg-blue-500 text-blue-400 hover:text-white px-3 py-1.5 rounded-lg text-xs transition-all ml-2">
                                            <i class="fas fa-edit ml-1"></i> تعديل
                                        </button>
                                        
                                        {{-- زر حذف --}}
                                        <form action="{{ route('admin.reviews.destroy', $rev->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا التقييم؟')" class="inline">
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
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                        <i class="fas fa-star-of-life text-4xl mb-2 block"></i>
                                        لا توجد تقييمات حالياً
                                     </td>
                                 </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    {{-- Modal تعديل التقييم --}}
    <div id="editReviewModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-slate-800 rounded-2xl p-6 w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
            <h3 class="text-lg font-bold text-white mb-4">تعديل التقييم</h3>
            <form id="editReviewForm" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-400 mb-2">اسم المراجع</label>
                        <input type="text" name="reviewer_name" id="edit_reviewer_name" class="w-full bg-slate-700 border border-slate-600 text-white rounded-xl p-3">
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-400 mb-2">التقييم (1-5)</label>
                        <select name="rating" id="edit_rating" class="w-full bg-slate-700 border border-slate-600 text-white rounded-xl p-3">
                            <option value="5">⭐⭐⭐⭐⭐ ممتاز (5/5)</option>
                            <option value="4">⭐⭐⭐⭐ جيد جداً (4/5)</option>
                            <option value="3">⭐⭐⭐ متوسط (3/5)</option>
                            <option value="2">⭐⭐ مقبول (2/5)</option>
                            <option value="1">⭐ ضعيف (1/5)</option>
                        </select>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-xs font-bold text-slate-400 mb-2">التعليق</label>
                    <textarea name="comment" id="edit_comment" rows="3" class="w-full bg-slate-700 border border-slate-600 text-white rounded-xl p-3"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-xs font-bold text-slate-400 mb-2">الرد (اختياري)</label>
                    <textarea name="reply" id="edit_reply" rows="2" class="w-full bg-slate-700 border border-slate-600 text-white rounded-xl p-3"></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 rounded-xl">حفظ التغييرات</button>
                    <button type="button" onclick="closeEditReviewModal()" class="flex-1 bg-slate-700 hover:bg-slate-600 text-white font-bold py-2 rounded-xl">إلغاء</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditReviewModal(id, name, rating, comment, reply) {
            document.getElementById('editReviewForm').action = `/admin/reviews/${id}`;
            document.getElementById('edit_reviewer_name').value = name;
            document.getElementById('edit_rating').value = rating;
            document.getElementById('edit_comment').value = comment;
            document.getElementById('edit_reply').value = reply || '';
            document.getElementById('editReviewModal').classList.add('flex');
            document.getElementById('editReviewModal').classList.remove('hidden');
        }
        
        function closeEditReviewModal() {
            document.getElementById('editReviewModal').classList.add('hidden');
            document.getElementById('editReviewModal').classList.remove('flex');
        }
        
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