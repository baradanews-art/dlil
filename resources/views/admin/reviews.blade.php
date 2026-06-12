@extends('layouts.admin')

@section('title', 'إدارة وتدقيق التقييمات - دليل سوريا')
@section('page_heading', '⭐ لوحة الرقابة على مراجعات وتقييمات الجمهور')

@section('content')
<div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
    <div class="p-5 border-b border-slate-100 bg-slate-50">
        <h3 class="font-bold text-slate-900 text-base">آخر التقييمات المنشورة</h3>
        <p class="text-xs text-slate-500 mt-1">تتيح لك هذه اللوحة حذف التعليقات العشوائية أو المسيئة المكتوبة من قبل عامة زوار الموقع والعملاء.</p>
    </div>

    @if(session('success'))
        <div class="m-5 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold p-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-right border-collapse">
            <thead>
                <tr class="bg-slate-100 border-b border-slate-200 text-slate-700 text-xs font-bold uppercase">
                    <th class="p-4">صاحب المراجعة</th>
                    <th class="p-4">المنشأة المستهدفة</th>
                    <th class="p-4 text-center">مستوى التقييم</th>
                    <th class="p-4">نص المراجعة والتعليق</th>
                    <th class="p-4 text-center">الرقابة والإجراء</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($reviews as $rev)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="p-4 font-bold text-slate-900">
                            {{ $rev->reviewer_name ?? 'زائر عابر' }}
                            @if($rev->user_id)
                                <span class="block text-[10px] text-blue-600 font-semibold">(مستخدم مسجل ✔️)</span>
                            @endif
                        </td>
                        <td class="p-4 text-slate-600 font-semibold">
                            {{ $rev->business->title ?? '🚫 منشأة تم إزالتها' }}
                        </td>
                        <td class="p-4 text-center text-amber-500 font-bold tracking-tighter text-base">
                            {{ str_repeat('★', $rev->rating) }}{{ str_repeat('☆', 5 - $rev->rating) }}
                        </td>
                        <td class="p-4 text-slate-700 font-medium max-w-xs md:max-w-md break-words bg-slate-50/30 italic">
                            " {{ $rev->comment }} "
                        </td>
                        <td class="p-4 text-center">
                            <form action="{{ route('admin.reviews.delete', $rev->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد تماماً من رغبتك في حذف هذا التقييم نهائياً من الدليل؟');">
                                @csrf 
                                @method('DELETE')
                                <button type="submit" class="text-xs bg-red-50 text-red-600 hover:bg-red-100 font-bold px-3 py-1.5 rounded-lg transition-colors cursor-pointer">
                                    🗑️ إزالة وحذف فورى
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-10 text-center text-slate-400 font-bold">🎉 لا توجد مراجعات أو تقييمات منشورة حالياً في الدليل لتدقيقها. الكل نظيف!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection