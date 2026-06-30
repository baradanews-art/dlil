@extends('layouts.admin')

@section('title', 'تعديل بيانات المنشأة التجاريّة')
@section('page_heading')
    ✏️ تعديل بيانات المنشأة: <span class="text-blue-600">{{ $business->title }}</span>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <style>
        #map-picker { height: 355px; width: 100%; border-radius: 12px; border: 1px solid #cbd5e1; z-index: 1; }
        .price-row { transition: all 0.25s ease; }
    </style>
@endpush

@section('content')
<div class="bg-white rounded-xl shadow-xs border border-slate-200 p-6 md:p-8">
    <form action="{{ route('admin.businesses.update', $business->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-slate-700">اسم الفعالية / المنشأة التجارية</label>
                <input type="text" name="title" value="{{ old('title', $business->title) }}" class="w-full border border-slate-300 text-sm p-2.5 rounded-lg focus:border-blue-600 focus:outline-none bg-slate-50 font-bold" required>
            </div>

            <div class="space-y-1.5">
                <label class="text-sm font-bold text-slate-700">التصنيف أو النشاط التجاري</label>
                <select name="category_id" class="w-full border border-slate-300 text-sm p-2.5 rounded-lg focus:border-blue-600 focus:outline-none bg-slate-50 font-semibold" required>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $business->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-1.5">
                <label class="text-sm font-bold text-slate-700">المحافظة *</label>
                <select name="governorate_id" id="governorate-select" class="w-full border border-slate-300 text-sm p-2.5 rounded-lg focus:border-blue-600 focus:outline-none bg-slate-50 font-semibold" required>
                    <option value="">اختر المحافظة</option>
                    @foreach($locations->where('parent_id', null) as $gov)
                        <option value="{{ $gov->id }}" {{ $business->governorate_id == $gov->id ? 'selected' : '' }}>{{ $gov->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-1.5">
                <label class="text-sm font-bold text-slate-700">المنطقة / المدينة *</label>
                <select name="region_id" id="region-select" class="w-full border border-slate-300 text-sm p-2.5 rounded-lg focus:border-blue-600 focus:outline-none bg-slate-200 font-semibold" {{ $business->governorate_id ? '' : 'disabled' }}>
                    <option value="">اختر المحافظة أولاً</option>
                    @if($business->governorate_id)
                        @foreach($locations->where('parent_id', $business->governorate_id) as $region)
                            <option value="{{ $region->id }}" {{ $business->region_id == $region->id ? 'selected' : '' }}>
                                {{ $region->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="space-y-1.5">
                <label class="text-sm font-bold text-slate-700">رقم الهاتف *</label>
                <input type="text" name="phone" value="{{ old('phone', $business->phone) }}" class="w-full border border-slate-300 text-sm p-2.5 rounded-lg focus:border-blue-600 focus:outline-none bg-slate-50 font-mono text-left" required>
            </div>
            
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-slate-700">العنوان التفصيلي</label>
                <input type="text" name="address_detail" value="{{ old('address_detail', $business->address_detail) }}" class="w-full border border-slate-300 text-sm p-2.5 rounded-lg focus:border-blue-600 focus:outline-none bg-slate-50" placeholder="الشارع، المبنى، الحي...">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-slate-700">🟢 حالة الظهور</label>
                <select name="status" class="w-full border border-slate-300 text-sm p-2.5 rounded-lg focus:border-blue-600 focus:outline-none bg-slate-50 font-bold text-emerald-700">
                    <option value="approved" {{ $business->is_approved ? 'selected' : '' }}>🟢 معتمد ومنشور</option>
                    <option value="pending" {{ !$business->is_approved ? 'selected' : '' }}>🟡 معلق للمراجعة</option>
                </select>
            </div>

            <div class="space-y-1.5">
                <label class="text-sm font-bold text-slate-700">🛡️ شارة التوثيق</label>
                <select name="verification_type" class="w-full border border-slate-300 text-sm p-2.5 rounded-lg focus:border-blue-600 focus:outline-none bg-slate-50 font-bold text-blue-700">
                    <option value="none" {{ $business->verification_type == 'none' ? 'selected' : '' }}>❌ غير موثق</option>
                    <option value="verified" {{ $business->verification_type == 'verified' ? 'selected' : '' }}>🛡️ موثق</option>
                    <option value="official" {{ $business->verification_type == 'official' ? 'selected' : '' }}>✨ رسمي معتمد</option>
                </select>
            </div>

            <div class="space-y-1.5">
                <label class="text-sm font-bold text-slate-700">🛵 التوصيل</label>
                <select name="delivery_available" class="w-full border border-slate-300 text-sm p-2.5 rounded-lg focus:border-blue-600 focus:outline-none bg-slate-50 font-semibold">
                    <option value="1" {{ $business->delivery_available ? 'selected' : '' }}>نعم، متوفرة</option>
                    <option value="0" {{ !$business->delivery_available ? 'selected' : '' }}>لا، غير متوفرة</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-slate-700">رابط فيسبوك</label>
                <input type="url" name="facebook_url" value="{{ old('facebook_url', $business->facebook_url) }}" placeholder="https://facebook.com/..." class="w-full border border-slate-300 text-sm p-2.5 rounded-lg focus:border-blue-600 focus:outline-none bg-slate-50 font-mono text-left" dir="ltr">
            </div>
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-slate-700">رابط انستغرام</label>
                <input type="url" name="instagram_url" value="{{ old('instagram_url', $business->instagram_url) }}" placeholder="https://instagram.com/..." class="w-full border border-slate-300 text-sm p-2.5 rounded-lg focus:border-blue-600 focus:outline-none bg-slate-50 font-mono text-left" dir="ltr">
            </div>
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-slate-700">واتساب</label>
                <input type="text" name="whatsapp" value="{{ old('whatsapp', $business->whatsapp) }}" placeholder="مثال: 963933123456" class="w-full border border-slate-300 text-sm p-2.5 rounded-lg focus:border-blue-600 focus:outline-none bg-slate-50 font-mono text-left" dir="ltr">
            </div>
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-slate-700">خرائط جوجل</label>
                <input type="url" name="google_maps_url" value="{{ old('google_maps_url', $business->google_maps_url ?? $business->Maps_url ?? '') }}" placeholder="https://maps.google.com/..." class="w-full border border-slate-300 text-sm p-2.5 rounded-lg focus:border-blue-600 focus:outline-none bg-slate-50 font-mono text-left" dir="ltr">
            </div>
        </div>

        <div class="space-y-1.5">
            <label class="text-sm font-bold text-slate-700">الوصف التفصيلي (للسيو)</label>
            <textarea name="description" rows="4" class="w-full border border-slate-300 text-sm p-3 rounded-lg focus:border-blue-600 focus:outline-none bg-slate-50 font-medium leading-relaxed" required>{{ old('description', $business->description) }}</textarea>
        </div>

        <div class="space-y-3 bg-slate-50 p-5 rounded-xl border border-slate-200">
            <div class="flex justify-between items-center border-b border-slate-200 pb-2">
                <h3 class="text-sm font-bold text-slate-900">📊 قائمة الأسعار</h3>
                <button type="button" id="add-price-row" class="text-xs bg-blue-600 text-white px-3 py-1.5 rounded-md hover:bg-blue-700 font-bold transition-all cursor-pointer">
                    + إضافة سطر
                </button>
            </div>
            
            <div id="price-list-container" class="space-y-3">
                @php $priceList = $business->price_list ?? []; @endphp
                @forelse($priceList as $index => $item)
                    <div class="flex gap-4 price-row items-center">
                        <input type="text" name="price_list[{{ $index }}][name]" value="{{ $item['name'] ?? '' }}" placeholder="اسم الخدمة" class="w-1/2 rounded-lg p-2 border border-slate-300 text-xs bg-white font-medium" required>
                        <input type="text" name="price_list[{{ $index }}][price]" value="{{ $item['price'] ?? '' }}" placeholder="السعر (ل.س)" class="w-1/3 rounded-lg p-2 border border-slate-300 text-xs bg-white font-mono text-left" dir="ltr">
                        <button type="button" class="px-3 py-2 bg-red-100 text-red-600 hover:bg-red-200 text-xs font-bold rounded-lg remove-row cursor-pointer">حذف</button>
                    </div>
                @empty
                    <div class="flex gap-4 price-row items-center">
                        <input type="text" name="price_list[0][name]" placeholder="اسم الخدمة" class="w-1/2 rounded-lg p-2 border border-slate-300 text-xs bg-white font-medium">
                        <input type="text" name="price_list[0][price]" placeholder="السعر (ل.س)" class="w-1/3 rounded-lg p-2 border border-slate-300 text-xs bg-white font-mono text-left" dir="ltr">
                        <button type="button" class="px-3 py-2 bg-red-100 text-red-600 hover:bg-red-200 text-xs font-bold rounded-lg remove-row cursor-pointer">حذف</button>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            <div class="lg:col-span-2 space-y-1.5">
                <label class="text-sm font-bold text-slate-700">📍 الموقع على الخريطة</label>
                <div id="map-picker"></div>
            </div>
            
            <div class="space-y-4 bg-slate-100 p-5 rounded-xl border border-slate-200 h-full justify-center flex flex-col">
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase">خط العرض (Latitude)</label>
                    <input type="text" id="lat-input" name="latitude" value="{{ old('latitude', $business->latitude ?? '33.5138') }}" class="w-full border border-slate-300 text-xs p-2.5 rounded-lg bg-white font-mono text-slate-600" readonly>
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase">خط الطول (Longitude)</label>
                    <input type="text" id="lng-input" name="longitude" value="{{ old('longitude', $business->longitude ?? '36.2765') }}" class="w-full border border-slate-300 text-xs p-2.5 rounded-lg bg-white font-mono text-slate-600" readonly>
                </div>
                <p class="text-[10px] text-slate-400 leading-normal">يتم تحديث الإحداثيات تلقائياً عند سحب الدبوس.</p>
            </div>
        </div>

        <div class="flex justify-end gap-4 border-t border-slate-200 pt-5">
            <a href="{{ route('admin.businesses.index') }}" class="px-5 py-2.5 bg-slate-100 text-slate-700 hover:bg-slate-200 text-sm font-bold rounded-lg transition-all">إلغاء</a>
            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white hover:bg-blue-700 text-sm font-bold rounded-lg transition-all shadow-md shadow-blue-500/10 cursor-pointer">
                💾 حفظ التغييرات
            </button>
        </div>
    </form>
</div>

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // الخريطة
            let initLat = parseFloat(document.getElementById('lat-input').value) || 33.5138;
            let initLng = parseFloat(document.getElementById('lng-input').value) || 36.2765;

            const map = L.map('map-picker').setView([initLat, initLng], 14);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            let marker = L.marker([initLat, initLng], { draggable: true }).addTo(map);

            function updateLocationInputs(lat, lng) {
                document.getElementById('lat-input').value = lat;
                document.getElementById('lng-input').value = lng;
            }

            marker.on('dragend', function (event) {
                const position = marker.getLatLng();
                updateLocationInputs(position.lat.toFixed(6), position.lng.toFixed(6));
            });

            map.on('click', function (event) {
                marker.setLatLng([event.latlng.lat, event.latlng.lng]);
                updateLocationInputs(event.latlng.lat.toFixed(6), event.latlng.lng.toFixed(6));
            });

            // المحافظة والمنطقة
            const govSelect = document.getElementById('governorate-select');
            const regionSelect = document.getElementById('region-select');

            function loadRegions(governorateId, selectedRegionId = null) {
                if (!governorateId) {
                    regionSelect.innerHTML = '<option value="">اختر المحافظة أولاً</option>';
                    regionSelect.disabled = true;
                    return;
                }

                regionSelect.innerHTML = '<option value="">⏳ جاري التحميل...</option>';
                regionSelect.disabled = true;

                fetch(`/get-regions/${governorateId}`)
                    .then(response => response.json())
                    .then(data => {
                        regionSelect.innerHTML = '<option value="">اختر المنطقة</option>';
                        if (data && data.length > 0) {
                            data.forEach(region => {
                                const option = document.createElement('option');
                                option.value = region.id;
                                option.textContent = region.name;
                                if (selectedRegionId && selectedRegionId == region.id) {
                                    option.selected = true;
                                }
                                regionSelect.appendChild(option);
                            });
                            regionSelect.disabled = false;
                        } else {
                            regionSelect.innerHTML = '<option value="">⚠️ لا توجد مناطق مسجلة</option>';
                            regionSelect.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error loading regions:', error);
                        regionSelect.innerHTML = '<option value="">❌ حدث خطأ</option>';
                        regionSelect.disabled = false;
                    });
            }

            govSelect.addEventListener('change', function() {
                loadRegions(this.value);
            });

            // تحميل المناطق عند تحميل الصفحة إذا كانت المحافظة محددة
            if (govSelect.value) {
                loadRegions(govSelect.value, {{ $business->region_id ?? 'null' }});
            }

            // قائمة الأسعار
            let priceIndex = {{ is_array($priceList) ? count($priceList) : 1 }};
            const container = document.getElementById('price-list-container');
            const addBtn = document.getElementById('add-price-row');

            if (addBtn) {
                addBtn.addEventListener('click', function() {
                    const row = document.createElement('div');
                    row.className = 'flex gap-4 price-row items-center';
                    row.innerHTML = `
                        <input type="text" name="price_list[${priceIndex}][name]" placeholder="اسم الخدمة" class="w-1/2 rounded-lg p-2 border border-slate-300 text-xs bg-white font-medium">
                        <input type="text" name="price_list[${priceIndex}][price]" placeholder="السعر (ل.س)" class="w-1/3 rounded-lg p-2 border border-slate-300 text-xs bg-white font-mono text-left" dir="ltr">
                        <button type="button" class="px-3 py-2 bg-red-100 text-red-600 hover:bg-red-200 text-xs font-bold rounded-lg remove-row cursor-pointer">حذف</button>
                    `;
                    container.appendChild(row);
                    priceIndex++;
                });
            }

            container.addEventListener('click', function(e) {
                if (e.target && e.target.closest('.remove-row')) {
                    const rows = container.querySelectorAll('.price-row');
                    if (rows.length > 1) {
                        e.target.closest('.price-row').remove();
                    } else {
                        alert('⚠️ يجب الإبقاء على سطر واحد على الأقل!');
                    }
                }
            });
        });
    </script>
@endpush