@extends('layouts.admin')

@section('title', 'إدارة المنشآت التجارية')
@section('page_heading', '🏢 إدارة المنشآت التجارية')
@section('page_subheading', 'مراجعة وتعديل وحذف المنشآت المسجلة في الدليل')

@section('content')
<div class="space-y-6">
    
    {{-- Filters --}}
    <div class="card">
        <div class="p-4 bg-slate-50 border-b border-slate-200">
            <h3 class="font-bold text-slate-800 text-sm">🔍 فلترة المنشآت</h3>
        </div>
        <div class="p-4">
            <form method="GET" action="{{ route('admin.businesses.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
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
                <button type="submit" class="btn-primary py-2 text-sm">بحث <i class="fas fa-search mr-1"></i></button>
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
                        <th class="px-4 py-3">الموقع</th>
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
                                <img src="{{ $bus->logo_url }}" class="w-8 h-8 rounded-lg object-cover" loading="lazy" onerror="this.src='https://placehold.co/200x200/1e293b/10b981?text=🏪'">
                                <div>
                                    <div class="font-bold text-slate-800 text-sm">{{ $bus->title }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $bus->slug }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-600">{{ $bus->category->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-xs text-slate-600">{{ $bus->location->name ?? '-' }}</td>
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
                        <td colspan="8" class="px-4 py-12 text-center text-slate-500">
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
@endsection