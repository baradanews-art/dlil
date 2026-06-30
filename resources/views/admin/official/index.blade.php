@extends('layouts.admin')

@section('title', 'إدارة المؤسسات الرسمية - ' . ($type == 'government' ? 'حكومية' : ($type == 'security' ? 'أمن ونجدة' : 'مراكز مساعدة')))
@section('page_heading', '🏛️ إدارة المؤسسات الرسمية')
@section('page_subheading', 'إضافة وتعديل وحذف المؤسسات الحكومية والأمنية ومراكز المساعدة')

@section('content')
<div class="space-y-6">
    
    {{-- Type Tabs & Action Buttons --}}
    <div class="flex justify-between items-center flex-wrap gap-3">
        <div class="flex bg-slate-100 rounded-xl overflow-hidden">
            <a href="{{ route('admin.official.index', ['type' => 'government']) }}" 
               class="px-5 py-2.5 text-sm font-bold transition-all {{ $type == 'government' ? 'bg-emerald-600 text-white' : 'text-slate-600 hover:bg-slate-200' }}">
                🏛️ حكومية
            </a>
            <a href="{{ route('admin.official.index', ['type' => 'security']) }}" 
               class="px-5 py-2.5 text-sm font-bold transition-all {{ $type == 'security' ? 'bg-emerald-600 text-white' : 'text-slate-600 hover:bg-slate-200' }}">
                🛡️ أمن ونجدة
            </a>
            <a href="{{ route('admin.official.index', ['type' => 'help']) }}" 
               class="px-5 py-2.5 text-sm font-bold transition-all {{ $type == 'help' ? 'bg-emerald-600 text-white' : 'text-slate-600 hover:bg-slate-200' }}">
                🤝 مراكز مساعدة
            </a>
        </div>
        
        <div class="flex gap-3">
            {{-- زر التصدير --}}
            <a href="{{ route('admin.official.export', request()->query()) }}" 
               class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2.5 px-4 rounded-xl transition-all">
                <i class="fas fa-download ml-1"></i> تصدير CSV
            </a>
            
            {{-- زر الاستيراد --}}
            <button type="button" onclick="openImportModal()" 
                    class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-2.5 px-4 rounded-xl transition-all">
                <i class="fas fa-upload ml-1"></i> استيراد وتحديث
            </button>
            
            {{-- زر إضافة جديدة --}}
            <a href="{{ route('admin.official.create', ['type' => $type]) }}" class="btn-primary !py-2.5">
                <i class="fas fa-plus ml-1"></i> إضافة جديدة
            </a>
        </div>
    </div>
    
    {{-- Search & Filters --}}
    <div class="card">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
            <h3 class="font-bold text-slate-900 flex items-center gap-2">
                <i class="fas fa-search text-emerald-500"></i>
                بحث وتصفية
            </h3>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('admin.official.index') }}" id="filterForm" class="space-y-4">
                <input type="hidden" name="type" value="{{ $type }}">
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="label text-xs">البحث بالاسم أو العنوان</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="اسم المؤسسة، العنوان، الهاتف..." 
                               class="input w-full">
                    </div>
                    
                    <div>
                        <label class="label text-xs">المحافظة</label>
                        <select name="city_id" id="filter_city_id" class="input w-full">
                            <option value="">جميع المحافظات</option>
                            @foreach($cities ?? [] as $city)
                                <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="label text-xs">المنطقة</label>
                        <select name="region_id" id="filter_region_id" class="input w-full">
                            <option value="">جميع المناطق</option>
                            @foreach($regions ?? [] as $region)
                                <option value="{{ $region->id }}" {{ request('region_id') == $region->id ? 'selected' : '' }}>
                                    {{ $region->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="label text-xs">النوع الفرعي</label>
                        <select name="sub_type" class="input w-full">
                            <option value="">جميع الأنواع</option>
                            @foreach($subTypes ?? [] as $key => $label)
                                <option value="{{ $key }}" {{ request('sub_type') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="flex gap-3 justify-end">
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-xl transition-all">
                        <i class="fas fa-search ml-1"></i> بحث
                    </button>
                    <a href="{{ route('admin.official.index', ['type' => $type]) }}" 
                       class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-6 py-2 rounded-xl transition-all">
                        <i class="fas fa-undo-alt ml-1"></i> إعادة ضبط
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    {{-- Entities Table --}}
    <div class="card">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
            <h3 class="font-bold text-slate-900 flex items-center gap-2">
                <i class="fas fa-list text-emerald-500"></i>
                قائمة المؤسسات
            </h3>
            <span class="text-sm text-slate-500">إجمالي: {{ $entities->total() }} مؤسسة</span>
        </div>
        
        @if($entities->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-right">
                <thead class="bg-slate-50">
                    <tr class="text-xs text-slate-500 border-b border-slate-200">
                        <th class="px-4 py-3 font-bold text-center">#</th>
                        <th class="px-4 py-3 font-bold">الشعار</th>
                        <th class="px-4 py-3 font-bold">الاسم</th>
                        <th class="px-4 py-3 font-bold">النوع</th>
                        <th class="px-4 py-3 font-bold">الهاتف</th>
                        <th class="px-4 py-3 font-bold">طوارئ</th>
                        <th class="px-4 py-3 font-bold">المحافظة</th>
                        <th class="px-4 py-3 font-bold">المنطقة</th>
                        <th class="px-4 py-3 font-bold text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($entities as $index => $entity)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3 text-xs text-slate-500 text-center">{{ $entities->firstItem() + $index }}</td>
                        <td class="px-4 py-3">
                            <img src="{{ $entity->logo_url }}" 
                                 class="w-10 h-10 rounded-lg object-cover bg-slate-100"
                                 loading="lazy"
                                 onerror="this.src='https://placehold.co/100x100/1e293b/ffffff?text=🏛️'">
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-bold text-slate-800 text-sm">{{ $entity->name }}</div>
                            @if($entity->sub_type)
                                <span class="text-[10px] bg-slate-100 text-slate-500 px-1.5 py-0.5 rounded-full">{{ $entity->sub_type_label }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs px-2 py-1 rounded-full 
                                @if($entity->type == 'government') bg-green-500/20 text-green-600
                                @elseif($entity->type == 'security') bg-red-500/20 text-red-600
                                @else bg-blue-500/20 text-blue-600 @endif">
                                {{ $entity->type_label }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-600 font-mono" dir="ltr">{{ $entity->phone ?? '—' }}</td>
                        <td class="px-4 py-3 text-xs text-slate-600 font-mono" dir="ltr">{{ $entity->hotline ?? '—' }}</td>
                        <td class="px-4 py-3 text-xs text-slate-600">{{ $entity->city->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-xs text-slate-600">{{ $entity->region->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.official.edit', $entity->id) }}" 
                                   class="bg-blue-500/20 hover:bg-blue-500 text-blue-600 hover:text-white px-3 py-1.5 rounded-lg text-xs transition-all">
                                    <i class="fas fa-edit ml-1"></i> تعديل
                                </a>
                                <form action="{{ route('admin.official.destroy', $entity->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذه المؤسسة؟')" 
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
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $entities->appends(request()->query())->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-building text-5xl text-slate-300 mb-3 block"></i>
            <p class="text-slate-500">لا توجد مؤسسات مطابقة لمعايير البحث</p>
            <p class="text-slate-400 text-sm mt-1">حاول تغيير معايير البحث</p>
            <a href="{{ route('admin.official.create', ['type' => $type]) }}" class="inline-block mt-4 btn-primary">
                <i class="fas fa-plus ml-1"></i> أضف مؤسسة جديدة
            </a>
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
        <form action="{{ route('admin.official.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">
            
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
        const citySelect = document.getElementById('filter_city_id');
        const regionSelect = document.getElementById('filter_region_id');
        
        if (citySelect && regionSelect) {
            // تحميل المناطق عند تغيير المحافظة في الفلتر
            citySelect.addEventListener('change', function() {
                const cityId = this.value;
                
                if (!cityId) {
                    regionSelect.innerHTML = '<option value="">جميع المناطق</option>';
                    regionSelect.disabled = false;
                    return;
                }
                
                regionSelect.innerHTML = '<option value="">⏳ جاري التحميل...</option>';
                regionSelect.disabled = true;
                
                fetch(`{{ url('/admin/get-regions') }}/${cityId}`)
                    .then(response => response.json())
                    .then(data => {
                        regionSelect.innerHTML = '<option value="">جميع المناطق</option>';
                        if (data && data.length > 0) {
                            data.forEach(region => {
                                const option = document.createElement('option');
                                option.value = region.id;
                                option.textContent = region.name;
                                if (option.value == '{{ request('region_id') }}') {
                                    option.selected = true;
                                }
                                regionSelect.appendChild(option);
                            });
                            regionSelect.disabled = false;
                        } else {
                            regionSelect.innerHTML = '<option value="">⚠️ لا توجد مناطق</option>';
                            regionSelect.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        regionSelect.innerHTML = '<option value="">❌ حدث خطأ</option>';
                        regionSelect.disabled = false;
                    });
            });
        }
    });
    
    function openImportModal() {
        document.getElementById('importModal').classList.add('flex');
        document.getElementById('importModal').classList.remove('hidden');
    }
    
    function closeImportModal() {
        document.getElementById('importModal').classList.add('hidden');
        document.getElementById('importModal').classList.remove('flex');
    }
    
    // إغلاق المودال عند الضغط خارج المحتوى
    document.getElementById('importModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeImportModal();
    });
</script>
@endpush
@endsection