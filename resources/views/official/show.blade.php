<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $entity->name }} | دليل سوريا التجاري</title>
    <meta name="description" content="{{ Str::limit($entity->description ?? '', 160) }}">
    <meta name="robots" content="index, follow">
    
    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $entity->name }}">
    <meta property="og:description" content="{{ Str::limit($entity->description ?? '', 160) }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    @if($entity->logo)
    <meta property="og:image" content="{{ $entity->logo_url }}">
    @endif
    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        .social-btn { transition: all 0.3s ease; }
        .social-btn:hover { transform: translateY(-3px); }
        .info-card { transition: all 0.3s ease; }
        .info-card:hover { transform: translateY(-2px); box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.1); }
        .map-container { border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); }
    </style>
</head>
<body class="bg-slate-50 font-sans antialiased">

    {{-- Header --}}
    <div class="bg-gradient-to-r {{ $bgColor ?? 'from-green-700 to-green-600' }} text-white py-12 px-4 text-center">
        <div class="max-w-4xl mx-auto">
            <div class="text-6xl mb-4">
                <i class="fas {{ $icon ?? 'fa-landmark' }}"></i>
            </div>
            <h1 class="text-3xl md:text-4xl font-black">{{ $entity->name }}</h1>
            @if($entity->sub_type)
                <p class="text-white/80 mt-2">{{ $entity->sub_type_label }}</p>
            @endif
            <div class="mt-4 flex flex-wrap gap-3 justify-center">
                <a href="{{ route('home') }}" class="inline-block bg-white/20 hover:bg-white/30 rounded-xl px-5 py-1.5 text-sm transition-all">
                    <i class="fas fa-home ml-2"></i> الرئيسية
                </a>
                <a href="{{ url()->previous() }}" class="inline-block bg-white/20 hover:bg-white/30 rounded-xl px-5 py-1.5 text-sm transition-all">
                    <i class="fas fa-arrow-right ml-2"></i> العودة
                </a>
                @if($entity->type == 'security')
                <a href="{{ route('official.security') }}" class="inline-block bg-white/20 hover:bg-white/30 rounded-xl px-5 py-1.5 text-sm transition-all">
                    <i class="fas fa-shield-alt ml-2"></i> جميع مراكز الأمن
                </a>
                @elseif($entity->type == 'government')
                <a href="{{ route('official.government') }}" class="inline-block bg-white/20 hover:bg-white/30 rounded-xl px-5 py-1.5 text-sm transition-all">
                    <i class="fas fa-landmark ml-2"></i> جميع المؤسسات الحكومية
                </a>
                @else
                <a href="{{ route('official.help') }}" class="inline-block bg-white/20 hover:bg-white/30 rounded-xl px-5 py-1.5 text-sm transition-all">
                    <i class="fas fa-hand-holding-heart ml-2"></i> جميع مراكز المساعدة
                </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="max-w-4xl mx-auto px-4 py-12">
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
            
            {{-- Logo --}}
            @if($entity->logo)
            <div class="flex justify-center -mt-10 mb-6">
                <div class="bg-white rounded-2xl p-2 shadow-lg">
                    <img src="{{ $entity->logo_url }}" alt="{{ $entity->name }}" class="w-24 h-24 rounded-xl object-cover">
                </div>
            </div>
            @endif
            
            <div class="p-6 md:p-8">
                
                {{-- Description --}}
                @if($entity->description)
                <div class="mb-8">
                    <h2 class="text-lg font-bold text-slate-900 mb-3 flex items-center gap-2">
                        <i class="fas fa-info-circle text-emerald-600"></i>
                        نبذة عن المؤسسة
                    </h2>
                    <div class="text-slate-600 leading-relaxed whitespace-pre-line">
                        {{ $entity->description }}
                    </div>
                </div>
                @endif
                
                {{-- Contact Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                    @if($entity->phone)
                    <div class="info-card flex items-center gap-3 p-3 bg-slate-50 rounded-xl hover:bg-emerald-50 transition-all">
                        <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-phone-alt text-emerald-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">رقم الهاتف</p>
                            <a href="tel:{{ $entity->phone }}" class="text-slate-800 font-medium hover:text-emerald-600" dir="ltr">{{ $entity->phone }}</a>
                        </div>
                    </div>
                    @endif
                    
                    @if($entity->hotline)
                    <div class="info-card flex items-center gap-3 p-3 bg-red-50 rounded-xl hover:bg-red-100 transition-all">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-phone-alt text-red-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">رقم الطوارئ</p>
                            <a href="tel:{{ $entity->hotline }}" class="text-slate-800 font-medium hover:text-red-600" dir="ltr">{{ $entity->hotline }}</a>
                        </div>
                    </div>
                    @endif
                    
                    @if($entity->email)
                    <div class="info-card flex items-center gap-3 p-3 bg-slate-50 rounded-xl hover:bg-emerald-50 transition-all">
                        <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-envelope text-emerald-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">البريد الإلكتروني</p>
                            <a href="mailto:{{ $entity->email }}" class="text-slate-800 font-medium hover:text-emerald-600">{{ $entity->email }}</a>
                        </div>
                    </div>
                    @endif
                    
                    @if($entity->website)
                    <div class="info-card flex items-center gap-3 p-3 bg-slate-50 rounded-xl hover:bg-emerald-50 transition-all">
                        <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-globe text-emerald-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">الموقع الإلكتروني</p>
                            <a href="{{ $entity->website }}" target="_blank" rel="noopener noreferrer" class="text-slate-800 font-medium hover:text-emerald-600">
                                {{ parse_url($entity->website, PHP_URL_HOST) ?? $entity->website }}
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
                
                {{-- Address --}}
                @if($entity->address || $entity->city || $entity->region)
                <div class="info-card bg-slate-50 rounded-2xl p-5 mb-8 hover:bg-emerald-50 transition-all">
                    <h3 class="text-md font-bold text-slate-900 mb-3 flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-emerald-600"></i>
                        العنوان
                    </h3>
                    @if($entity->address)
                        <p class="text-slate-600 mb-2">{{ $entity->address }}</p>
                    @endif
                    @if($entity->region || $entity->city)
                        <p class="text-slate-500 text-sm">
                            @if($entity->region) {{ $entity->region->name }}، @endif
                            @if($entity->city) {{ $entity->city->name }} @endif
                        </p>
                    @endif
                </div>
                @endif
                
                {{-- Working Hours --}}
                @if($entity->working_hours)
                <div class="info-card bg-slate-50 rounded-2xl p-5 mb-8 hover:bg-emerald-50 transition-all">
                    <h3 class="text-md font-bold text-slate-900 mb-3 flex items-center gap-2">
                        <i class="fas fa-clock text-emerald-600"></i>
                        ساعات العمل
                    </h3>
                    <p class="text-slate-600">{{ $entity->working_hours }}</p>
                </div>
                @endif
                
                {{-- Social Media --}}
                @if($entity->facebook_url || $entity->twitter_url || $entity->instagram_url || $entity->youtube_url || $entity->linkedin_url)
                <div class="mb-8">
                    <h3 class="text-md font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-share-alt text-emerald-600"></i>
                        وسائل التواصل الاجتماعي
                    </h3>
                    <div class="flex flex-wrap gap-3">
                        @if($entity->facebook_url)
                        <a href="{{ $entity->facebook_url }}" target="_blank" class="social-btn bg-[#1877f2] hover:bg-[#0d65d9] text-white px-4 py-2 rounded-xl text-sm font-bold inline-flex items-center gap-2">
                            <i class="fab fa-facebook-f"></i> فيسبوك
                        </a>
                        @endif
                        @if($entity->twitter_url)
                        <a href="{{ $entity->twitter_url }}" target="_blank" class="social-btn bg-[#1da1f2] hover:bg-[#0d8bdb] text-white px-4 py-2 rounded-xl text-sm font-bold inline-flex items-center gap-2">
                            <i class="fab fa-twitter"></i> تويتر
                        </a>
                        @endif
                        @if($entity->instagram_url)
                        <a href="{{ $entity->instagram_url }}" target="_blank" class="social-btn bg-gradient-to-r from-purple-600 to-pink-600 text-white px-4 py-2 rounded-xl text-sm font-bold inline-flex items-center gap-2">
                            <i class="fab fa-instagram"></i> انستغرام
                        </a>
                        @endif
                        @if($entity->youtube_url)
                        <a href="{{ $entity->youtube_url }}" target="_blank" class="social-btn bg-[#ff0000] hover:bg-[#cc0000] text-white px-4 py-2 rounded-xl text-sm font-bold inline-flex items-center gap-2">
                            <i class="fab fa-youtube"></i> يوتيوب
                        </a>
                        @endif
                        @if($entity->linkedin_url)
                        <a href="{{ $entity->linkedin_url }}" target="_blank" class="social-btn bg-[#0077b5] hover:bg-[#005e8c] text-white px-4 py-2 rounded-xl text-sm font-bold inline-flex items-center gap-2">
                            <i class="fab fa-linkedin-in"></i> لينكد إن
                        </a>
                        @endif
                    </div>
                </div>
                @endif
                
                {{-- Map --}}
                @if($entity->latitude && $entity->longitude)
                <div class="mb-8">
                    <h3 class="text-md font-bold text-slate-900 mb-3 flex items-center gap-2">
                        <i class="fas fa-map text-emerald-600"></i>
                        الموقع على الخريطة
                    </h3>
                    <div class="map-container">
                        <iframe width="100%" height="300" frameborder="0" style="border:0; display: block;"
                            src="https://www.google.com/maps?q={{ $entity->latitude }},{{ $entity->longitude }}&hl=ar&z=15&output=embed"
                            allowfullscreen>
                        </iframe>
                    </div>
                    <div class="mt-3 text-center">
                        <a href="https://www.google.com/maps?q={{ $entity->latitude }},{{ $entity->longitude }}" 
                           target="_blank" class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 text-sm font-bold">
                            <i class="fas fa-external-link-alt"></i> فتح في خرائط جوجل
                        </a>
                    </div>
                </div>
                @endif
                
                {{-- Action Buttons --}}
                <div class="flex flex-wrap gap-4">
                    @if($entity->phone)
                    <a href="tel:{{ $entity->phone }}" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white text-center font-bold py-3 rounded-xl transition-all transform hover:scale-105">
                        <i class="fas fa-phone-alt ml-2"></i> اتصل الآن
                    </a>
                    @endif
                    @if($entity->hotline)
                    <a href="tel:{{ $entity->hotline }}" class="flex-1 bg-red-600 hover:bg-red-700 text-white text-center font-bold py-3 rounded-xl transition-all transform hover:scale-105">
                        <i class="fas fa-phone-alt ml-2"></i> طوارئ
                    </a>
                    @endif
                </div>
                
                {{-- Share Section --}}
                <div class="mt-8 pt-6 border-t border-slate-200 text-center">
                    <p class="text-xs text-slate-400 mb-3">شارك هذه الصفحة</p>
                    <div class="flex justify-center gap-2">
                        <button onclick="shareOnWhatsApp()" class="bg-green-500 hover:bg-green-600 text-white p-2 rounded-full transition-all w-9 h-9 flex items-center justify-center">
                            <i class="fab fa-whatsapp"></i>
                        </button>
                        <button onclick="shareOnFacebook()" class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-full transition-all w-9 h-9 flex items-center justify-center">
                            <i class="fab fa-facebook-f"></i>
                        </button>
                        <button onclick="shareOnTwitter()" class="bg-sky-500 hover:bg-sky-600 text-white p-2 rounded-full transition-all w-9 h-9 flex items-center justify-center">
                            <i class="fab fa-twitter"></i>
                        </button>
                        <button onclick="copyLink()" class="bg-slate-600 hover:bg-slate-700 text-white p-2 rounded-full transition-all w-9 h-9 flex items-center justify-center">
                            <i class="fas fa-link"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-slate-900 text-slate-400 py-8 text-center text-sm">
        <p>© {{ date('Y') }} دليل سوريا التجاري - {{ $entity->name }}</p>
    </footer>

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
        function shareOnTwitter() {
            window.open(`https://twitter.com/intent/tweet?url=${encodeURIComponent(window.location.href)}&text=${encodeURIComponent('{{ $entity->name }}')}`, '_blank');
        }
    </script>
</body>
</html>