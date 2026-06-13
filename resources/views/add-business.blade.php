<!DOCTYPE html>
<html lang="ar" dir="rtl" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>أضف نشاطك التجاري - دليل سوريا التجاري</title>
    <meta name="description" content="أضف منشأتك التجارية مجاناً في دليل سوريا التجاري">
    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        #map-picker { height: 350px; width: 100%; border-radius: 12px; border: 1px solid #e2e8f0; z-index: 1; }
    </style>
</head>
<body class="flex flex-col min-h-screen text-slate-800 font-sans antialiased">

    <header class="bg-slate-900 text-white py-4 px-6 shadow-md flex justify-between items-center">
        <h2 class="text-sm font-black tracking-tight">📍 دليل سوريا التجاري</h2>
        <a href="{{ route('home') }}" class="text-xs bg-slate-800 hover:bg-slate-700 text-slate-200 px-4 py-2 rounded-xl transition-colors font-bold">⬅️ العودة للرئيسية</a>
    </header>

    <main class="flex-grow max-w-3xl w-full mx-auto px-4 py-12">
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
            
            <div class="text-center mb-8">
                <h1 class="text-xl font-black text-slate-900">📢 أضف منشأتك أو محلك التجاري مجاناً</h1>
                <p class="text-xs text-slate-500 font-medium mt-1">املأ البيانات التالية بدقة لتظهر منشأتك لآلاف الزوار بعد مراجعة الإدارة</p>
            </div>

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-xl text-xs font-bold mb-6">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl text-xs font-medium mb-6 space-y-1">
                    <p class="font-bold">⚠️ يرجى تصحيح الأخطاء التالية:</p>
                    <ul class="list-disc list-inside text-[11px] space-y-0.5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form action="{{ route('business.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><label class="block text-xs font-bold text-slate-700 mb-1.5">اسم المنشأة التجارية *</label><input type="text" name="title" value="{{ old('title') }}" required placeholder="مثال: سوبرماركت الخير، مطعم الياسمين..." class="w-full text-xs p-3 border border-slate-200 rounded-xl bg-slate-50 focus:outline-emerald-500 font-medium"></div>
                    <div><label class="block text-xs font-bold text-slate-700 mb-1.5">رقم هاتف التواصل *</label><input type="text" name="phone" value="{{ old('phone') }}" required placeholder="مثال: 0912345678 أو 011xxxxxx" class="w-full text-xs p-3 border border-slate-200 rounded-xl bg-slate-50 focus:outline-emerald-500 font-mono font-medium"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div><label class="block text-xs font-bold text-slate-700 mb-1.5">التصنيف التجاري *</label><select name="category_id" required class="w-full text-xs p-3 border border-slate-200 rounded-xl bg-slate-50 focus:outline-emerald-500 font-bold"><option value="">اختر التصنيف...</option>@foreach($categories as $category)<option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->icon ?? '📁' }} {{ $category->name }}</option>@endforeach</select></div>
                    <div><label class="block text-xs font-bold text-slate-700 mb-1.5">المحافظة الرئيسية *</label><select id="governorate-select" required class="w-full text-xs p-3 border border-slate-200 rounded-xl bg-slate-50 focus:outline-emerald-500 font-bold"><option value="">اختر المحافظة...</option>@foreach($governorates as $gov)<option value="{{ $gov->id }}">{{ $gov->name }}</option>@endforeach</select></div>
                    <div><label class="block text-xs font-bold text-slate-700 mb-1.5">المنطقة / المدينة الفرعية *</label><select id="region-select" name="location_id" required class="w-full text-xs p-3 border border-slate-200 rounded-xl bg-slate-200 cursor-not-allowed font-bold" disabled><option value="">اختر المحافظة أولاً...</option></select></div>
                </div>

                <div class="space-y-4">
                    <div><label class="block text-xs font-bold text-slate-700 mb-1.5">العنوان بالتفصيل (اختياري)</label><input type="text" name="address_detail" value="{{ old('address_detail') }}" placeholder="مثال: قدسيا، الشارع العام، بجانب صيدلية..." class="w-full text-xs p-3 border border-slate-200 rounded-xl bg-slate-50 focus:outline-emerald-500 font-medium"></div>
                    <div><label class="block text-xs font-bold text-slate-700 mb-1.5">وصف المنشأة والخدمات *</label><textarea name="description" rows="4" required placeholder="اكتب نبذة عن منشأتك، مواعيد العمل، وأهم الخدمات أو المنتجات التي تقدمها..." class="w-full text-xs p-3 border border-slate-200 rounded-xl bg-slate-50 focus:outline-emerald-500 font-medium">{{ old('description') }}</textarea></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><label class="block text-xs font-bold text-slate-700 mb-1">لوجو / شعار المحل</label><p class="text-[10px] text-slate-400 mb-1.5">يفضل قياس مربع (أقصى حجم 2 ميجا)</p><input type="file" name="logo" class="w-full text-xs p-2 border border-slate-200 rounded-xl bg-slate-50 focus:outline-emerald-500"></div>
                    <div><label class="block text-xs font-bold text-slate-700 mb-1">صورة غلاف المنشأة</label><p class="text-[10px] text-slate-400 mb-1.5">تظهر في أعلى صفحة العرض (أقصى حجم 3 ميجا)</p><input type="file" name="cover" class="w-full text-xs p-2 border border-slate-200 rounded-xl bg-slate-50 focus:outline-emerald-500"></div>
                </div>

                <div class="flex items-center gap-2 bg-slate-50 p-4 rounded-xl border border-slate-100">
                    <input type="checkbox" id="delivery_available" name="delivery_available" value="1" {{ old('delivery_available') ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 border-slate-300 rounded-sm focus:ring-emerald-500">
                    <label for="delivery_available" class="text-xs font-bold text-slate-700 select-none cursor-pointer">🛵 تتوفر لدينا خدمة التوصيل (Delivery)</label>
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-700">📍 حدد موقع منشأتك الجغرافي على الخريطة</label>
                    <p class="text-[10px] text-slate-400">حرك الدبوس أو اضغط على موقع محلك لجلبه بدقة على الخريطة</p>
                    <div id="map-picker"></div>
                    <input type="hidden" name="latitude" id="lat-input" value="{{ old('latitude', '33.5138') }}">
                    <input type="hidden" name="longitude" id="lng-input" value="{{ old('longitude', '36.2765') }}">
                </div>

                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs py-3.5 rounded-xl shadow-xs transition-all cursor-pointer">🚀 إرسال المنشأة للمراجعة والنشر الآن</button>
            </form>
        </div>
    </main>

    <footer class="bg-slate-900 text-slate-400 text-xs py-6 text-center border-t border-slate-800"><p>© {{ date('Y') }} دليل سوريا التجاري. جميع الحقوق محفوظة.</p></footer>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const govSelect = document.getElementById('governorate-select');
            const regionSelect = document.getElementById('region-select');

            function fetchRegions(governorateId) {
                regionSelect.innerHTML = '<option value="">⏳ جاري تحميل المناطق...</option>';
                regionSelect.disabled = true;
                if (!governorateId) { regionSelect.innerHTML = '<option value="">❌ اختر المحافظة أولاً</option>'; return; }
                
                const apiUrl = `https://aza-international.com/dlil/get-regions/${governorateId}`;
                
                fetch(apiUrl, { headers: { 'Accept': 'application/json' } })
                    .then(response => response.json())
                    .then(data => {
                        regionSelect.innerHTML = '<option value="">🏙️ اختر المنطقة الفرعية...</option>';
                        if (data.length === 0) { regionSelect.innerHTML = '<option value="">⚠️ لا توجد مناطق تابعة</option>'; regionSelect.disabled = true; }
                        else { data.forEach(region => { const option = document.createElement('option'); option.value = region.id; option.textContent = region.name; regionSelect.appendChild(option); }); regionSelect.disabled = false; }
                    })
                    .catch(error => { console.error(error); regionSelect.innerHTML = '<option value="">❌ خطأ في التحميل</option>'; regionSelect.disabled = true; });
            }

            govSelect.addEventListener('change', function () { fetchRegions(this.value); });

            let defaultLat = parseFloat(document.getElementById('lat-input').value);
            let defaultLng = parseFloat(document.getElementById('lng-input').value);
            let map = L.map('map-picker').setView([defaultLat, defaultLng], 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);
            let marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);
            marker.on('dragend', function (e) { let pos = marker.getLatLng(); document.getElementById('lat-input').value = pos.lat.toFixed(6); document.getElementById('lng-input').value = pos.lng.toFixed(6); });
            map.on('click', function (e) { marker.setLatLng(e.latlng); document.getElementById('lat-input').value = e.latlng.lat.toFixed(6); document.getElementById('lng-input').value = e.latlng.lng.toFixed(6); });
        });
    </script>
</body>
</html>