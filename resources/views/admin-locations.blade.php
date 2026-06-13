@extends('layouts.admin')

@section('title', 'إدارة المواقع الجغرافية')
@section('page_heading', '📍 إدارة المواقع الجغرافية')
@section('page_subheading', 'إدارة المحافظات والمدن والمناطق')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    {{-- Add Location Form --}}
    <div class="lg:col-span-1">
        <div class="card sticky top-24">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h3 class="font-bold text-slate-900 flex items-center gap-2">
                    <i class="fas fa-plus-circle text-emerald-500"></i>
                    إضافة موقع جديد
                </h3>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.locations.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="label">اسم الموقع *</label>
                        <input type="text" name="name" required placeholder="مثال: دمشق، قدسيا، حلب..." class="input">
                    </div>
                    <div>
                        <label class="label">التبعية (اختر أب)</label>
                        <select name="parent_id" class="input">
                            <option value="">🚫 محافظة رئيسية (بدون أب)</option>
                            @foreach($governorates ?? [] as $gov)
                                <option value="{{ $gov->id }}">📌 تابعة لـ: {{ $gov->name }}</option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-slate-400 mt-1">اختر المحافظة الأم إذا كنت تضيف منطقة فرعية</p>
                    </div>
                    <button type="submit" class="btn-primary w-full">
                        <i class="fas fa-save ml-2"></i> حفظ الموقع
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    {{-- Locations List --}}
    <div class="lg:col-span-2">
        <div class="card">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h3 class="font-bold text-slate-900 flex items-center gap-2">
                    <i class="fas fa-tree text-emerald-500"></i>
                    شجرة المواقع ({{ count($locations) }})
                </h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-right">
                    <thead class="bg-slate-50">
                        <tr class="text-xs text-slate-500 border-b border-slate-200">
                            <th class="px-6 py-3 font-bold">#</th>
                            <th class="px-6 py-3 font-bold">اسم الموقع</th>
                            <th class="px-6 py-3 font-bold">النوع</th>
                            <th class="px-6 py-3 font-bold">التابعة لـ</th>
                            <th class="px-6 py-3 font-bold text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($locations as $loc)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-xs text-slate-500">{{ $loc->id }}</td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-800">{{ $loc->name }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($loc->parent_id)
                                    <span class="text-xs bg-sky-500/20 text-sky-600 px-2 py-1 rounded-full">🏙️ منطقة</span>
                                @else
                                    <span class="text-xs bg-emerald-500/20 text-emerald-600 px-2 py-1 rounded-full">👑 محافظة</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500">{{ $loc->parent->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-center">
                                <button onclick="openEditModal({{ $loc->id }}, '{{ $loc->name }}', {{ $loc->parent_id ?? 'null' }})" 
                                        class="bg-blue-500/20 hover:bg-blue-500 text-blue-600 hover:text-white px-3 py-1.5 rounded-lg text-xs transition-all ml-2">
                                    <i class="fas fa-edit ml-1"></i> تعديل
                                </button>
                                
                                <form action="{{ route('admin.locations.destroy', $loc->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا الموقع؟')" 
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500/20 hover:bg-red-500 text-red-600 hover:text-white px-3 py-1.5 rounded-lg text-xs transition-all">
                                        <i class="fas fa-trash ml-1"></i> حذف
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <i class="fas fa-map-marked-alt text-4xl text-slate-300 mb-2 block"></i>
                                <p class="text-slate-500">لا توجد مواقع مضافة</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div id="editModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-slate-900">تعديل الموقع</h3>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="label">اسم الموقع</label>
                <input type="text" name="name" id="edit_name" class="input">
            </div>
            <div class="mb-4">
                <label class="label">التبعية</label>
                <select name="parent_id" id="edit_parent" class="input">
                    <option value="">🚫 محافظة رئيسية (بدون أب)</option>
                </select>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="btn-primary flex-1">حفظ</button>
                <button type="button" onclick="closeEditModal()" class="btn-secondary flex-1">إلغاء</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const governorates = @json($governorates);
    
    function openEditModal(id, name, parentId) {
        document.getElementById('editForm').action = `/admin/locations/${id}`;
        document.getElementById('edit_name').value = name;
        
        const select = document.getElementById('edit_parent');
        select.innerHTML = '<option value="">🚫 محافظة رئيسية (بدون أب)</option>';
        
        governorates.forEach(gov => {
            if (gov.id != id) {
                const option = document.createElement('option');
                option.value = gov.id;
                option.textContent = `📌 تابعة لـ: ${gov.name}`;
                if (parentId === gov.id) option.selected = true;
                select.appendChild(option);
            }
        });
        
        document.getElementById('editModal').classList.add('flex');
        document.getElementById('editModal').classList.remove('hidden');
    }
    
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('editModal').classList.remove('flex');
    }
    
    document.getElementById('editModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeEditModal();
    });
</script>
@endpush
@endsection