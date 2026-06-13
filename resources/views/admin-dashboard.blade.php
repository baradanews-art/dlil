@extends('layouts.admin')

@section('title', 'لوحة التحكم الرئيسية')
@section('page_heading', '📊 لوحة التحكم الرئيسية')
@section('page_subheading', 'نظرة عامة على أداء المنصة وإحصائياتها')

@section('content')
<div class="space-y-6">
    
    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="stat-card bg-gradient-to-br from-emerald-600 to-emerald-700 rounded-2xl p-5 shadow-lg">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-emerald-100 text-xs font-bold uppercase tracking-wider">إجمالي المنشآت</p>
                    <p class="text-white text-3xl font-black mt-2">{{ number_format($stats['total'] ?? 0) }}</p>
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
        
        <div class="stat-card bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl p-5 shadow-lg">
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
        
        <div class="stat-card bg-gradient-to-br from-purple-600 to-purple-700 rounded-2xl p-5 shadow-lg">
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
        
        <div class="stat-card bg-gradient-to-br from-amber-600 to-amber-700 rounded-2xl p-5 shadow-lg">
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
                    <span class="text-amber-100">⭐ متوسط: {{ number_format($stats['avg_rating'] ?? 0, 1) }}/5</span>
                    <span class="text-amber-200">⏳ قيد المراجعة: {{ number_format($stats['pending_reviews'] ?? 0) }}</span>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Second Row Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-xs font-bold uppercase">موثقة</p>
                    <p class="text-2xl font-black text-slate-900">{{ number_format($stats['verified_businesses'] ?? 0) }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-xs font-bold uppercase">رسمية معتمدة</p>
                    <p class="text-2xl font-black text-slate-900">{{ number_format($stats['official_businesses'] ?? 0) }}</p>
                </div>
                <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-crown text-amber-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-xs font-bold uppercase">توصيل متاح</p>
                    <p class="text-2xl font-black text-slate-900">{{ number_format($stats['delivery_available'] ?? 0) }}</p>
                </div>
                <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-motorcycle text-emerald-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-xs font-bold uppercase">مؤسسات رسمية</p>
                    <p class="text-2xl font-black text-slate-900">{{ number_format($stats['official_entities'] ?? 0) }}</p>
                </div>
                <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-building text-slate-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Pending Businesses Table --}}
    <div class="card">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
            <div class="flex justify-between items-center flex-wrap gap-3">
                <div>
                    <h3 class="font-bold text-slate-900 flex items-center gap-2">
                        <i class="fas fa-clock text-amber-500"></i>
                        المنشآت بانتظار المراجعة
                    </h3>
                    <p class="text-xs text-slate-500 mt-1">تحتاج إلى مراجعة وتفعيل</p>
                </div>
                <div class="relative">
                    <i class="fas fa-search absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" id="searchInput" placeholder="بحث..." 
                           class="input !w-64 !py-2 !text-sm">
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-right">
                <thead class="bg-slate-50">
                    <tr class="text-xs text-slate-500 border-b border-slate-200">
                        <th class="px-6 py-3 font-bold">#</th>
                        <th class="px-6 py-3 font-bold">المنشأة</th>
                        <th class="px-6 py-3 font-bold">التصنيف</th>
                        <th class="px-6 py-3 font-bold">الموقع</th>
                        <th class="px-6 py-3 font-bold">رقم الهاتف</th>
                        <th class="px-6 py-3 font-bold">تاريخ الإضافة</th>
                        <th class="px-6 py-3 font-bold text-center">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pendingBusinesses ?? [] as $business)
                    <tr class="table-row">
                        <td class="px-6 py-4 text-xs text-slate-500">{{ $business->id }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <img src="{{ $business->logo_url }}" 
                                     class="w-8 h-8 rounded-lg object-cover" 
                                     loading="lazy"
                                     onerror="this.src='https://placehold.co/200x200/1e293b/10b981?text=🏪'">
                                <span class="text-sm font-bold text-slate-800">{{ $business->title }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-600">{{ $business->category->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-xs text-slate-600">{{ $business->location->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-xs font-mono text-slate-600">{{ $business->phone ?? '-' }}</td>
                        <td class="px-6 py-4 text-xs text-slate-500">{{ $business->created_at->format('Y-m-d') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.businesses.edit', $business->id) }}" 
                                   class="bg-blue-500/20 hover:bg-blue-500 text-blue-600 hover:text-white px-3 py-1.5 rounded-lg text-xs transition-all">
                                    <i class="fas fa-edit ml-1"></i> تعديل
                                </a>
                                <form action="{{ route('admin.businesses.destroy', $business->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذه المنشأة؟')" 
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500/20 hover:bg-red-500 text-red-600 hover:text-white px-3 py-1.5 rounded-lg text-xs transition-all">
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
                                <p class="text-slate-500">🎉 لا توجد منشآت بانتظار المراجعة</p>
                                <p class="text-slate-400 text-xs">جميع المنشآت تمت مراجعتها وتفعيلها</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(isset($pendingBusinesses) && method_exists($pendingBusinesses, 'links'))
            <div class="px-6 py-4 border-t border-slate-200">
                {{ $pendingBusinesses->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('searchInput')?.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
</script>
@endpush
@endsection