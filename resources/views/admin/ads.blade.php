@extends('layouts.admin')

@section('title', 'إدارة الرعايات والمساحات الإعلانية')
@section('page_heading', '📢 إدارة مساحات البنرات الرعائية والإعلانات المميزة')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <div class="lg:col-span-2 bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
        <div class="p-5 border-b border-slate-100 bg-slate-50">
            <h3 class="font-bold text-slate-900 text-base">البنرات النشطة بالموقع</h3>
            <p class="text-xs text-slate-500 mt-1">البنرات المعروضة لعامة الزوار لمساعدتك على تسييل الدليل وربح المال من الرعايات.</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-200 text-slate-700 text-xs font-bold uppercase">
                        <th class="p-4 w-24">معاينة البنر</th>
                        <th class="p-4">عنوان الإعلان / المعلن</th>
                        <th class="p-4">منطقة العرض</th>
                        <th class="p-4 text-center">التحكم</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($ads as $ad)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="p-4">
                                @if($ad->image_path)
                                    <img src="{{ asset('storage/' . $ad->image_path) }}" alt="Ad Banner" class="w-16 h-12 object-cover rounded-md shadow-xs border border-slate-200">
                                @else
                                    <span class="text-xs text-slate-400">لا توجد صورة</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <span class="font-bold text-slate-900 block">{{ $ad->title }}</span>
                                @if($ad->link_url)
                                    <a href="{{ $ad->link_url }}" target="_blank" class="text-[11px] text-blue-500 font-mono hover:underline truncate block max-w-xs">{{ $ad->link_url }}</a>
                                @endif
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-0.5 text-xs font-bold rounded-sm bg-purple-50 text-purple-700 border border-purple-100">
                                    🎯 {{ $ad->position ?? 'sidebar' }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <form action="{{ route('admin.ads.delete', $ad->id) }}" method="POST" onsubmit="return confirm('هل تريد إزالة هذا البنر الإعلاني بشكل نهائي؟');">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="text-xs bg-red-50 text-red-600 font-bold px-3 py-1.5 rounded-lg hover:bg-red-100 transition-colors cursor-pointer">
                                        🗑️ إنهاء الرعاية
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-slate-400 font-medium">⚠️ لم يتم حجز أو إطلاق بنرات إعلانية حتى الآن في هذا القسم.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-xs border border-slate-200 p-6 h-fit sticky top-6">
        <h3 class="font-bold text-slate-900 text-base mb-2 border-b border-slate-100 pb-3">➕ إطلاق رعاية / بنر إعلاني جديد</h3>
        
        @if(session('success'))
            <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold p-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.ads.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-700">عنوان الرعاية / اسم الشركة المعلنة</label>
                <input type="text" name="title" placeholder="مثال: مطعم الشام - فرع قدسيا الرائد" class="w-full border border-slate-300 text-sm p-2.5 rounded-lg focus:border-blue-600 focus:outline-none bg-slate-50 font-medium" required>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-700">رابط التوجيه الفوري المستهدف (Link URL)</label>
                <input type="url" name="link" placeholder="https://example.com" class="w-full border border-slate-300 text-sm p-2.5 rounded-lg focus:border-blue-600 focus:outline-none bg-slate-50 font-mono text-left" dir="ltr">
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-700">موقع العرض المناسب للبنر داخل الموقع</label>
                <select name="position" class="w-full border border-slate-300 text-sm p-2.5 rounded-lg focus:border-blue-600 focus:outline-none bg-slate-50 font-bold">
                    <option value="sidebar">🎯 القائمة الجانبية اليسيرة للموقع الرئيسي (Sidebar)</option>
                </select>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-700">ملف البنر أو صورة الإعلان المخصصة</label>
                <input type="file" name="image" class="w-full border border-slate-300 text-xs p-2 rounded-lg bg-slate-50 file:ml-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                <p class="text-[10px] text-slate-400">يفضل رفع صور بأبعاد متناسقة (أقل من 2 ميغابايت) للحفاظ على سرعة تصفح المستخدمين الفائقة.</p>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm py-3 rounded-lg shadow-xs transition-all cursor-pointer">
                🚀 نشر وتشغيل الإعلان فوراً
            </button>
        </form>
    </div>
</div>
@endsection