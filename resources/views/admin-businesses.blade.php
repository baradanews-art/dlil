@extends('layouts.admin')

@section('title', 'إدارة المنشآت التجارية')
@section('page_heading', '🏢 إدارة المنشآت التجارية')
@section('page_subheading', 'مراجعة وتعديل وحذف المنشآت المسجلة في الدليل')

@section('content')
<div class="space-y-6">
    
    {{-- Actions Buttons --}}
    <div class="flex justify-between items-center flex-wrap gap-3 mb-4">
        <div class="flex gap-3">
            <a href="{{ route('admin.businesses.create') }}" class="btn-primary !py-2.5">
                <i class="fas fa-plus ml-1"></i> إضافة منشأة جديدة
            </a>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.businesses.export', request()->query()) }}" 
               class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2.5 px-4 rounded-xl transition-all">
                <i class="fas fa-download ml-1"></i> تصدير CSV
            </a>
            <button type="button" onclick="openImportModal()" 
                    class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-2.5 px-4 rounded-xl transition-all">
                <i class="fas fa-upload ml-1"></i> استيراد وتحديث
            </button>
        </div>
    </div>
    
    {{-- Filters --}}
    <div class="card">
        <div class="p-4 bg-slate-50 border-b border-slate-200">
            <h3 class="font-bold text-slate-800 text-sm">🔍 فلترة المنشآت</h3>
        </div>
        <div class="p-4">
            <form method="GET" action="{{ route('admin.businesses.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث بالاسم أو الهاتف..." class="input text-sm">
                <select name="status" class="input text-sm">
                    <option value="">جميع الحالات</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>✅ منشور</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ معلق</option>
                </select>
                <select name="category_id" class="input text-sm">
                    <option value="">جميع التصنيفات</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <select name="governorate_id" id="filter_governorate" class="input text-sm">
                    <option value="">جميع المحافظات</option>
                    @foreach($governorates as $gov)
                        <option value="{{ $gov->id }}" {{ request('governorate_id') == $gov->id ? 'selected' : '' }}>{{ $gov->name }}</option>
                    @endforeach
                </select>
                <select name="region_id" id="filter_region" class="input text-sm" {{ request('governorate_id') ? '' : 'disabled' }}>
                    <option value="">جميع المناطق</option>
                    @foreach($regions ?? [] as $region)
                        <option value="{{ $region->id }}" {{ request('region_id') == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-primary py-2 text-sm col-span-full md:col-span-1">بحث <i class="fas fa-search mr-1"></i></button>
            </form>
        </div>
    </div>
    
    {{-- Businesses Table --}}
    <div class="card">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
            <h3 class="font-bold text-slate-800 text-sm">📋 قائمة المنشآت ({{ $businesses->total() }})</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-right">
                <thead class="bg-slate-50">
                    <tr class="text-xs text-slate-500 border-b border-slate-200">
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">المنشأة</th>
                        <th class="px-4 py-3">التصنيف</th>
                        <th class="px-4 py-3">المحافظة</th>
                        <th class="px-4 py-3">المنطقة</th>
                        <th class="px-4 py-3">الهاتف</th>
                        <th class="px-4 py-3">الحالة</th>
                        <th class="px-4 py-3">التوثيق</th>
                        <th class="px-4 py-3 text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($businesses as $bus)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3 text-xs text-slate-500">{{ $bus->id }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                @if($bus->logo)
                                    <img src="{{ asset('public/' . $bus->logo) }}" class="w-8 h-8 rounded-lg object-cover" loading="lazy" onerror="this.src='https://placehold.co/200x200/1e293b/10b981?text=🏪'">
                                @else
                                    <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400 text-xs">🏪</div>
                                @endif
                                <div>
                                    <div class="font-bold text-slate-800 text-sm">{{ $bus->title }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $bus->slug }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-600">{{ $bus->category->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-xs text-slate-600">{{ $bus->governorate->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-xs text-slate-600">{{ $bus->region->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-xs font-mono text-slate-600" dir="ltr">{{ $bus->phone ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @if($bus->is_approved)
                                <span class="text-xs bg-emerald-500/20 text-emerald-600 px-2 py-0.5 rounded-full">✅ منشور</span>
                            @else
                                <span class="text-xs bg-amber-500/20 text-amber-600 px-2 py-0.5 rounded-full">⏳ معلق</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($bus->verification_type == 'official')
                                <span class="text-xs bg-amber-500/20 text-amber-600 px-2 py-0.5 rounded-full">👑 رسمي</span>
                            @elseif($bus->verification_type == 'verified')
                                <span class="text-xs bg-blue-500/20 text-blue-600 px-2 py-0.5 rounded-full">✓ موثق</span>
                            @else
                                <span class="text-xs bg-slate-200 text-slate-500 px-2 py-0.5 rounded-full">غير موثق</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.businesses.edit', $bus->id) }}" class="bg-blue-500/20 hover:bg-blue-500 text-blue-600 hover:text-white px-3 py-1.5 rounded-lg text-xs transition-all">
                                    <i class="fas fa-edit ml-1"></i> تعديل
                                </a>
                                <form action="{{ route('admin.businesses.destroy', $bus->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه المنشأة نهائياً؟')" class="inline">
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
                        <td colspan="9" class="px-4 py-12 text-center text-slate-500">
                            <i class="fas fa-store text-4xl mb-2 block opacity-50"></i>
                            لا توجد منشآت مسجلة حالياً
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($businesses->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $businesses->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Import Modal --}}
<div id="importModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-slate-900">استيراد وتحديث البيانات</h3>
            <button onclick="closeImportModal()" class="text-slate-400 hover:text-slate-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form action="{{ route('admin.businesses.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="label text-sm">رفع ملف CSV</label>
                <input type="file" name="import_file" accept=".csv" required class="w-full border border-slate-200 rounded-xl p-2 text-sm">
                <p class="text-[10px] text-slate-400 mt-1">الملف يجب أن يكون بصيغة CSV تم تصديره من النظام مسبقاً</p>
                <p class="text-[10px] text-amber-600 mt-1">⚠️ سيتم تحديث السجلات بناءً على عمود ID</p>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-xl flex-1">
                    <i class="fas fa-upload ml-1"></i> استيراد
                </button>
                <button type="button" onclick="closeImportModal()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2 px-4 rounded-xl flex-1">
                    إلغاء
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ========== فلترة المنطقة ==========
        const govSelect = document.getElementById('filter_governorate');
        const regionSelect = document.getElementById('filter_region');
        
        if (govSelect && regionSelect) {
            function loadRegions(governorateId) {
                if (!governorateId) {
                    regionSelect.innerHTML = '<option value="">جميع المناطق</option>';
                    regionSelect.disabled = true;
                    return;
                }
                
                regionSelect.innerHTML = '<option value="">⏳ جاري التحميل...</option>';
                regionSelect.disabled = true;
                
                const url = `/dlil/get-regions/${governorateId}`;
                
                fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    regionSelect.innerHTML = '<option value="">جميع المناطق</option>';
                    if (data && Array.isArray(data) && data.length > 0) {
                        data.forEach(region => {
                            const option = document.createElement('option');
                            option.value = region.id;
                            option.textContent = region.name;
                            regionSelect.appendChild(option);
                        });
                        regionSelect.disabled = false;
                    } else {
                        regionSelect.innerHTML = '<option value="">⚠️ لا توجد مناطق</option>';
                        regionSelect.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('❌ خطأ في تحميل المناطق:', error);
                    regionSelect.innerHTML = '<option value="">❌ حدث خطأ</option>';
                    regionSelect.disabled = false;
                });
            }
            
            govSelect.addEventListener('change', function() {
                const govId = this.value;
                loadRegions(govId);
            });
            
            if (govSelect.value) {
                loadRegions(govSelect.value);
            }
        }
    });
    
    // ========== استيراد مودال ==========
    function openImportModal() {
        document.getElementById('importModal').classList.add('flex');
        document.getElementById('importModal').classList.remove('hidden');
    }
    
    function closeImportModal() {
        document.getElementById('importModal').classList.add('hidden');
        document.getElementById('importModal').classList.remove('flex');
    }
    
    document.getElementById('importModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeImportModal();
    });
</script>
@endpush
@endsection