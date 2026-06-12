@extends('layouts.admin')

@section('title', 'إدارة المحافظات والمناطق - لوحة الإشراف')
@section('page_heading', '📍 شجرة النطاق الجغرافي (المحافظات والمناطق)')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <div class="lg:col-span-2 bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
        <div class="p-5 border-b border-slate-100 bg-slate-50">
            <h3 class="font-bold text-slate-900 text-base">المواقع المدرجة حالياً</h3>
            <p class="text-xs text-slate-500 mt-1">توضح هذه القائمة شجرة التوزيع الجغرافي داخل دليل سوريا التجاري.</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-200 text-slate-700 text-xs font-bold uppercase">
                        <th class="p-4">الاسم الجغرافي</th>
                        <th class="p-4">التبعية الإدارية</th>
                        <th class="p-4">الرابط اللطيف (Slug)</th>
                        <th class="p-4 text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($locations as $loc)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="p-4 font-bold text-slate-900">{{ $loc->name }}</td>
                            <td class="p-4">
                                @if($loc->parent)
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-md bg-blue-50 text-blue-700 border border-blue-100">
                                        منطقة فرعية في: {{ $loc->parent->name }}
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-md bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        ⭐ محافظة رئيسية
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 font-mono text-xs text-slate-500">{{ $loc->slug }}</td>
                            <td class="p-4 text-center">
                                <form action="{{ route('admin.locations.delete', $loc->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذا الموقع؟ قد يؤثر هذا على المحلات المرتبطة به!');">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="text-xs bg-red-50 text-red-600 font-bold px-3 py-1.5 rounded-lg hover:bg-red-100 transition-colors cursor-pointer">
                                        🗑️ إزالة
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-slate-400 font-medium">⚠️ لا توجد أي محافظات أو مدن مضافة حالياً في شجرة البيانات.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-xs border border-slate-200 p-6 h-fit sticky top-6">
        <h3 class="font-bold text-slate-900 text-base mb-2 border-b border-slate-100 pb-3">➕ إضافة موقع جغرافي جديد</h3>
        
        @if(session('success'))
            <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold p-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.locations.store') }}" method="POST" class="space-y-4">
            @csrf
            
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-700">الاسم الجغرافي (بالعربية)</label>
                <input type="text" name="name" placeholder="مثال: دمشق، قدسيا، جرمانا..." class="w-full border border-slate-300 text-sm p-2.5 rounded-lg focus:border-blue-600 focus:outline-none bg-slate-50 font-medium" required>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-700">التبعية الجغرافية (الموقع الأم)</label>
                <select name="parent_id" class="w-full border border-slate-300 text-sm p-2.5 rounded-lg focus:border-blue-600 focus:outline-none bg-slate-50 font-bold">
                    <option value="">-- جعلها محافظة رئيسية أساسية --</option>
                    @foreach($parentLocations as $parentGov)
                        <option value="{{ $parentGov->id }}">تابعة لمحافظة: {{ $parentGov->name }}</option>
                    @endforeach
                </select>
                <p class="text-[10px] text-slate-400 leading-relaxed">اختر المحافظة الأم إذا كنت تضيف بلدة فرعية (مثل اختيار ريف دمشق عند إضافة قدسيا) أو اتركها فارغة لإنشاء محافظة مستقلة.</p>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm py-3 rounded-lg shadow-xs transition-all cursor-pointer">
                💾 حفظ التوزيع الجغرافي الجديد
            </button>
        </form>
    </div>
</div>
@endsection