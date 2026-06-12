<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة مؤسسة جديدة | لوحة التحكم</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #1e293b; }
        ::-webkit-scrollbar-thumb { background: #10b981; border-radius: 10px; }
        .sidebar-transition { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        @media (max-width: 768px) {
            .sidebar-mobile-hidden { transform: translateX(100%); }
            .sidebar-mobile-visible { transform: translateX(0); }
        }
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
                        <p class="text-[10px] text-emerald-400">إضافة مؤسسة جديدة</p>
                    </div>
                </div>
                <button id="closeSidebar" class="lg:hidden absolute top-4 left-4 text-slate-400 hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <nav class="flex-1 px-4 space-y-1 mt-4">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                    <i class="fas fa-tachometer-alt w-5"></i> <span class="text-sm font-bold">لوحة التحكم</span>
                </a>
                <a href="{{ route('admin.businesses.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                    <i class="fas fa-store w-5"></i> <span class="text-sm font-bold">المنشآت التجارية</span>
                </a>
                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                    <i class="fas fa-tags w-5"></i> <span class="text-sm font-bold">التصنيفات</span>
                </a>
                <a href="{{ route('admin.locations.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                    <i class="fas fa-map-marker-alt w-5"></i> <span class="text-sm font-bold">المواقع الجغرافية</span>
                </a>
                <a href="{{ route('admin.reviews.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                    <i class="fas fa-star w-5"></i> <span class="text-sm font-bold">التقييمات</span>
                </a>
                <a href="{{ route('admin.ads.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                    <i class="fas fa-ad w-5"></i> <span class="text-sm font-bold">الإعلانات</span>
                </a>
                <a href="{{ route('admin.official.index', ['type' => $type ?? 'government']) }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-600/20 text-emerald-400 transition-all">
                    <i class="fas fa-landmark w-5"></i> <span class="text-sm font-bold">المؤسسات الرسمية</span>
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
                            <i class="fas fa-plus-circle text-emerald-400"></i>
                            إضافة مؤسسة جديدة
                        </h1>
                        <p class="text-xs text-slate-400 mt-0.5">إضافة مؤسسة حكومية، أمنية، أو مركز مساعدة</p>
                    </div>
                    <a href="{{ route('admin.official.index', ['type' => $type]) }}" class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-4 py-2 rounded-xl text-xs font-bold transition-all">
                        <i class="fas fa-arrow-right ml-1"></i> العودة للقائمة
                    </a>
                </div>
            </div>
            
            <div class="p-6 max-w-4xl mx-auto">
                <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6">
                    <form action="{{ route('admin.official.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <input type="hidden" name="type" value="{{ $type }}">
                        
                        {{-- Basic Info --}}
                        <div>
                            <h3 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                                <i class="fas fa-info-circle text-blue-400"></i>
                                المعلومات الأساسية
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-400 mb-2">اسم المؤسسة *</label>
                                    <input type="text" name="name" required class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-3 rounded-xl focus:outline-none focus:border-emerald-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 mb-2">النوع الفرعي</label>
                                    <select name="sub_type" class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-3 rounded-xl focus:outline-none focus:border-emerald-500">
                                        <option value="">اختر النوع الفرعي</option>
                                        @if($type == 'security')
                                            <option value="police_station">مركز شرطة</option>
                                            <option value="criminal_investigation">مباحث</option>
                                            <option value="drug_enforcement">مكافحة مخدرات</option>
                                            <option value="traffic">مرور</option>
                                            <option value="passports">جوازات</option>
                                            <option value="civil_defense">دفاع مدني</option>
                                            <option value="emergency">طوارئ</option>
                                        @elseif($type == 'government')
                                            <option value="ministry">وزارة</option>
                                            <option value="directorate">مديرية</option>
                                            <option value="municipality">بلدية</option>
                                            <option value="government_office">مكتب حكومي</option>
                                        @else
                                            <option value="hospital">مستشفى</option>
                                            <option value="clinic">مركز صحي</option>
                                            <option value="charity">جمعية خيرية</option>
                                            <option value="social_care">رعاية اجتماعية</option>
                                        @endif
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 mb-2">المحافظة</label>
                                    <select name="city_id" id="city_id" class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-3 rounded-xl focus:outline-none focus:border-emerald-500">
                                        <option value="">اختر المحافظة</option>
                                        @foreach($cities ?? [] as $city)
                                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 mb-2">المنطقة</label>
                                    <select name="region_id" id="region_id" class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-3 rounded-xl" disabled>
                                        <option value="">اختر المحافظة أولاً</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-400 mb-2">الوصف</label>
                                    <textarea name="description" rows="3" class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-3 rounded-xl focus:outline-none focus:border-emerald-500"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Contact Info --}}
                        <div>
                            <h3 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                                <i class="fas fa-phone-alt text-green-400"></i>
                                معلومات الاتصال
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 mb-2">رقم الهاتف</label>
                                    <input type="text" name="phone" class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-3 rounded-xl focus:outline-none focus:border-emerald-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 mb-2">رقم الطوارئ</label>
                                    <input type="text" name="hotline" class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-3 rounded-xl focus:outline-none focus:border-emerald-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 mb-2">الموقع الإلكتروني</label>
                                    <input type="url" name="website" placeholder="https://example.com" class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-3 rounded-xl focus:outline-none focus:border-emerald-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 mb-2">البريد الإلكتروني</label>
                                    <input type="email" name="email" class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-3 rounded-xl focus:outline-none focus:border-emerald-500">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-400 mb-2">العنوان</label>
                                    <input type="text" name="address" class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-3 rounded-xl focus:outline-none focus:border-emerald-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 mb-2">ساعات العمل</label>
                                    <input type="text" name="working_hours" placeholder="الأحد - الخميس 9:00 - 17:00" class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-3 rounded-xl focus:outline-none focus:border-emerald-500">
                                </div>
                            </div>
                        </div>
                        
                        {{-- Social Media Links --}}
                        <div>
                            <h3 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                                <i class="fas fa-share-alt text-purple-400"></i>
                                روابط التواصل الاجتماعي
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 mb-2">فيسبوك</label>
                                    <input type="url" name="facebook_url" placeholder="https://facebook.com/..." class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-3 rounded-xl focus:outline-none focus:border-emerald-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 mb-2">تويتر</label>
                                    <input type="url" name="twitter_url" placeholder="https://twitter.com/..." class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-3 rounded-xl focus:outline-none focus:border-emerald-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 mb-2">انستغرام</label>
                                    <input type="url" name="instagram_url" placeholder="https://instagram.com/..." class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-3 rounded-xl focus:outline-none focus:border-emerald-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 mb-2">يوتيوب</label>
                                    <input type="url" name="youtube_url" placeholder="https://youtube.com/..." class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-3 rounded-xl focus:outline-none focus:border-emerald-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 mb-2">لينكد إن</label>
                                    <input type="url" name="linkedin_url" placeholder="https://linkedin.com/..." class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-3 rounded-xl focus:outline-none focus:border-emerald-500">
                                </div>
                            </div>
                        </div>
                        
                        {{-- Logo & Location --}}
                        <div>
                            <h3 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                                <i class="fas fa-image text-amber-400"></i>
                                الشعار والموقع الجغرافي
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 mb-2">شعار المؤسسة</label>
                                    <input type="file" name="logo" accept="image/*" class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-2 rounded-xl focus:outline-none focus:border-emerald-500">
                                    <p class="text-[10px] text-slate-500 mt-1">JPG, PNG (max 2MB)</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 mb-2">ترتيب الظهور</label>
                                    <input type="number" name="sort_order" value="0" class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-3 rounded-xl focus:outline-none focus:border-emerald-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 mb-2">خط العرض (Latitude)</label>
                                    <input type="text" name="latitude" value="33.5138" class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-3 rounded-xl focus:outline-none focus:border-emerald-500">
                                    <p class="text-[10px] text-slate-500 mt-1">مثال: 33.5138 (دمشق)</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 mb-2">خط الطول (Longitude)</label>
                                    <input type="text" name="longitude" value="36.2765" class="w-full bg-slate-800 border border-slate-700 text-white text-sm p-3 rounded-xl focus:outline-none focus:border-emerald-500">
                                    <p class="text-[10px] text-slate-500 mt-1">مثال: 36.2765 (دمشق)</p>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Submit Buttons --}}
                        <div class="flex gap-3 pt-4">
                            <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl transition-all">
                                <i class="fas fa-save ml-2"></i> حفظ المؤسسة
                            </button>
                            <a href="{{ route('admin.official.index', ['type' => $type]) }}" class="flex-1 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold py-3 rounded-xl text-center transition-all">
                                <i class="fas fa-times ml-2"></i> إلغاء
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    {{-- JavaScript لجلب المناطق --}}
    <script>
        const citySelect = document.getElementById('city_id');
        const regionSelect = document.getElementById('region_id');

        if (citySelect) {
            citySelect.addEventListener('change', function() {
                const cityId = this.value;
                console.log('تم اختيار المحافظة ID:', cityId);
                
                if (!cityId) {
                    regionSelect.innerHTML = '<option value="">اختر المحافظة أولاً</option>';
                    regionSelect.disabled = true;
                    return;
                }
                
                regionSelect.innerHTML = '<option value="">⏳ جاري التحميل...</option>';
                regionSelect.disabled = true;
                
                fetch(`/admin/get-regions/${cityId}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log('المناطق المستلمة:', data);
                        regionSelect.innerHTML = '<option value="">اختر المنطقة</option>';
                        if (data && data.length > 0) {
                            data.forEach(region => {
                                const option = document.createElement('option');
                                option.value = region.id;
                                option.textContent = region.name;
                                regionSelect.appendChild(option);
                            });
                            regionSelect.disabled = false;
                        } else {
                            regionSelect.innerHTML = '<option value="">⚠️ لا توجد مناطق مسجلة</option>';
                            regionSelect.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('خطأ في جلب المناطق:', error);
                        regionSelect.innerHTML = '<option value="">❌ حدث خطأ في التحميل</option>';
                        regionSelect.disabled = false;
                    });
            });
        }
        
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
    </script>
</body>
</html>