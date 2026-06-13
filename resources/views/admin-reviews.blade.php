@extends('layouts.admin')

@section('title', 'إدارة التقييمات')
@section('page_heading', '⭐ إدارة التقييمات')
@section('page_subheading', 'مراقبة وإدارة تقييمات ومراجعات الزوار')

@section('content')
<div class="card">
    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
        <div class="flex justify-between items-center flex-wrap gap-3">
            <h3 class="font-bold text-slate-900 flex items-center gap-2">
                <i class="fas fa-list text-emerald-500"></i>
                جميع التقييمات ({{ $reviews->total() ?? count($reviews) }})
            </h3>
            <div class="relative">
                <i class="fas fa-search absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" id="searchInput" placeholder="بحث..." class="input !w-64 !py-2 !text-sm">
            </div>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-right">
            <thead class="bg-slate-50">
                <tr class="text-xs text-slate-500 border-b border-slate-200">
                    <th class="px-6 py-3 font-bold">المراجع</th>
                    <th class="px-6 py-3 font-bold">المنشأة</th>
                    <th class="px-6 py-3 font-bold">التقييم</th>
                    <th class="px-6 py-3 font-bold">التعليق</th>
                    <th class="px-6 py-3 font-bold">الرد</th>
                    <th class="px-6 py-3 font-bold">التاريخ</th>
                    <th class="px-6 py-3 font-bold text-center">الإجراءات</th>
                 </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($reviews as $rev)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-slate-500 text-xs"></i>
                            </div>
                            <span class="text-sm font-bold text-slate-800">{{ $rev->reviewer_name ?? 'زائر' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('business.show', $rev->business->slug ?? '#') }}" 
                           target="_blank" 
                           class="text-emerald-600 hover:text-emerald-700 text-sm">
                            {{ $rev->business->title ?? 'منشأة محذوفة' }}
                            <i class="fas fa-external-link-alt text-[10px] mr-1"></i>
                        </a>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $rev->rating)
                                    <i class="fas fa-star text-amber-400 text-xs"></i>
                                @else
                                    <i class="far fa-star text-slate-300 text-xs"></i>
                                @endif
                            @endfor
                            <span class="text-xs text-slate-500 mr-2">({{ $rev->rating }}/5)</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 max-w-xs">
                        <p class="text-xs text-slate-600 line-clamp-2">{{ Str::limit($rev->comment, 60) }}</p>
                    </td>
                    <td class="px-6 py-4 max-w-xs">
                        @if($rev->reply)
                            <p class="text-xs text-emerald-600 line-clamp-2">{{ Str::limit($rev->reply, 50) }}</p>
                        @else
                            <span class="text-xs text-slate-400">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-xs text-slate-500">{{ $rev->created_at->format('Y-m-d') }}</td>
                    <td class="px-6 py-4 text-center">
                        <button onclick="openReplyModal({{ $rev->id }}, '{{ addslashes($rev->reviewer_name) }}', {{ $rev->rating }}, '{{ addslashes($rev->comment) }}', '{{ addslashes($rev->reply) }}')" 
                                class="bg-blue-500/20 hover:bg-blue-500 text-blue-600 hover:text-white px-3 py-1.5 rounded-lg text-xs transition-all ml-2">
                            <i class="fas fa-reply ml-1"></i> رد
                        </button>
                        
                        <form action="{{ route('admin.reviews.destroy', $rev->id) }}" 
                              method="POST" 
                              onsubmit="return confirm('هل أنت متأكد من حذف هذا التقييم؟')" 
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
                    <td colspan="7" class="px-6 py-12 text-center">
                        <i class="fas fa-star-of-life text-4xl text-slate-300 mb-2 block"></i>
                        <p class="text-slate-500">لا توجد تقييمات حالياً</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if(isset($reviews) && method_exists($reviews, 'links'))
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $reviews->links() }}
        </div>
    @endif
</div>

{{-- Reply Modal --}}
<div id="replyModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 w-full max-w-lg mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-slate-900">الرد على التقييم</h3>
            <button onclick="closeReplyModal()" class="text-slate-400 hover:text-slate-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="replyForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="label">الرد</label>
                <textarea name="reply" id="reply_text" rows="4" required class="input" placeholder="اكتب ردك على هذا التقييم..."></textarea>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="btn-primary flex-1">إرسال الرد</button>
                <button type="button" onclick="closeReplyModal()" class="btn-secondary flex-1">إلغاء</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openReplyModal(id, name, rating, comment, reply) {
        document.getElementById('replyForm').action = `/admin/reviews/${id}`;
        document.getElementById('reply_text').value = reply || '';
        document.getElementById('replyModal').classList.add('flex');
        document.getElementById('replyModal').classList.remove('hidden');
    }
    
    function closeReplyModal() {
        document.getElementById('replyModal').classList.add('hidden');
        document.getElementById('replyModal').classList.remove('flex');
    }
    
    document.getElementById('replyModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeReplyModal();
    });
    
    document.getElementById('searchInput')?.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
</script>
@endpush
@endsection