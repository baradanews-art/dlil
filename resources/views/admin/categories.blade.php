@extends('layouts.admin')

@section('title', 'إدارة التصنيفات التجارية - لوحة الإشراف')
@section('page_heading', '📁 إدارة أقسام وتصنيفات الأنشطة التجارية')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <div class="lg:col-span-2 bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
        <div class="p-5 border-b border-slate-100 bg-slate-50">
            <h3 class="font-bold text-slate-900 text-base">الأقسام المعتمدة في الدليل</h3>
            <p class="text-xs text-slate-500 mt-1">هذه الأقسام تظهر لجمهور المنصة في الصفحة الرئيسية للتصفح السريع.</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-200 text-slate-700 text-xs font-bold uppercase">
                        <th class="p-4 w-16 text-center">الرمز</th>
                        <th class="p-4">اسم التصنيف</th>
                        <th class="p-4">الرابط الصديق (Slug)</th>
                        <th class="p-4 text-center">التحكم</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($categories as $cat)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="p-4 text-center text-xl bg-slate-50/50">{{ $cat->icon ?? '📁' }}</td>
                            <td class="p-4 font-bold text-slate-900">{{ $cat->name }}</td>
                            <td class="p-4 font-mono text-xs text-slate-500">{{ $cat->slug }}</td>
                            <td class="p-4 text-center">
                                <form action="{{ route('admin.categories.delete', $cat->id) }}" method="POST" onsubmit="return confirm('هل تريد بالتأكيد إزالة هذا التصنيف العام؟');">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="text-xs bg-red-50 text-red-600 font-bold px-3 py-1.5 rounded-lg hover:bg-red-100 transition-colors cursor-pointer">
                                        🗑️ حذف القسم
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-slate-400 font-medium">⚠️ لا توجد تصنيفات معرفة حالياً في قاعدة البيانات.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-xs border border-slate-200 p-6 h-fit sticky top-6">
        <h3 class="font-bold text-slate-900 text-base mb-2 border-b border-slate-100 pb-3">➕ إضافة تصنيف تجاري جديد</h3>
        
        @if(session('success'))
            <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold p-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
            @csrf
            
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-700">اسم التصنيف (بالعربية)</label>
                <input type="text" name="name" placeholder="مثال: مطاعم وجبات سريعة، صيدليات..." class="w-full border border-slate-300 text-sm p-2.5 rounded-lg focus:border-blue-600 focus:outline-none bg-slate-50 font-medium" required>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-700">رمز أو أيقونة التصنيف التعبيرية (Emoji)</label>
                <input type="text" name="icon" placeholder="مثال: 🍔، 💊، 🛠️" class="w-full border border-slate-300 text-sm p-2.5 rounded-lg focus:border-blue-600 focus:outline-none bg-slate-50 text-center">
                <p class="text-[10px] text-slate-400">تُستخدم هذه الأيقونة لإعطاء مظهر تفاعلي جذاب وجلب نقرات أكثر للتصنيف بالصفحة الرئيسية.</p>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm py-3 rounded-lg shadow-xs transition-all cursor-pointer">
                💾 حفظ واعتماد القسم الجديد
            </button>
        </form>
    </div>
</div>
@endsection