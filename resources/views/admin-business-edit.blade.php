@extends('layouts.admin')

@section('title', 'تعديل منشأة: ' . $business->title)
@section('page_heading', '✏️ تعديل المنشأة')
@section('page_subheading', 'تحديث بيانات: ' . $business->title)

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map-picker { height: 350px; width: 100%; border-radius: 16px; z-index: 1; }
        .price-row { transition: all 0.2s ease; }
        .price-row:hover { background-color: #f8fafc; }
    </style>
@endpush

@section('content')
<div class="card">
    <div class="p-6">
        <form action="{{ route('admin.businesses.update', $business->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
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
                            <option value="approved" {{ $business->is_approved ? 'selected' : '' }}>✅ معتمدة ومنشورة</option>
                            <option value="pending" {{ !$business->is_approved ? 'selected' : '' }}>⏳ معلقة بانتظار المراجعة</option>
                        </select>
                    </div>
                    <div>
                        <label class="label">شارة التوثيق</label>
                        <select name="verification_type" class="input">
                            <option value="none" {{ $business->verification_type == 'none' ? 'selected' : '' }}>⚪ غير موثق</option>
                            <option value="verified" {{ $business->verification_type == 'verified' ? 'selected' : '' }}>🔵 موثق (Verified)</option>
                            <option value="official" {{ $business->verification_type == 'official' ? 'selected' : '' }}>👑 رسمي (Official)</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-3 pt-6">
                        <input type="checkbox" id="delivery_available" name="delivery_available" value="1" {{ $business->delivery_available ? 'checked' : '' }} class="w-5 h-5 text-emerald-600 rounded">
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
                        <input type="text" name="title" value="{{ old('title', $business->title) }}" required class="input">
                    </div>
                    <div>
                        <label class="label">رقم الهاتف *</label>
                        <input type="text" name="phone" value="{{ old('phone', $business->phone) }}" required class="input" dir="ltr">
                    </div>
                    <div>
                        <label class="label">التصنيف *</label>
                        <select name="category_id" class="input">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $business->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->icon ?? '📁' }} {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="label">الموقع *</label>
                        <select name="location_id" class="input">
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}" {{ $business->location_id == $loc->id ? 'selected' : '' }}>
                                    {{ $loc->parent ? $loc->parent->name . ' - ' : '' }}{{ $loc->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="label">العنوان بالتفصيل</label>
                        <input type="text" name="address_detail" value="{{ old('address_detail', $business->address_detail) }}" class="input">
                    </div>
                    <div class="md:col-span-2">
                        <label class="label">وصف المنشأة *</label>
                        <textarea name="description" rows="5" class="input">{{ old('description', $business->description) }}</textarea>
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
                        <input type="url" name="facebook_url" value="{{ old('facebook_url', $business->facebook_url) }}" placeholder="https://facebook.com/..." class="input" dir="ltr">
                    </div>
                    <div>
                        <label class="label">انستغرام</label>
                        <input type="url" name="instagram_url" value="{{ old('instagram_url', $business->instagram_url) }}" placeholder="https://instagram.com/..." class="input" dir="ltr">
                    </div>
                    <div>
                        <label class="label">خرائط جوجل</label>
                        <input type="url" name="google_maps_url" value="{{ old('google_maps_url', $business->Maps_url) }}" placeholder="https://maps.google.com/..." class="input" dir="ltr">
                    </div>
                </div>
            </div>
            
            {{-- Price List --}}
            <div class="bg-slate-50 rounded-xl p-5">
                <div class="flex justify-between items-center mb-4 flex-wrap gap-3">
                    <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-dollar-sign text-emerald-600"></i>
                        قائمة الأسعار
                    </h3>
                    <button type="button" id="add-price-row" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition-all">
                        <i class="fas fa-plus ml-1"></i> إضافة خدمة جديدة
                    </button>
                </div>
                
                <div id="price-list-container" class="space-y-3">
                    @php $priceList = is_array($business->price_list) ? $business->price_list : []; @endphp
                    @forelse($priceList as $index => $item)
                    <div class="flex gap-3 price-row items-center bg-white p-3 rounded-xl">
                        <input type="text" name="price_list[{{ $index }}][name]" value="{{ $item['name'] ?? '' }}" placeholder="اسم الخدمة" class="flex-1 input !bg-white">
                        <input type="text" name="price_list[{{ $index }}][price]" value="{{ $item['price'] ?? '' }}" placeholder="السعر (ل.س)" class="w-32 input !bg-white" dir="ltr">
                        <button type="button" class="remove-row bg-red-500/20 hover:bg-red-500 text-red-600 hover:text-white px-3 py-2.5 rounded-lg transition-all">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    @empty
                    <div id="no-prices-alert" class="text-center py-8 text-slate-500 border border-dashed border-slate-300 rounded-xl">
                        <i class="fas fa-box-open text-2xl mb-2 block"></i>
                        لا توجد أسعار مدخلة. أضف خدمات ومنتجات المنشأة.
                    </div>
                    @endforelse
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
                        <label class="label">اللوجو الحالي</label>
                        <div class="mb-3">
                            <img src="{{ $business->logo_url }}" class="w-20 h-20 rounded-xl object-cover border border-slate-200" loading="lazy">
                        </div>
                        <input type="file" name="logo" accept="image/*" class="input !p-2">
                        <p class="text-[10px] text-slate-400 mt-1">JPG, PNG (max 2MB)</p>
                    </div>
                    <div>
                        <label class="label">صورة الغلاف الحالية</label>
                        <div class="mb-3">
                            <img src="{{ $business->cover_url }}" class="w-full h-24 rounded-xl object-cover border border-slate-200" loading="lazy">
                        </div>
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
                        <input type="text" name="latitude" id="lat-input" readonly value="{{ old('latitude', $business->latitude ?? '33.5138') }}" class="input bg-slate-100 font-mono">
                    </div>
                    <div>
                        <label class="label">خط الطول (Longitude)</label>
                        <input type="text" name="longitude" id="lng-input" readonly value="{{ old('longitude', $business->longitude ?? '36.2765') }}" class="input bg-slate-100 font-mono">
                    </div>
                </div>
            </div>
            
            {{-- Submit Buttons --}}
            <div class="flex gap-4">
                <button type="submit" class="btn-primary flex-1 py-3">
                    <i class="fas fa-save ml-2"></i> حفظ التغييرات
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
        // Map
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
        
        // Price list
        let priceIndex = {{ count($priceList ?? []) }};
        const container = document.getElementById('price-list-container');
        const addBtn = document.getElementById('add-price-row');
        const noPricesAlert = document.getElementById('no-prices-alert');
        
        if (addBtn) {
            addBtn.addEventListener('click', () => {
                if (noPricesAlert) noPricesAlert.style.display = 'none';
                const row = document.createElement('div');
                row.className = 'flex gap-3 price-row items-center bg-white p-3 rounded-xl';
                row.innerHTML = `
                    <input type="text" name="price_list[${priceIndex}][name]" placeholder="اسم الخدمة" class="flex-1 input !bg-white">
                    <input type="text" name="price_list[${priceIndex}][price]" placeholder="السعر (ل.س)" class="w-32 input !bg-white" dir="ltr">
                    <button type="button" class="remove-row bg-red-500/20 hover:bg-red-500 text-red-600 hover:text-white px-3 py-2.5 rounded-lg transition-all"><i class="fas fa-trash"></i></button>
                `;
                container.appendChild(row);
                priceIndex++;
            });
        }
        
        container.addEventListener('click', (e) => {
            if (e.target.closest('.remove-row')) e.target.closest('.price-row')?.remove();
            if (container.children.length === 0 && noPricesAlert) noPricesAlert.style.display = 'block';
        });
    });
</script>
@endpush
@endsection