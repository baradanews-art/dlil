@extends('layouts.admin')

@section('title', 'إدارة المؤسسات الرسمية - ' . ($type == 'government' ? 'حكومية' : ($type == 'security' ? 'أمن ونجدة' : 'مراكز مساعدة')))
@section('page_heading', '🏛️ إدارة المؤسسات الرسمية')
@section('page_subheading', 'إضافة وتعديل وحذف المؤسسات الحكومية والأمنية ومراكز المساعدة')

@section('content')
<div class="space-y-6">
    
    {{-- Type Tabs --}}
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
        
        <a href="{{ route('admin.official.create', ['type' => $type]) }}" 
           class="btn-primary !py-2.5">
            <i class="fas fa-plus ml-1"></i> إضافة جديدة
        </a>
    </div>
    
    {{-- Entities Table --}}
    <div class="card">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
            <h3 class="font-bold text-slate-900 flex items-center gap-2">
                <i class="fas fa-list text-emerald-500"></i>
                قائمة المؤسسات ({{ count($entities ?? []) }})
            </h3>
        </div>
        
        @if(isset($entities) && count($entities) > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-right">
                <thead class="bg-slate-50">
                    <tr class="text-xs text-slate-500 border-b border-slate-200">
                        <th class="px-6 py-3 font-bold">#</th>
                        <th class="px-6 py-3 font-bold">الشعار</th>
                        <th class="px-6 py-3 font-bold">الاسم</th>
                        <th class="px-6 py-3 font-bold">النوع</th>
                        <th class="px-6 py-3 font-bold">الهاتف</th>
                        <th class="px-6 py-3 font-bold">طوارئ</th>
                        <th class="px-6 py-3 font-bold">المحافظة</th>
                        <th class="px-6 py-3 font-bold">المنطقة</th>
                        <th class="px-6 py-3 font-bold text-center">الإجراءات</th>
                     </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($entities as $index => $entity)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-xs text-slate-500">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            <img src="{{ $entity->logo_url }}" 
                                 class="w-10 h-10 rounded-lg object-cover"
                                 loading="lazy"
                                 onerror="this.src='https://placehold.co/100x100/1e293b/ffffff?text=🏛️'">
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-slate-800">{{ $entity->name }}</td>
                        <td class="px-6 py-4">
                            <span class="text-xs px-2 py-1 rounded-full 
                                @if($entity->type == 'government') bg-green-500/20 text-green-600
                                @elseif($entity->type == 'security') bg-red-500/20 text-red-600
                                @else bg-blue-500/20 text-blue-600 @endif">
                                {{ $entity->type_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-600" dir="ltr">{{ $entity->phone ?? '—' }}</td>
                        <td class="px-6 py-4 text-xs text-slate-600" dir="ltr">{{ $entity->hotline ?? '—' }}</td>
                        <td class="px-6 py-4 text-xs text-slate-600">{{ $entity->city->name ?? '—' }}</td>
                        <td class="px-6 py-4 text-xs text-slate-600">{{ $entity->region->name ?? '—' }}</td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.official.edit', $entity->id) }}" 
                               class="bg-blue-500/20 hover:bg-blue-500 text-blue-600 hover:text-white px-3 py-1.5 rounded-lg text-xs transition-all ml-2">
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
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-building text-5xl text-slate-300 mb-3 block"></i>
            <p class="text-slate-500">لا توجد مؤسسات مضافة حالياً</p>
            <a href="{{ route('admin.official.create', ['type' => $type]) }}" class="inline-block mt-4 btn-primary">
                <i class="fas fa-plus ml-1"></i> أضف أول مؤسسة
            </a>
        </div>
        @endif
    </div>
</div>
@endsection