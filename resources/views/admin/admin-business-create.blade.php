@extends('layouts.admin')

@section('title', 'إضافة منشأة جديدة')
@section('page_heading', '➕ إضافة منشأة تجارية جديدة')
@section('page_subheading', 'إضافة منشأة جديدة من لوحة التحكم')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map-picker { height: 350px; width: 100%; border-radius: 16px; z-index: 1; }
    </style>
@endpush

@section('content')
<div class="card">
    <div class="p-6">
        <form action="{{ route('admin.businesses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            {{-- Status & Verification --}}
            <div class="bg-slate-50 rounded-xl p-5">
                <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-shield-alt text-emerald-600"></i>
                    حالة المنشأة والتحقق
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="label">حالة الظهور</label>
                        <select name="status" class="input">
                            <option value="approved">✅ معتمدة ومنشورة</option>
                            <option value="pending" selected>⏳ معلقة بانتظار المراجعة</option>
                        </select>
                    </div>
                    <div>
                        <label class="label">شارة التوثيق</label>
                        <select name="verification_type" class="input">
                            <option value="none" selected>⚪ غير موثق</option>
                            <option value="verified">🔵 موثق (Verified)</option>
                            <option value="official">👑 رسمي (Official)</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-3 pt-6">
                        <input type="checkbox" id="delivery_available" name="delivery_available" value="1" class="w-5 h-5 text-emerald-600 rounded">
                        <label for="delivery_available" class="text-sm font-bold text-slate-700 cursor-pointer">
                            <i class="fas fa-motorcycle ml-1"></i> تتوفر خدمة التوصيل
                        </label>
                    </div>
                </div>
            </div>
            
            {{-- Basic Info --}}
            <div class="bg-slate-50 rounded-xl p-5">
                <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-info-circle text-blue-600"></i>
                    المعلومات الأساسية
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="label">اسم المنشأة *</label>
                        <input type="text" name="title" value="{{ old('title') }}" required class="input">
                    </div>
                    <div>
                        <label class="label">رقم الهاتف *</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" required class="input" dir="ltr">
                    </div>
                    <div>
                        <label class="label">التصنيف *</label>
                        <select name="category_id" class="input" required>
                            <option value="">اختر التصنيف</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->icon ?? '📁' }} {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="label">المحافظة *</label>
                        <select name="governorate_id" id="governorate-select" class="input" required>
                            <option value="">اختر المحافظة</option>
                            @foreach($governorates as $gov)
                                <option value="{{ $gov->id }}" {{ old('governorate_id') == $gov->id ? 'selected' : '' }}>
                                    {{ $gov->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="label">المنطقة *</label>
                        <select name="region_id" id="region-select" class="input" disabled required>
                            <option value="">اختر المحافظة أولاً</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="label">العنوان بالتفصيل</label>
                        <input type="text" name="address_detail" value="{{ old('address_detail') }}" class="input">
                    </div>
                    <div class="md:col-span-2">
                        <label class="label">وصف المنشأة *</label>
                        <textarea name="description" rows="5" class="input" required>{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
            
            {{-- Social Links --}}
            <div class="bg-slate-50 rounded-xl p-5">
                <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-share-alt text-sky-600"></i>
                    روابط التواصل الاجتماعي
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="label">فيسبوك</label>
                        <input type="url" name="facebook_url" value="{{ old('facebook_url') }}" placeholder="https://facebook.com/..." class="input" dir="ltr">
                    </div>
                    <div>
                        <label class="label">انستغرام</label>
                        <input type="url" name="instagram_url" value="{{ old('instagram_url') }}" placeholder="https://instagram.com/..." class="input" dir="ltr">
                    </div>
                    <div>
                        <label class="label">خرائط جوجل</label>
                        <input type="url" name="google_maps_url" value="{{ old('google_maps_url') }}" placeholder="https://maps.google.com/..." class="input" dir="ltr">
                    </div>
                </div>
            </div>
            
            {{-- Images --}}
            <div class="bg-slate-50 rounded-xl p-5">
                <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-image text-purple-600"></i>
                    الصور
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="label">اللوجو</label>
                        <input type="file" name="logo" accept="image/*" class="input !p-2">
                        <p class="text-[10px] text-slate-400 mt-1">JPG, PNG (max 2MB)</p>
                    </div>
                    <div>
                        <label class="label">صورة الغلاف</label>
                        <input type="file" name="cover" accept="image/*" class="input !p-2">
                        <p class="text-[10px] text-slate-400 mt-1">JPG, PNG (max 3MB)</p>
                    </div>
                </div>
            </div>
            
            {{-- Map --}}
            <div class="bg-slate-50 rounded-xl p-5">
                <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-map text-rose-600"></i>
                    الموقع الجغرافي
                </h3>
                <div id="map-picker"></div>
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="label">خط العرض (Latitude)</label>
                        <input type="text" name="latitude" id="lat-input" readonly value="{{ old('latitude', '33.5138') }}" class="input bg-slate-100 font-mono">
                    </div>
                    <div>
                        <label class="label">خط الطول (Longitude)</label>
                        <input type="text" name="longitude" id="lng-input" readonly value="{{ old('longitude', '36.2765') }}" class="input bg-slate-100 font-mono">
                    </div>
                </div>
            </div>
            
            {{-- Submit Buttons --}}
            <div class="flex gap-4">
                <button type="submit" class="btn-primary flex-1 py-3">
                    <i class="fas fa-save ml-2"></i> إضافة المنشأة
                </button>
                <a href="{{ route('admin.businesses.index') }}" class="btn-secondary flex-1 py-3 text-center">
                    <i class="fas fa-times ml-2"></i> إلغاء
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ========== المحافظة والمنطقة ==========
        const govSelect = document.getElementById('governorate-select');
        const regionSelect = document.getElementById('region-select');

        if (!govSelect || !regionSelect) {
            console.error('❌ عناصر المحافظة أو المنطقة غير موجودة!');
            return;
        }

        function loadRegions(governorateId, selectedRegionId = null) {
            if (!governorateId) {
                regionSelect.innerHTML = '<option value="">اختر المحافظة أولاً</option>';
                regionSelect.disabled = true;
                return;
            }

            regionSelect.innerHTML = '<option value="">⏳ جاري التحميل...</option>';
            regionSelect.disabled = true;

            const url = `/dlil/get-regions/${governorateId}`;

            fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                regionSelect.innerHTML = '<option value="">اختر المنطقة</option>';
                
                if (data && Array.isArray(data) && data.length > 0) {
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
                console.error('❌ خطأ في تحميل المناطق:', error);
                regionSelect.innerHTML = '<option value="">❌ حدث خطأ</option>';
                regionSelect.disabled = false;
            });
        }

        govSelect.addEventListener('change', function() {
            const governorateId = this.value;
            loadRegions(governorateId);
        });

        // ========== الخريطة ==========
        let latInput = document.getElementById('lat-input');
        let lngInput = document.getElementById('lng-input');
        let defaultLat = parseFloat(latInput.value);
        let defaultLng = parseFloat(lngInput.value);
        
        let map = L.map('map-picker').setView([defaultLat, defaultLng], 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { 
            attribution: '© OpenStreetMap' 
        }).addTo(map);
        
        let marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);
        
        function updateCoords(lat, lng) {
            latInput.value = parseFloat(lat).toFixed(6);
            lngInput.value = parseFloat(lng).toFixed(6);
        }
        
        marker.on('dragend', (e) => { let c = marker.getLatLng(); updateCoords(c.lat, c.lng); });
        map.on('click', (e) => { marker.setLatLng(e.latlng); updateCoords(e.latlng.lat, e.latlng.lng); });
    });
</script>
@endpush
@endsection