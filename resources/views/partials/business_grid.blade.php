@foreach($featuredBusinesses as $bus)
<div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/40 overflow-hidden group hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
    <div class="relative h-48 overflow-hidden bg-slate-100">
        <img src="{{ $bus->cover_url }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" loading="lazy" alt="{{ $bus->title }}">
        @if($bus->delivery_available ?? false)
            <span class="absolute bottom-3 left-3 bg-emerald-500 text-white text-[10px] font-black px-2.5 py-1 rounded-xl shadow-sm">🛵 توصيل متاح</span>
        @endif
    </div>
    <div class="p-5 flex-1 flex flex-col justify-between">
        <div>
            <div class="flex items-start gap-3">
                <img src="{{ $bus->logo_url }}" class="w-11 h-11 rounded-xl object-cover border border-slate-100 dark:border-slate-700 shadow-inner bg-white" alt="{{ $bus->title }}" loading="lazy">
                <div class="flex-1 overflow-hidden">
                    <h3 class="font-bold text-slate-800 dark:text-white line-clamp-1 group-hover:text-emerald-600 transition-colors text-base">{{ $bus->title }}</h3>
                    <p class="text-xs text-slate-400 mt-0.5 flex items-center gap-1"><i class="fas fa-folder text-slate-300"></i> {{ $bus->category->name ?? 'عام' }}</p>
                </div>
            </div>
            <p class="text-xs text-slate-600 dark:text-slate-400 mt-3.5 line-clamp-2 leading-relaxed">{{ Str::limit($bus->description, 75) }}</p>
        </div>
        <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-700/60 flex justify-between items-center">
            <div class="text-amber-400 text-xs flex gap-0.5">
                @for($i=1;$i<=5;$i++)
                    <i class="fas fa-star{{ $i <= ($bus->reviews_avg_rating ?? 0) ? '' : '-o' }}"></i>
                @endfor
            </div>
            <a href="{{ route('business.show', $bus->slug) }}" class="text-emerald-600 dark:text-emerald-400 text-xs font-bold hover:underline flex items-center gap-0.5">التفاصيل <i class="fas fa-chevron-left text-[9px]"></i></a>
        </div>
    </div>
</div>
@endforeach