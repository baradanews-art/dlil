<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    
    {{-- ============================================================ --}}
    {{-- 🔥 SEO META TAGS المتطورة --}}
    {{-- ============================================================ --}}
    <title>{{ $business->seo_title ?? $business->title }}</title>
    <meta name="description" content="{{ $business->seo_description ?? Str::limit($business->description, 160) }}">
    <meta name="keywords" content="{{ $business->seo_keywords ?? $business->category->name ?? '' }}, دليل سوريا, منشآت سورية">
    <meta name="author" content="دليل سوريا التجاري">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large">
    
    {{-- Canonical URL --}}
    <link rel="canonical" href="{{ url()->current() }}">
    
    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $business->title }}">
    <meta property="og:description" content="{{ Str::limit($business->description, 200) }}">
    <meta property="og:image" content="{{ $business->cover_url ?? $business->logo_url ?? 'https://placehold.co/1200x630/1e293b/10b981?text=' . urlencode($business->title) }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="دليل سوريا التجاري">
    <meta property="og:locale" content="ar_AR">
    
    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $business->title }}">
    <meta name="twitter:description" content="{{ Str::limit($business->description, 200) }}">
    <meta name="twitter:image" content="{{ $business->cover_url ?? $business->logo_url ?? 'https://placehold.co/1200x630/1e293b/10b981?text=' . urlencode($business->title) }}">
    
    {{-- Geo Tags (للمواقع الجغرافية) --}}
    @if($business->latitude && $business->longitude)
        <meta name="geo.position" content="{{ $business->latitude }};{{ $business->longitude }}">
        <meta name="geo.placename" content="{{ $business->location->name ?? '' }}">
        <meta name="geo.region" content="SY">
        <meta name="ICBM" content="{{ $business->latitude }}, {{ $business->longitude }}">
    @endif
    
    {{-- Business Meta Tags --}}
    <meta name="business:name" content="{{ $business->title }}">
    <meta name="business:category" content="{{ $business->category->name ?? '' }}">
    <meta name="business:phone" content="{{ $business->phone }}">
    
    {{-- Preconnect for faster loading --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://unpkg.com">
    
    {{-- Tailwind CSS --}}
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    {{-- Leaflet CSS --}}
    <link rel="preload" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" as="style">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    {{-- Custom Styles --}}
    <style>
        html { scroll-behavior: smooth; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: #10b981; border-radius: 10px; }
        .gallery-image { transition: transform 0.3s ease, opacity 0.3s ease; }
        .gallery-image:hover { transform: scale(1.05); }
        .star-rating { direction: ltr; display: inline-flex; gap: 2px; }
        .review-card { transition: all 0.3s ease; }
        .review-card:hover { transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
        #map-viewer { height: 300px; width: 100%; border-radius: 16px; z-index: 1; }
        @media print { header, footer, .no-print { display: none; } body { background: white; } .print-full { width: 100%; margin: 0; padding: 0; } }
    </style>
</head>
<body class="bg-gradient-to-b from-slate-50 to-white font-sans antialiased">

    {{-- ============================================================ --}}
    {{-- 🍞 BREADCRUMB - مسار التصفح لتحسين SEO --}}
    {{-- ============================================================ --}}
    <div class="max-w-7xl mx-auto px-4 py-3 text-sm text-slate-500 no-print">
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('home') }}" class="hover:text-emerald-600 transition-colors">الرئيسية</a>
            <span>/</span>
            <a href="{{ route('home', ['category' => $business->category->slug ?? '']) }}" class="hover:text-emerald-600 transition-colors">
                {{ $business->category->name ?? 'تصنيفات' }}
            </a>
            @if($business->location && $business->location->parent)
                <span>/</span>
                <a href="{{ route('home', ['location' => $business->location->parent->slug]) }}" class="hover:text-emerald-600 transition-colors">
                    {{ $business->location->parent->name }}
                </a>
            @endif
            <span>/</span>
            <span class="text-emerald-600 font-bold">{{ $business->title }}</span>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- 🎯 MAIN BUSINESS CARD - البطاقة الرئيسية --}}
    {{-- ============================================================ --}}
    <div class="max-w-7xl mx-auto px-4 py-6 lg:py-8">
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-100">
            
            {{-- Cover Image with Gradient Overlay --}}
            <div class="relative h-56 md:h-80 lg:h-96 overflow-hidden bg-gradient-to-r from-slate-800 to-slate-900">
                @php
                    $coverUrl = $business->cover ? url('/image/covers/' . basename($business->cover)) : null;
                @endphp
                @if($coverUrl)
                    <img src="{{ $coverUrl }}" 
                         alt="{{ $business->title }}" 
                         class="w-full h-full object-cover"
                         fetchpriority="high"
                         width="1200" height="400"
                         onerror="this.onerror=null; this.src='https://placehold.co/1200x400/1e293b/10b981?text=' + encodeURIComponent('{{ $business->title }}')">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <span class="text-8xl opacity-20">🏪</span>
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                @endif
                
                {{-- Logo Overlay --}}
                <div class="absolute -bottom-8 right-4 md:right-8 lg:right-12 z-10">
                    @php
                        $logoUrl = $business->logo ? url('/image/logos/' . basename($business->logo)) : null;
                    @endphp
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" 
                             alt="{{ $business->title }} - شعار" 
                             class="w-20 h-20 md:w-28 md:h-28 rounded-2xl object-cover border-4 border-white shadow-xl bg-white"
                             width="112" height="112"
                             loading="lazy"
                             onerror="this.onerror=null; this.src='https://placehold.co/200x200/1e293b/10b981?text=' + encodeURIComponent('{{ substr($business->title, 0, 2) }}')">
                    @else
                        <div class="w-20 h-20 md:w-28 md:h-28 rounded-2xl bg-white shadow-xl flex items-center justify-center border-4 border-white">
                            <span class="text-3xl md:text-4xl">🏪</span>
                        </div>
                    @endif
                </div>
                
                {{-- Badges --}}
                <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                    @if($business->verification_type == 'official')
                        <span class="bg-amber-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg flex items-center gap-1">
                            <span>👑</span> رسمي معتمد
                        </span>
                    @elseif($business->verification_type == 'verified')
                        <span class="bg-blue-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg flex items-center gap-1">
                            <span>✓</span> موثق
                        </span>
                    @endif
                    
                    @if($business->delivery_available)
                        <span class="bg-emerald-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg flex items-center gap-1">
                            <span>🛵</span> توصيل متاح
                        </span>
                    @endif
                </div>
                
                {{-- Rating Badge --}}
                @if($business->rating_avg > 0)
                <div class="absolute bottom-4 left-4 bg-black/70 backdrop-blur-sm rounded-full px-3 py-1.5 flex items-center gap-2">
                    <div class="star-rating">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($business->rating_avg))
                                <i class="fas fa-star text-amber-400 text-xs"></i>
                            @elseif($i - 0.5 <= $business->rating_avg)
                                <i class="fas fa-star-half-alt text-amber-400 text-xs"></i>
                            @else
                                <i class="far fa-star text-slate-400 text-xs"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="text-white text-sm font-bold">{{ number_format($business->rating_avg, 1) }}</span>
                    <span class="text-white/70 text-xs">({{ $business->reviews_count }} تقييم)</span>
                </div>
                @endif
            </div>
            
            {{-- Business Info --}}
            <div class="p-6 md:p-8">
                <div class="flex flex-wrap justify-between items-start gap-4 mb-6">
                    <div>
                        <h1 class="text-2xl md:text-3xl lg:text-4xl font-black text-slate-900 mb-2">
                            {{ $business->title }}
                        </h1>
                        <div class="flex flex-wrap gap-3 text-sm text-slate-500">
                            <span class="inline-flex items-center gap-1">
                                <span>📁</span>
                                {{ $business->category->name ?? 'تصنيف عام' }}
                            </span>
                            <span class="inline-flex items-center gap-1">
                                <span>📍</span>
                                @if($business->location && $business->location->parent)
                                    {{ $business->location->parent->name }} - 
                                @endif
                                {{ $business->location->name ?? 'سوريا' }}
                            </span>
                            @if($business->created_at)
                            <span class="inline-flex items-center gap-1">
                                <span>📅</span>
                                أضيف {{ $business->created_at->diffForHumans() }}
                            </span>
                            @endif
                        </div>
                    </div>
                    
                    {{-- Share Buttons --}}
                    <div class="flex gap-2 no-print">
                        <button onclick="shareOnWhatsApp()" class="bg-green-50 hover:bg-green-100 text-green-700 p-2 rounded-xl transition-colors" title="مشاركة واتساب">
                            <i class="fab fa-whatsapp text-xl"></i>
                        </button>
                        <button onclick="shareOnFacebook()" class="bg-blue-50 hover:bg-blue-100 text-blue-700 p-2 rounded-xl transition-colors" title="مشاركة فيسبوك">
                            <i class="fab fa-facebook-f text-xl"></i>
                        </button>
                        <button onclick="copyLink()" class="bg-slate-100 hover:bg-slate-200 text-slate-600 p-2 rounded-xl transition-colors" title="نسخ الرابط">
                            <i class="fas fa-link text-xl"></i>
                        </button>
                    </div>
                </div>
                
                {{-- Description --}}
                <div class="prose prose-slate max-w-none mb-8">
                    <h2 class="text-lg font-bold text-slate-900 mb-3">📖 عن المنشأة</h2>
                    <div class="text-slate-600 leading-relaxed whitespace-pre-line">
                        {{ $business->description }}
                    </div>
                </div>
                
                {{-- Address & Contact --}}
                @if($business->address_detail)
                <div class="bg-slate-50 rounded-2xl p-4 mb-8">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-map-marker-alt text-emerald-600 mt-0.5"></i>
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm mb-1">العنوان الدقيق</h3>
                            <p class="text-slate-600 text-sm">{{ $business->address_detail }}</p>
                        </div>
                    </div>
                </div>
                @endif
                
                {{-- Price List --}}
                @if(!empty($business->price_list) && is_array($business->price_list) && count($business->price_list) > 0)
                <div class="mb-8">
                    <h2 class="text-lg font-bold text-slate-900 mb-4">💰 قائمة الأسعار والخدمات</h2>
                    <div class="bg-slate-50 rounded-2xl overflow-hidden">
                        <div class="divide-y divide-slate-200">
                            @foreach($business->price_list as $item)
                                @if(!empty($item['name']))
                                <div class="flex justify-between items-center p-4 hover:bg-white transition-colors">
                                    <span class="font-bold text-slate-800">{{ $item['name'] }}</span>
                                    <span class="font-black text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full text-sm">
                                        {{ $item['price'] ?? 'غير محدد' }} {{ $item['price'] ? 'ل.س' : '' }}
                                    </span>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
                
                {{-- Map --}}
                @if($business->latitude && $business->longitude)
                <div class="mb-8">
                    <h2 class="text-lg font-bold text-slate-900 mb-4">📍 الموقع على الخريطة</h2>
                    <div id="map-viewer"></div>
                    <div class="mt-3 text-center">
                        <a href="https://www.google.com/maps?q={{ $business->latitude }},{{ $business->longitude }}" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 text-sm font-bold transition-colors">
                            <i class="fas fa-external-link-alt"></i>
                            فتح في خرائط جوجل
                        </a>
                    </div>
                </div>
                @endif
                
                {{-- Contact Buttons --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-8 no-print">
                    @if($business->phone)
                    <a href="tel:{{ $business->phone }}" 
                       class="flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-4 rounded-xl transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-phone-alt"></i>
                        اتصل الآن
                    </a>
                    @endif
                    
                    @if($business->phone)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $business->phone) }}" 
                       target="_blank"
                       class="flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-xl transition-all duration-300 transform hover:scale-105">
                        <i class="fab fa-whatsapp"></i>
                        واتساب
                    </a>
                    @endif
                    
                    @if($business->facebook_url)
                    <a href="{{ $business->facebook_url }}" target="_blank" rel="noopener noreferrer"
                       class="flex items-center justify-center gap-2 bg-[#1877f2] hover:bg-[#0d65d9] text-white font-bold py-3 px-4 rounded-xl transition-all duration-300 transform hover:scale-105">
                        <i class="fab fa-facebook-f"></i>
                        فيسبوك
                    </a>
                    @endif
                    
                    @if($business->instagram_url)
                    <a href="{{ $business->instagram_url }}" target="_blank" rel="noopener noreferrer"
                       class="flex items-center justify-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-bold py-3 px-4 rounded-xl transition-all duration-300 transform hover:scale-105">
                        <i class="fab fa-instagram"></i>
                        انستغرام
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- ⭐ REVIEWS SECTION - قسم التقييمات المحسن --}}
    {{-- ============================================================ --}}
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-100">
            <div class="p-6 md:p-8">
                <div class="flex flex-wrap justify-between items-start gap-4 mb-6">
                    <div>
                        <h2 class="text-2xl font-black text-slate-900">💬 تقييمات الزوار</h2>
                        <p class="text-slate-500 text-sm mt-1">آراء حقيقية من عملاء المنشأة</p>
                    </div>
                    @if($business->reviews_count > 0)
                    <div class="text-center bg-emerald-50 px-4 py-2 rounded-2xl">
                        <div class="text-2xl font-black text-emerald-600">{{ number_format($business->rating_avg, 1) }}</div>
                        <div class="star-rating">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($business->rating_avg))
                                    <i class="fas fa-star text-amber-400 text-sm"></i>
                                @elseif($i - 0.5 <= $business->rating_avg)
                                    <i class="fas fa-star-half-alt text-amber-400 text-sm"></i>
                                @else
                                    <i class="far fa-star text-slate-300 text-sm"></i>
                                @endif
                            @endfor
                        </div>
                        <p class="text-xs text-slate-500 mt-1">{{ $business->reviews_count }} تقييم</p>
                    </div>
                    @endif
                </div>
                
                {{-- Reviews List --}}
                <div class="space-y-4 mb-8">
                    @forelse($business->reviews as $review)
                    <div class="review-card bg-slate-50 rounded-2xl p-5 transition-all">
                        <div class="flex flex-wrap justify-between items-start gap-3 mb-3">
                            <div>
                                <div class="font-bold text-slate-900">{{ $review->reviewer_name ?? 'زائر عابر' }}</div>
                                <div class="star-rating mt-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fas fa-star text-amber-400 text-xs"></i>
                                        @else
                                            <i class="far fa-star text-slate-300 text-xs"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            <span class="text-xs text-slate-400">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-slate-600 text-sm leading-relaxed">" {{ $review->comment }} "</p>
                    </div>
                    @empty
                    <div class="text-center py-12 text-slate-400 border-2 border-dashed border-slate-200 rounded-2xl">
                        <i class="fas fa-star-of-life text-4xl mb-3 block text-slate-300"></i>
                        <p class="font-medium">لا توجد مراجعات حالياً</p>
                        <p class="text-sm mt-1">كن أول من يقيّم هذه المنشأة</p>
                    </div>
                    @endforelse
                </div>
                
                {{-- Add Review Form --}}
                <div class="border-t border-slate-200 pt-6">
                    <h3 class="font-bold text-slate-900 mb-4">✍️ أضف تقييمك</h3>
                    
                    @if(session('success'))
                        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-xl text-sm mb-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <form action="{{ route('business.review.store', $business->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">الاسم الكريم *</label>
                                <input type="text" name="reviewer_name" required 
                                       placeholder="مثال: أبو عبد الرحمن" 
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-slate-50">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">التقييم بالنجوم *</label>
                                <select name="rating" required class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-slate-50">
                                    <option value="5">⭐⭐⭐⭐⭐ ممتاز (5/5)</option>
                                    <option value="4">⭐⭐⭐⭐ جيد جداً (4/5)</option>
                                    <option value="3">⭐⭐⭐ متوسط (3/5)</option>
                                    <option value="2">⭐⭐ مقبول (2/5)</option>
                                    <option value="1">⭐ ضعيف (1/5)</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">تفاصيل المراجعة *</label>
                            <textarea name="comment" rows="4" required 
                                      placeholder="شارك تجربتك مع المنشأة، جودة الخدمات، التعامل، وأي ملاحظات..." 
                                      class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-slate-50"></textarea>
                        </div>
                        <button type="submit" 
                                class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3 rounded-xl transition-all duration-300 transform hover:scale-105">
                            🚀 نشر التقييم
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- 🗺️ Leaflet Map Script --}}
    {{-- ============================================================ --}}
    @if($business->latitude && $business->longitude)
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lat = {{ $business->latitude }};
            const lng = {{ $business->longitude }};
            const title = "{{ addslashes($business->title) }}";
            
            const map = L.map('map-viewer').setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            L.marker([lat, lng]).addTo(map)
                .bindPopup(`<strong>${title}</strong>`)
                .openPopup();
        });
    </script>
    @endif

    {{-- ============================================================ --}}
    {{-- 🛠️ Share Functions --}}
    {{-- ============================================================ --}}
    <script>
        function copyLink() {
            navigator.clipboard.writeText(window.location.href);
            alert('✅ تم نسخ الرابط بنجاح!');
        }
        
        function shareOnWhatsApp() {
            window.open(`https://wa.me/?text=${encodeURIComponent(window.location.href)}`, '_blank');
        }
        
        function shareOnFacebook() {
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(window.location.href)}`, '_blank');
        }
    </script>

</body>
</html>