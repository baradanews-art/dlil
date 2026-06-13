@extends('layouts.admin')

@section('title', 'إضافة مؤسسة جديدة')
@section('page_heading', '➕ إضافة مؤسسة رسمية')
@section('page_subheading', 'إضافة مؤسسة حكومية، أمنية، أو مركز مساعدة')

@push('styles')
<style>
    .form-section {
        @apply bg-slate-50 rounded-xl p-5;
    }
    .form-section-title {
        @apply text-sm font-bold text-slate-800 mb-4 flex items-center gap-2 border-b border-slate-200 pb-2;
    }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="card">
        <div class="p-6">
            <form action="{{ route('admin.official.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">
                
                {{-- Basic Info --}}
                <div class="form-section">
                    <h3 class="form-section-title">
                        <i class="fas fa-info-circle text-blue-500"></i>
                        المعلومات الأساسية
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="label">اسم المؤسسة *</label>
                            <input type="text" name="name" required class="input" placeholder="مثال: وزارة السياحة">
                        </div>
                        <div>
                            <label class="label">النوع الفرعي</label>
                            <select name="sub_type" class="input">
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
                            <label class="label">المحافظة</label>
                            <select name="city_id" id="city_id" class="input">
                                <option value="">اختر المحافظة</option>
                                @foreach($cities ?? [] as $city)
                                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="label">المنطقة</label>
                            <select name="region_id" id="region_id" class="input" disabled>
                                <option value="">اختر المحافظة أولاً</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="label">الوصف</label>
                            <textarea name="description" rows="3" class="input" placeholder="نبذة عن المؤسسة وخدماتها..."></textarea>
                        </div>
                    </div>
                </div>
                
                {{-- Contact Info --}}
                <div class="form-section">
                    <h3 class="form-section-title">
                        <i class="fas fa-phone-alt text-green-500"></i>
                        معلومات الاتصال
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="label">رقم الهاتف</label>
                            <input type="text" name="phone" class="input" placeholder="011-1234567" dir="ltr">
                        </div>
                        <div>
                            <label class="label">رقم الطوارئ</label>
                            <input type="text" name="hotline" class="input" placeholder="112" dir="ltr">
                        </div>
                        <div>
                            <label class="label">الموقع الإلكتروني</label>
                            <input type="url" name="website" class="input" placeholder="https://example.com" dir="ltr">
                        </div>
                        <div>
                            <label class="label">البريد الإلكتروني</label>
                            <input type="email" name="email" class="input" placeholder="info@example.com" dir="ltr">
                        </div>
                        <div class="md:col-span-2">
                            <label class="label">العنوان</label>
                            <input type="text" name="address" class="input" placeholder="العنوان الكامل">
                        </div>
                        <div>
                            <label class="label">ساعات العمل</label>
                            <input type="text" name="working_hours" class="input" placeholder="الأحد - الخميس 9:00 - 17:00">
                        </div>
                    </div>
                </div>
                
                {{-- Social Media --}}
                <div class="form-section">
                    <h3 class="form-section-title">
                        <i class="fab fa-facebook text-blue-600"></i>
                        روابط التواصل الاجتماعي
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="label">فيسبوك</label>
                            <input type="url" name="facebook_url" class="input" placeholder="https://facebook.com/..." dir="ltr">
                        </div>
                        <div>
                            <label class="label">تويتر</label>
                            <input type="url" name="twitter_url" class="input" placeholder="https://twitter.com/..." dir="ltr">
                        </div>
                        <div>
                            <label class="label">انستغرام</label>
                            <input type="url" name="instagram_url" class="input" placeholder="https://instagram.com/..." dir="ltr">
                        </div>
                        <div>
                            <label class="label">يوتيوب</label>
                            <input type="url" name="youtube_url" class="input" placeholder="https://youtube.com/..." dir="ltr">
                        </div>
                        <div>
                            <label class="label">لينكد إن</label>
                            <input type="url" name="linkedin_url" class="input" placeholder="https://linkedin.com/..." dir="ltr">
                        </div>
                    </div>
                </div>
                
                {{-- Logo & Location --}}
                <div class="form-section">
                    <h3 class="form-section-title">
                        <i class="fas fa-image text-purple-500"></i>
                        الشعار والموقع الجغرافي
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="label">شعار المؤسسة</label>
                            <input type="file" name="logo" accept="image/*" class="input !p-2">
                            <p class="text-[10px] text-slate-400 mt-1">JPG, PNG (max 2MB)</p>
                        </div>
                        <div>
                            <label class="label">ترتيب الظهور</label>
                            <input type="number" name="sort_order" value="0" class="input">
                        </div>
                        <div>
                            <label class="label">خط العرض (Latitude)</label>
                            <input type="text" name="latitude" value="33.5138" class="input" dir="ltr">
                        </div>
                        <div>
                            <label class="label">خط الطول (Longitude)</label>
                            <input type="text" name="longitude" value="36.2765" class="input" dir="ltr">
                        </div>
                    </div>
                </div>
                
                {{-- Submit Buttons --}}
                <div class="flex gap-4 pt-4">
                    <button type="submit" class="btn-primary flex-1 py-3">
                        <i class="fas fa-save ml-2"></i> حفظ المؤسسة
                    </button>
                    <a href="{{ route('admin.official.index', ['type' => $type]) }}" class="btn-secondary flex-1 py-3 text-center">
                        <i class="fas fa-times ml-2"></i> إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const citySelect = document.getElementById('city_id');
    const regionSelect = document.getElementById('region_id');
    
    if (citySelect) {
        citySelect.addEventListener('change', function() {
            const cityId = this.value;
            
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
                    console.error('Error:', error);
                    regionSelect.innerHTML = '<option value="">❌ حدث خطأ في التحميل</option>';
                    regionSelect.disabled = false;
                });
        });
    }
</script>
@endpush
@endsection