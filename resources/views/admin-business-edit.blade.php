<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل منشأة: {{ $business->title }} | لوحة التحكم</title>
    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #1e293b; }
        ::-webkit-scrollbar-thumb { background: #10b981; border-radius: 10px; }
        
        .sidebar-transition { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .price-row { transition: all 0.2s ease; }
        .price-row:hover { background-color: #1e293b; }
        
        @media (max-width: 768px) {
            .sidebar-mobile-hidden { transform: translateX(100%); }
            .sidebar-mobile-visible { transform: translateX(0); }
        }
        
        #map-picker { height: 350px; width: 100%; border-radius: 16px; z-index: 1; }
    </style>
</head>
<body class="bg-slate-950 font-sans antialiased">

    <button id="mobileMenuBtn" class="lg:hidden fixed top-4 right-4 z-50 bg-emerald-600 text-white p-3 rounded-xl shadow-lg">
        <i class="fas fa-bars text-xl"></i>
    </button>

    <div class="flex min-h-screen">
        
        {{-- Sidebar --}}
        <aside id="sidebar" class="fixed lg:static inset-y-0 right-0 z-40 w-72 bg-slate-900 border-l border-slate-800 flex flex-col sidebar-transition transform lg:transform-none sidebar-mobile-hidden">
            <div class="p-6 border-b border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-line text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-black text-white">لوحة الإشراف</h2>
                        <p class="text-[10px] text-emerald-400">تعديل المنشأة</p>
                    </div>
                </div>
                <button id="closeSidebar" class="lg:hidden absolute top-4 left-4 text-slate-400 hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <nav class="flex-1 px-4 space-y-1 mt-4">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                    <i class="fas fa-tachometer-alt w-5"></i>
                    <span class="text-sm font-bold">لوحة التحكم</span>
                </a>
                <a href="{{ route('admin.businesses.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-600/20 text-emerald-400 transition-all">
                    <i class="fas fa-store w-5"></i>
                    <span class="text-sm font-bold">المنشآت التجارية</span>
                </a>
                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                    <i class="fas fa-tags w-5"></i>
                    <span class="text-sm font-bold">التصنيفات</span>
                </a>
                <a href="{{ route('admin.locations.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                    <i class="fas fa-map-marker-alt w-5"></i>
                    <span class="text-sm font-bold">المواقع الجغرافية</span>
                </a>
                <a href="{{ route('admin.reviews.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                    <i class="fas fa-star w-5"></i>
                    <span class="text-sm font-bold">التقييمات</span>
                </a>
                <a href="{{ route('admin.ads.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                    <i class="fas fa-ad w-5"></i>
                    <span class="text-sm font-bold">الإعلانات</span>
                </a>
            </nav>
            
            <div class="p-4 border-t border-slate-800 mt-auto">
                <a href="{{ route('home') }}" target="_blank" class="flex items-center justify-center gap-2 w-full bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold py-2.5 rounded-xl transition-all">
                    <i class="fas fa-external-link-alt"></i> عرض الموقع العام
                </a>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 overflow-x-hidden">
            <div class="bg-slate-900 border-b border-slate-800 px-6 py-4 sticky top-0 z-30">
                <div class="flex justify-between items-center flex-wrap gap-3">
                    <div>
                        <h1 class="text-lg font-black text-white flex items-center gap-2">
                            <i class="fas fa-edit text-emerald-400"></i>
                            تعديل المنشأة
                        </h1>
                        <p class="text-xs text-slate-400 mt-0.5">تحديث بيانات: <span class="text-emerald-400 font-bold">{{ $business->title }}</span></p>
                    </div>
                    <a href="{{ route('admin.businesses.index') }}" class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-4 py-2 rounded-xl text-xs font-bold transition-all">
                        <i class="fas fa-arrow-right ml-1"></i> العودة
                    </a>
                </div>
            </div>
            
            <div class="p-6 max-w-5xl mx-auto">
                
                @if($errors->any())
                <div class="bg-red-500/10 border border-red-500/30 rounded-2xl p-4 mb-6">
                    <div class="flex items-center gap-2 text-red-400 mb-2">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span class="text-sm font-bold">يرجى تصحيح الأخطاء التالية:</span>
                    </div>
                    <ul class="list-disc list-inside text-xs text-red-300 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <form action="{{ route('admin.businesses.update', $business->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    {{-- Status & Verification --}}
                    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6">
                        <h3 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                            <i class="fas fa-shield-alt text-emerald-400"></i>
                            حالة المنشأة والتحقق
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 mb-2">حالة الظهور</label>
                                <select name="status" class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-3 rounded-xl focus:outline-none focus:border-emerald-500">
                                    <option value="approved" {{ $business->is_approved ? 'selected' : '' }}>✅ معتمدة ومنشورة</option>
                                    <option value="pending" {{ !$business->is_approved ? 'selected' : '' }}>⏳ معلقة بانتظار المراجعة</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 mb-2">شارة التوثيق</label>
                                <select name="verification_type" class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-3 rounded-xl focus:outline-none focus:border-emerald-500">
                                    <option value="none" {{ $business->verification_type == 'none' ? 'selected' : '' }}>⚪ غير موثق</option>
                                    <option value="verified" {{ $business->verification_type == 'verified' ? 'selected' : '' }}>🔵 موثق (Verified)</option>
                                    <option value="official" {{ $business->verification_type == 'official' ? 'selected' : '' }}>👑 رسمي (Official)</option>
                                </select>
                            </div>
                            <div class="flex items-center gap-3 pt-6">
                                <input type="checkbox" id="delivery_available" name="delivery_available" value="1" {{ $business->delivery_available ? 'checked' : '' }} class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                                <label for="delivery_available" class="text-sm font-bold text-slate-300 cursor-pointer">
                                    <i class="fas fa-motorcycle ml-1"></i> تتوفر خدمة التوصيل
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Basic Info --}}
                    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6">
                        <h3 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                            <i class="fas fa-info-circle text-blue-400"></i>
                            المعلومات الأساسية
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 mb-2">اسم المنشأة *</label>
                                <input type="text" name="title" value="{{ old('title', $business->title) }}" required class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-3 rounded-xl focus:outline-none focus:border-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 mb-2">رقم الهاتف *</label>
                                <input type="text" name="phone" value="{{ old('phone', $business->phone) }}" required class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-3 rounded-xl focus:outline-none focus:border-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 mb-2">التصنيف *</label>
                                <select name="category_id" class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-3 rounded-xl focus:outline-none focus:border-emerald-500">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $business->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->icon ?? '📁' }} {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 mb-2">الموقع *</label>
                                <select name="location_id" class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-3 rounded-xl focus:outline-none focus:border-emerald-500">
                                    @foreach($locations as $loc)
                                        <option value="{{ $loc->id }}" {{ $business->location_id == $loc->id ? 'selected' : '' }}>
                                            {{ $loc->parent ? $loc->parent->name . ' - ' : '' }}{{ $loc->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-400 mb-2">العنوان بالتفصيل</label>
                                <input type="text" name="address_detail" value="{{ old('address_detail', $business->address_detail) }}" class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-3 rounded-xl focus:outline-none focus:border-emerald-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-400 mb-2">وصف المنشأة *</label>
                                <textarea name="description" rows="5" class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-3 rounded-xl focus:outline-none focus:border-emerald-500">{{ old('description', $business->description) }}</textarea>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Social Links --}}
                    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6">
                        <h3 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                            <i class="fas fa-share-alt text-sky-400"></i>
                            روابط التواصل الاجتماعي
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 mb-2">فيسبوك</label>
                                <input type="url" name="facebook_url" value="{{ old('facebook_url', $business->facebook_url) }}" placeholder="https://facebook.com/..." class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-3 rounded-xl focus:outline-none focus:border-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 mb-2">انستغرام</label>
                                <input type="url" name="instagram_url" value="{{ old('instagram_url', $business->instagram_url) }}" placeholder="https://instagram.com/..." class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-3 rounded-xl focus:outline-none focus:border-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 mb-2">خرائط جوجل</label>
                                <input type="url" name="google_maps_url" value="{{ old('google_maps_url', $business->Maps_url) }}" placeholder="https://maps.google.com/..." class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-3 rounded-xl focus:outline-none focus:border-emerald-500">
                            </div>
                        </div>
                    </div>
                    
                    {{-- Price List --}}
                    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6">
                        <div class="flex justify-between items-center mb-4 flex-wrap gap-3">
                            <h3 class="text-sm font-bold text-white flex items-center gap-2">
                                <i class="fas fa-dollar-sign text-emerald-400"></i>
                                قائمة الأسعار
                            </h3>
                            <button type="button" id="add-price-row" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition-all">
                                <i class="fas fa-plus ml-1"></i> إضافة خدمة جديدة
                            </button>
                        </div>
                        <div id="price-list-container" class="space-y-3">
                            @php $priceList = is_array($business->price_list) ? $business->price_list : []; @endphp
                            @forelse($priceList as $index => $item)
                            <div class="flex gap-3 price-row items-center bg-slate-800 p-3 rounded-xl">
                                <input type="text" name="price_list[{{ $index }}][name]" value="{{ $item['name'] ?? '' }}" placeholder="اسم الخدمة" class="flex-1 bg-slate-700 border border-slate-600 text-white text-sm p-2.5 rounded-lg focus:outline-none focus:border-emerald-500">
                                <input type="text" name="price_list[{{ $index }}][price]" value="{{ $item['price'] ?? '' }}" placeholder="السعر (ل.س)" class="w-32 bg-slate-700 border border-slate-600 text-white text-sm p-2.5 rounded-lg focus:outline-none focus:border-emerald-500">
                                <button type="button" class="remove-row bg-red-500/20 hover:bg-red-500 text-red-400 hover:text-white px-3 py-2.5 rounded-lg transition-all">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            @empty
                            <div id="no-prices-alert" class="text-center py-8 text-slate-500 border border-dashed border-slate-700 rounded-xl">
                                <i class="fas fa-box-open text-2xl mb-2 block"></i>
                                لا توجد أسعار مدخلة. أضف خدمات ومنتجات المنشأة.
                            </div>
                            @endforelse
                        </div>
                    </div>
                    
                    {{-- Images --}}
                    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6">
                        <h3 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                            <i class="fas fa-image text-purple-400"></i>
                            الصور
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 mb-2">اللوجو الحالي</label>
                                <div class="mb-3">
                                    @if($business->logo && file_exists(base_path('storage/app/public/' . $business->logo)))
                                        <img src="{{ url('/storage/' . $business->logo) }}" class="w-20 h-20 rounded-xl object-cover border border-slate-700">
                                    @else
                                        <div class="w-20 h-20 bg-slate-800 rounded-xl flex items-center justify-center">
                                            <i class="fas fa-store text-slate-600 text-2xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <input type="file" name="logo" class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-2 rounded-xl focus:outline-none focus:border-emerald-500">
                                <p class="text-[10px] text-slate-500 mt-1">JPG, PNG (max 2MB)</p>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 mb-2">صورة الغلاف الحالية</label>
                                <div class="mb-3">
                                    @if($business->cover && file_exists(base_path('storage/app/public/' . $business->cover)))
                                        <img src="{{ url('/storage/' . $business->cover) }}" class="w-full h-24 rounded-xl object-cover border border-slate-700">
                                    @else
                                        <div class="w-full h-24 bg-slate-800 rounded-xl flex items-center justify-center">
                                            <i class="fas fa-image text-slate-600 text-2xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <input type="file" name="cover" class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-2 rounded-xl focus:outline-none focus:border-emerald-500">
                                <p class="text-[10px] text-slate-500 mt-1">JPG, PNG (max 3MB)</p>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Map --}}
                    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6">
                        <h3 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                            <i class="fas fa-map text-rose-400"></i>
                            الموقع الجغرافي
                        </h3>
                        <div id="map-picker"></div>
                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 mb-1">خط العرض (Latitude)</label>
                                <input type="text" name="latitude" id="lat-input" readonly value="{{ old('latitude', $business->latitude ?? '33.5138') }}" class="w-full bg-slate-800 border border-slate-700 text-slate-300 text-sm p-2.5 rounded-lg font-mono">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 mb-1">خط الطول (Longitude)</label>
                                <input type="text" name="longitude" id="lng-input" readonly value="{{ old('longitude', $business->longitude ?? '36.2765') }}" class="w-full bg-slate-800 border border-slate-700 text-slate-300 text-sm p-2.5 rounded-lg font-mono">
                            </div>
                        </div>
                    </div>
                    
                    {{-- Submit Button --}}
                    <div class="flex gap-4">
                        <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-xl transition-all transform hover:scale-105">
                            <i class="fas fa-save ml-2"></i> حفظ التغييرات
                        </button>
                        <a href="{{ route('admin.businesses.index') }}" class="px-6 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold rounded-xl transition-all flex items-center gap-2">
                            <i class="fas fa-times"></i> إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Map
            let latInput = document.getElementById('lat-input');
            let lngInput = document.getElementById('lng-input');
            let defaultLat = parseFloat(latInput.value);
            let defaultLng = parseFloat(lngInput.value);
            let map = L.map('map-picker').setView([defaultLat, defaultLng], 14);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);
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
            
            addBtn.addEventListener('click', () => {
                if (noPricesAlert) noPricesAlert.style.display = 'none';
                const row = document.createElement('div');
                row.className = 'flex gap-3 price-row items-center bg-slate-800 p-3 rounded-xl';
                row.innerHTML = `
                    <input type="text" name="price_list[${priceIndex}][name]" placeholder="اسم الخدمة" class="flex-1 bg-slate-700 border border-slate-600 text-white text-sm p-2.5 rounded-lg focus:outline-none focus:border-emerald-500">
                    <input type="text" name="price_list[${priceIndex}][price]" placeholder="السعر (ل.س)" class="w-32 bg-slate-700 border border-slate-600 text-white text-sm p-2.5 rounded-lg focus:outline-none focus:border-emerald-500">
                    <button type="button" class="remove-row bg-red-500/20 hover:bg-red-500 text-red-400 hover:text-white px-3 py-2.5 rounded-lg transition-all"><i class="fas fa-trash"></i></button>
                `;
                container.appendChild(row);
                priceIndex++;
            });
            
            container.addEventListener('click', (e) => {
                if (e.target.closest('.remove-row')) e.target.closest('.price-row').remove();
            });
            
            // Mobile sidebar
            const sidebar = document.getElementById('sidebar');
            document.getElementById('mobileMenuBtn')?.addEventListener('click', () => {
                sidebar.classList.remove('sidebar-mobile-hidden');
                sidebar.classList.add('sidebar-mobile-visible');
            });
            document.getElementById('closeSidebar')?.addEventListener('click', () => {
                sidebar.classList.add('sidebar-mobile-hidden');
                sidebar.classList.remove('sidebar-mobile-visible');
            });
        });
    </script>
</body>
</html>