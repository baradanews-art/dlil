@foreach($businesses as $bus)
<div class="business-card group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">
    <div class="relative h-48 overflow-hidden bg-slate-200">
        {{-- Cover Image with lazy loading --}}
        <img src="{{ $bus->cover_url }}" 
             alt="{{ $bus->title }}" 
             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
             loading="lazy"
             onerror="this.src='https://placehold.co/600x400/1e293b/10b981?text=🏪'">
        
        {{-- Badges --}}
        <div class="absolute top-3 right-3 flex gap-2">
            @if($bus->verification_type == 'official')
                <span class="bg-amber-500 text-white text-[10px] font-bold px-2 py-1 rounded-full shadow-lg">👑 رسمي</span>
            @elseif($bus->verification_type == 'verified')
                <span class="bg-blue-500 text-white text-[10px] font-bold px-2 py-1 rounded-full shadow-lg">✓ موثق</span>
            @endif
            @if($bus->delivery_available)
                <span class="bg-emerald-500 text-white text-[10px] font-bold px-2 py-1 rounded-full shadow-lg">🛵 توصيل</span>
            @endif
        </div>
        
        {{-- Rating --}}
        @if($bus->rating_avg > 0)
        <div class="absolute bottom-3 left-3 bg-black/60 backdrop-blur-sm rounded-full px-2 py-1">
            <i class="fas fa-star text-amber-400 text-xs"></i>
            <span class="text-white text-xs font-bold">{{ number_format($bus->rating_avg, 1) }}</span>
        </div>
        @endif
    </div>
    
    <div class="p-4">
        <div class="flex items-start justify-between gap-2">
            <h3 class="font-bold text-slate-900 group-hover:text-emerald-600 transition-colors line-clamp-1">
                <a href="{{ route('business.show', $bus->slug) }}">{{ $bus->title }}</a>
            </h3>
            {{-- Small Logo --}}
            <img src="{{ $bus->logo_url }}" 
                 alt="{{ $bus->title }}" 
                 class="w-10 h-10 rounded-xl object-cover border border-slate-200"
                 loading="lazy"
                 onerror="this.src='https://placehold.co/100x100/1e293b/10b981?text=🏪'">
        </div>
        
        <p class="text-xs text-slate-500 mt-2 line-clamp-2">{{ Str::limit($bus->description, 80) }}</p>
        
        <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-100">
            <span class="text-xs text-slate-500">
                <i class="fas fa-map-marker-alt ml-1"></i> {{ $bus->location->name ?? 'سوريا' }}
            </span>
            <a href="{{ route('business.show', $bus->slug) }}" 
               class="text-emerald-600 text-xs font-bold hover:underline inline-flex items-center gap-1">
                تفاصيل <i class="fas fa-arrow-left"></i>
            </a>
        </div>
    </div>
</div>
@endforeach