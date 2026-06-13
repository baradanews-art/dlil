<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'الجهات الرسمية' }} | دليل سوريا التجاري</title>
    <meta name="description" content="{{ $description ?? 'دليل الجهات الرسمية في سوريا' }}">
    <meta name="robots" content="index, follow">
    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        .entity-card {
            transition: all 0.3s ease;
        }
        .entity-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body class="bg-slate-50 font-sans antialiased">

    {{-- Header Section --}}
    <div class="bg-gradient-to-r {{ $bgColor ?? 'from-green-700 to-green-600' }} text-white py-16 px-4 text-center">
        <div class="max-w-4xl mx-auto">
            <div class="text-6xl mb-4">
                <i class="fas {{ $icon ?? 'fa-landmark' }}"></i>
            </div>
            <h1 class="text-3xl md:text-4xl font-black">{{ $title ?? 'الجهات الرسمية' }}</h1>
            <p class="text-white/80 mt-2">{{ $description ?? 'دليل شامل للجهات الرسمية في سوريا' }}</p>
            <a href="{{ route('home') }}" class="inline-block mt-6 bg-white/20 hover:bg-white/30 rounded-xl px-6 py-2 text-sm transition-all">
                <i class="fas fa-arrow-right ml-2"></i> العودة للرئيسية
            </a>
        </div>
    </div>

    {{-- Search Section --}}
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="bg-white rounded-2xl shadow-md p-6 mb-8">
            <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                <i class="fas fa-search text-emerald-600"></i> بحث وتصفية
            </h3>
            <form method="GET" action="{{ url()->current() }}" id="filterForm" class="space-y-4">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="ابحث بالاسم، الوصف، أو العنوان..." 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <select name="city_id" id="city_id" class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm">
                        <option value="">جميع المحافظات</option>
                        @foreach($cities ?? [] as $city)
                            <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                        @endforeach
                    </select>
                    
                    <select name="region_id" id="region_id" class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm">
                        <option value="">جميع المناطق</option>
                        @if(request('city_id'))
                            @foreach($regions ?? [] as $region)
                                <option value="{{ $region->id }}" {{ request('region_id') == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    
                    <select name="sub_type" class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm">
                        <option value="">جميع الأنواع</option>
                        @foreach($subTypes ?? [] as $key => $label)
                            <option value="{{ $key }}" {{ request('sub_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex gap-3">
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-xl transition-all">
                        <i class="fas fa-search ml-1"></i> بحث
                    </button>
                    <a href="{{ url()->current() }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-6 py-2 rounded-xl transition-all">
                        <i class="fas fa-undo-alt ml-1"></i> إعادة ضبط
                    </a>
                </div>
            </form>
        </div>
        
        {{-- Results Count --}}
        <div class="mb-4 flex justify-between items-center">
            <span class="text-sm text-slate-500">
                <i class="fas fa-building ml-1"></i> {{ count($entities ?? []) }} نتيجة
            </span>
        </div>
        
        {{-- Results Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($entities ?? [] as $entity)
            <a href="{{ route('official.show', $entity->slug) }}" class="entity-card bg-white rounded-2xl shadow-md hover:shadow-xl transition-all p-6 block group">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 bg-{{ $entity->color }}-100 rounded-xl flex items-center justify-center group-hover:bg-{{ $entity->color }}-600 transition-colors">
                        <i class="fas {{ $entity->icon }} text-2xl text-{{ $entity->color }}-600 group-hover:text-white transition-colors"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 group-hover:text-emerald-600 transition-colors">{{ $entity->name }}</h3>
                        @if($entity->sub_type)
                            <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full">{{ $entity->sub_type_label }}</span>
                        @endif
                        @if($entity->working_hours)
                            <p class="text-xs text-slate-500 mt-1"><i class="far fa-clock ml-1"></i> {{ $entity->working_hours }}</p>
                        @endif
                    </div>
                </div>
                
                @if($entity->address)
                    <p class="text-sm text-slate-600 flex items-center gap-2"><i class="fas fa-map-marker-alt text-emerald-600"></i> {{ Str::limit($entity->address, 60) }}</p>
                @endif
                
                @if($entity->phone)
                    <p class="text-sm text-slate-600 flex items-center gap-2 mt-2"><i class="fas fa-phone-alt text-emerald-600"></i> {{ $entity->phone }}</p>
                @endif
                
                @if($entity->hotline)
                    <p class="text-sm text-red-600 flex items-center gap-2 mt-1"><i class="fas fa-phone-alt"></i> طوارئ: {{ $entity->hotline }}</p>
                @endif
            </a>
            @empty
            <div class="col-span-full text-center py-12 bg-white rounded-2xl">
                <i class="fas fa-building text-5xl text-slate-300 mb-3 block"></i>
                <p class="text-slate-500">لا توجد نتائج مطابقة لمعايير البحث</p>
                <p class="text-slate-400 text-sm mt-1">حاول تغيير معايير البحث</p>
            </div>
            @endforelse
        </div>
    </div>

    <script>
        const citySelect = document.getElementById('city_id');
        const regionSelect = document.getElementById('region_id');
        
        if (citySelect) {
            citySelect.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        }
    </script>
</body>
</html>