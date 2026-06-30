@extends('layouts.admin')

@section('title', 'تعديل مؤسسة: ' . $entity->name)
@section('page_heading', '✏️ تعديل المؤسسة')
@section('page_subheading', 'تحديث بيانات: ' . $entity->name)

@push('styles')
<style>
    .form-section {
        background-color: #f8fafc;
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid #e2e8f0;
    }
    .form-section-title {
        font-size: 0.9rem;
        font-weight: bold;
        color: #1e293b;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 0.5rem;
    }
    .label {
        display: block;
        font-size: 0.7rem;
        font-weight: bold;
        color: #475569;
        margin-bottom: 0.35rem;
    }
    .input {
        width: 100%;
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        padding: 0.6rem 1rem;
        font-size: 0.85rem;
        transition: all 0.2s;
    }
    .input:focus {
        outline: none;
        border-color: #10b981;
        ring: 2px solid #10b981;
    }
    .btn-primary {
        background-color: #10b981;
        color: white;
        font-weight: bold;
        padding: 0.6rem 1rem;
        border-radius: 0.75rem;
        transition: all 0.2s;
    }
    .btn-primary:hover {
        background-color: #059669;
        transform: scale(1.02);
    }
    .btn-secondary {
        background-color: #64748b;
        color: white;
        font-weight: bold;
        padding: 0.6rem 1rem;
        border-radius: 0.75rem;
        text-align: center;
        transition: all 0.2s;
    }
    .btn-secondary:hover {
        background-color: #475569;
    }
    .card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
</style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="card">
        <div class="p-8">
            <form action="{{ route('admin.official.update', $entity->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="type" value="{{ $entity->type }}">
                
                {{-- Basic Info --}}
                <div class="form-section">
                    <h3 class="form-section-title">
                        <i class="fas fa-info-circle text-blue-500"></i>
                        المعلومات الأساسية
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="label">اسم المؤسسة *</label>
                            <input type="text" name="name" value="{{ old('name', $entity->name) }}" required class="input">
                        </div>
                        <div>
                            <label class="label">النوع الفرعي</label>
                            <select name="sub_type" class="input">
                                <option value="">اختر النوع الفرعي</option>
                                @if($entity->type == 'security')
                                    <option value="police_station" {{ $entity->sub_type == 'police_station' ? 'selected' : '' }}>مركز شرطة</option>
                                    <option value="criminal_investigation" {{ $entity->sub_type == 'criminal_investigation' ? 'selected' : '' }}>مباحث</option>
                                    <option value="drug_enforcement" {{ $entity->sub_type == 'drug_enforcement' ? 'selected' : '' }}>مكافحة مخدرات</option>
                                    <option value="traffic" {{ $entity->sub_type == 'traffic' ? 'selected' : '' }}>مرور</option>
                                    <option value="passports" {{ $entity->sub_type == 'passports' ? 'selected' : '' }}>جوازات</option>
                                    <option value="civil_defense" {{ $entity->sub_type == 'civil_defense' ? 'selected' : '' }}>دفاع مدني</option>
                                    <option value="emergency" {{ $entity->sub_type == 'emergency' ? 'selected' : '' }}>طوارئ</option>
                                @elseif($entity->type == 'government')
                                    <option value="ministry" {{ $entity->sub_type == 'ministry' ? 'selected' : '' }}>وزارة</option>
                                    <option value="directorate" {{ $entity->sub_type == 'directorate' ? 'selected' : '' }}>مديرية</option>
                                    <option value="municipality" {{ $entity->sub_type == 'municipality' ? 'selected' : '' }}>بلدية</option>
                                    <option value="government_office" {{ $entity->sub_type == 'government_office' ? 'selected' : '' }}>مكتب حكومي</option>
                                @else
                                    <option value="hospital" {{ $entity->sub_type == 'hospital' ? 'selected' : '' }}>مستشفى</option>
                                    <option value="clinic" {{ $entity->sub_type == 'clinic' ? 'selected' : '' }}>مركز صحي</option>
                                    <option value="charity" {{ $entity->sub_type == 'charity' ? 'selected' : '' }}>جمعية خيرية</option>
                                    <option value="social_care" {{ $entity->sub_type == 'social_care' ? 'selected' : '' }}>رعاية اجتماعية</option>
                                @endif
                            </select>
                        </div>
                        <div>
                            <label class="label">المحافظة</label>
                            <select name="city_id" id="city_id" class="input">
                                <option value="">اختر المحافظة</option>
                                @foreach($cities ?? [] as $city)
                                    <option value="{{ $city->id }}" {{ $entity->city_id == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="label">المنطقة / الحي</label>
                            <select name="region_id" id="region_id" class="input" {{ !$entity->city_id ? 'disabled' : '' }}>
                                <option value="">اختر المنطقة</option>
                                @foreach($regions ?? [] as $region)
                                    <option value="{{ $region->id }}" {{ $entity->region_id == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="label">الوصف</label>
                            <textarea name="description" rows="4" class="input">{{ old('description', $entity->description) }}</textarea>
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
                            <input type="text" name="phone" value="{{ old('phone', $entity->phone) }}" class="input" dir="ltr" placeholder="011-1234567">
                        </div>
                        <div>
                            <label class="label">رقم الطوارئ</label>
                            <input type="text" name="hotline" value="{{ old('hotline', $entity->hotline) }}" class="input" dir="ltr" placeholder="112">
                        </div>
                        <div>
                            <label class="label">الموقع الإلكتروني</label>
                            <input type="url" name="website" value="{{ old('website', $entity->website) }}" class="input" dir="ltr" placeholder="https://example.com">
                        </div>
                        <div>
                            <label class="label">البريد الإلكتروني</label>
                            <input type="email" name="email" value="{{ old('email', $entity->email) }}" class="input" dir="ltr" placeholder="info@example.com">
                        </div>
                        <div class="md:col-span-2">
                            <label class="label">العنوان بالتفصيل</label>
                            <input type="text" name="address" value="{{ old('address', $entity->address) }}" class="input" placeholder="الشارع، المبنى، المنطقة...">
                        </div>
                        <div class="md:col-span-2">
                            <label class="label">ساعات العمل</label>
                            <input type="text" name="working_hours" value="{{ old('working_hours', $entity->working_hours) }}" class="input" placeholder="الأحد - الخميس 9:00 - 17:00">
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
                            <input type="url" name="facebook_url" value="{{ old('facebook_url', $entity->facebook_url) }}" class="input" dir="ltr" placeholder="https://facebook.com/...">
                        </div>
                        <div>
                            <label class="label">تويتر</label>
                            <input type="url" name="twitter_url" value="{{ old('twitter_url', $entity->twitter_url) }}" class="input" dir="ltr" placeholder="https://twitter.com/...">
                        </div>
                        <div>
                            <label class="label">انستغرام</label>
                            <input type="url" name="instagram_url" value="{{ old('instagram_url', $entity->instagram_url) }}" class="input" dir="ltr" placeholder="https://instagram.com/...">
                        </div>
                        <div>
                            <label class="label">يوتيوب</label>
                            <input type="url" name="youtube_url" value="{{ old('youtube_url', $entity->youtube_url) }}" class="input" dir="ltr" placeholder="https://youtube.com/...">
                        </div>
                        <div>
                            <label class="label">لينكد إن</label>
                            <input type="url" name="linkedin_url" value="{{ old('linkedin_url', $entity->linkedin_url) }}" class="input" dir="ltr" placeholder="https://linkedin.com/...">
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
                            <label class="label">الشعار الحالي</label>
                            <div class="mb-3">
                                <img src="{{ $entity->logo_url }}" class="w-20 h-20 rounded-xl object-cover border border-slate-200 bg-slate-50" loading="lazy">
                            </div>
                            <input type="file" name="logo" accept="image/*" class="input !p-2">
                            <p class="text-[10px] text-slate-400 mt-1">JPG, PNG, WEBP (max 2MB)</p>
                        </div>
                        <div>
                            <label class="label">ترتيب الظهور</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $entity->sort_order) }}" class="input">
                        </div>
                        <div>
                            <label class="label">خط العرض (Latitude)</label>
                            <input type="text" name="latitude" value="{{ old('latitude', $entity->latitude ?? '33.5138') }}" class="input" dir="ltr">
                        </div>
                        <div>
                            <label class="label">خط الطول (Longitude)</label>
                            <input type="text" name="longitude" value="{{ old('longitude', $entity->longitude ?? '36.2765') }}" class="input" dir="ltr">
                        </div>
                    </div>
                </div>
                
                {{-- Submit Buttons --}}
                <div class="flex gap-4 pt-4">
                    <button type="submit" class="btn-primary flex-1 py-3">
                        <i class="fas fa-save ml-2"></i> حفظ التغييرات
                    </button>
                    <a href="{{ route('admin.official.index', ['type' => $entity->type]) }}" class="btn-secondary flex-1 py-3 text-center">
                        <i class="fas fa-times ml-2"></i> إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const citySelect = document.getElementById('city_id');
        const regionSelect = document.getElementById('region_id');
        const currentRegionId = {{ $entity->region_id ?? 'null' }};
        
        function loadRegions(cityId, selectedRegionId = null) {
            if (!cityId) {
                regionSelect.innerHTML = '<option value="">اختر المحافظة أولاً</option>';
                regionSelect.disabled = true;
                return;
            }
            
            regionSelect.innerHTML = '<option value="">⏳ جاري التحميل...</option>';
            regionSelect.disabled = true;
            
            fetch(`{{ url('/admin/get-regions') }}/${cityId}`)
                .then(response => response.json())
                .then(data => {
                    regionSelect.innerHTML = '<option value="">اختر المنطقة أو الحي</option>';
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
                        regionSelect.innerHTML = '<option value="">⚠️ لا توجد مناطق مسجلة لهذه المحافظة</option>';
                        regionSelect.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    regionSelect.innerHTML = '<option value="">❌ حدث خطأ في التحميل</option>';
                    regionSelect.disabled = false;
                });
        }
        
        if (citySelect) {
            citySelect.addEventListener('change', function() {
                loadRegions(this.value);
            });
            
            // تحميل المناطق عند تحميل الصفحة إذا كانت المحافظة محددة مسبقاً
            if (citySelect.value) {
                loadRegions(citySelect.value, currentRegionId);
            }
        }
    });
</script>
@endpush
@endsection