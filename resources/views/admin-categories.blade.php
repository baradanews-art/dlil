@extends('layouts.admin')

@section('title', 'إدارة التصنيفات')
@section('page_heading', '📁 إدارة التصنيفات')
@section('page_subheading', 'إدارة أقسام وتصنيفات المنشآت التجارية')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    {{-- Add Category Form --}}
    <div class="lg:col-span-1">
        <div class="card sticky top-24">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h3 class="font-bold text-slate-900 flex items-center gap-2">
                    <i class="fas fa-plus-circle text-emerald-500"></i>
                    إضافة تصنيف جديد
                </h3>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="label">اسم التصنيف *</label>
                        <input type="text" name="name" required placeholder="مثال: مطاعم، صيدليات..." class="input">
                    </div>
                    <div>
                        <label class="label">الأيقونة (Emoji)</label>
                        <input type="text" name="icon" placeholder="مثال: 🍔، 💊، 🛋️" class="input">
                        <p class="text-[10px] text-slate-400 mt-1">تُستخدم لإعطاء مظهر تفاعلي جذاب</p>
                    </div>
                    <button type="submit" class="btn-primary w-full">
                        <i class="fas fa-save ml-2"></i> حفظ التصنيف
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    {{-- Categories List --}}
    <div class="lg:col-span-2">
        <div class="card">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h3 class="font-bold text-slate-900 flex items-center gap-2">
                    <i class="fas fa-th-large text-emerald-500"></i>
                    التصنيفات الحالية
                    <span class="bg-slate-200 text-slate-600 text-xs px-2 py-0.5 rounded-full mr-2">{{ count($categories) }}</span>
                </h3>
            </div>
            
            <div class="p-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @forelse($categories as $cat)
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-200 hover:border-emerald-300 transition-all group">
                        <div class="flex items-center justify-between mb-3">
                            <div class="text-4xl">{{ $cat->icon ?? '📁' }}</div>
                            <div class="flex gap-1">
                                <button onclick="openEditModal({{ $cat->id }}, '{{ $cat->name }}', '{{ $cat->icon }}')" 
                                        class="bg-blue-500/20 hover:bg-blue-500 text-blue-600 hover:text-white p-2 rounded-lg transition-all">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                                <form action="{{ route('admin.categories.destroy', $cat->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا التصنيف؟')" 
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500/20 hover:bg-red-500 text-red-600 hover:text-white p-2 rounded-lg transition-all">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <h4 class="font-bold text-slate-800 text-sm mb-1">{{ $cat->name }}</h4>
                        <p class="text-[10px] text-slate-400 font-mono mb-2">{{ $cat->slug }}</p>
                        <div class="flex justify-between items-center pt-2 border-t border-slate-200">
                            <span class="text-xs text-slate-500">
                                <i class="fas fa-store ml-1"></i> {{ $cat->businesses_count ?? $cat->businesses()->count() }} منشأة
                            </span>
                            <span class="text-[10px] text-slate-400">ID: {{ $cat->id }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center py-12">
                        <i class="fas fa-folder-open text-5xl text-slate-300 mb-3 block"></i>
                        <p class="text-slate-500">لا توجد تصنيفات مضافة</p>
                        <p class="text-slate-400 text-sm mt-1">أضف تصنيفك الأول من النموذج المجاور</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div id="editModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-slate-900">تعديل التصنيف</h3>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="label">اسم التصنيف</label>
                <input type="text" name="name" id="edit_name" class="input">
            </div>
            <div class="mb-4">
                <label class="label">الأيقونة</label>
                <input type="text" name="icon" id="edit_icon" class="input">
            </div>
            <div class="flex gap-3">
                <button type="submit" class="btn-primary flex-1">حفظ التغييرات</button>
                <button type="button" onclick="closeEditModal()" class="btn-secondary flex-1">إلغاء</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openEditModal(id, name, icon) {
        document.getElementById('editForm').action = `/admin/categories/${id}`;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_icon').value = icon || '';
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