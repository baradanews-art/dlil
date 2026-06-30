

<?php $__env->startSection('title', 'تعديل منشأة: ' . $business->title); ?>
<?php $__env->startSection('page_heading', '✏️ تعديل المنشأة'); ?>
<?php $__env->startSection('page_subheading', 'تحديث بيانات: ' . $business->title); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map-picker { height: 350px; width: 100%; border-radius: 16px; z-index: 1; }
        .price-row { transition: all 0.2s ease; }
        .price-row:hover { background-color: #f8fafc; }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="p-6">
        <form action="<?php echo e(route('admin.businesses.update', $business->id)); ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            
            <div class="bg-slate-50 rounded-xl p-5">
                <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-shield-alt text-emerald-600"></i>
                    حالة المنشأة والتحقق
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="label">حالة الظهور</label>
                        <select name="status" class="input">
                            <option value="approved" <?php echo e($business->is_approved ? 'selected' : ''); ?>>✅ معتمدة ومنشورة</option>
                            <option value="pending" <?php echo e(!$business->is_approved ? 'selected' : ''); ?>>⏳ معلقة بانتظار المراجعة</option>
                        </select>
                    </div>
                    <div>
                        <label class="label">شارة التوثيق</label>
                        <select name="verification_type" class="input">
                            <option value="none" <?php echo e($business->verification_type == 'none' ? 'selected' : ''); ?>>⚪ غير موثق</option>
                            <option value="verified" <?php echo e($business->verification_type == 'verified' ? 'selected' : ''); ?>>🔵 موثق (Verified)</option>
                            <option value="official" <?php echo e($business->verification_type == 'official' ? 'selected' : ''); ?>>👑 رسمي (Official)</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-3 pt-6">
                        <input type="checkbox" id="delivery_available" name="delivery_available" value="1" <?php echo e($business->delivery_available ? 'checked' : ''); ?> class="w-5 h-5 text-emerald-600 rounded">
                        <label for="delivery_available" class="text-sm font-bold text-slate-700 cursor-pointer">
                            <i class="fas fa-motorcycle ml-1"></i> تتوفر خدمة التوصيل
                        </label>
                    </div>
                </div>
            </div>
            
            
            <div class="bg-slate-50 rounded-xl p-5">
                <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-info-circle text-blue-600"></i>
                    المعلومات الأساسية
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="label">اسم المنشأة *</label>
                        <input type="text" name="title" value="<?php echo e(old('title', $business->title)); ?>" required class="input">
                    </div>
                    <div>
                        <label class="label">رقم الهاتف *</label>
                        <input type="text" name="phone" value="<?php echo e(old('phone', $business->phone)); ?>" required class="input" dir="ltr">
                    </div>
                    <div>
                        <label class="label">التصنيف *</label>
                        <select name="category_id" class="input">
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($category->id); ?>" <?php echo e($business->category_id == $category->id ? 'selected' : ''); ?>>
                                    <?php echo e($category->icon ?? '📁'); ?> <?php echo e($category->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="label">المحافظة *</label>
                        <select name="governorate_id" id="governorate-select" class="input" required>
                            <option value="">اختر المحافظة</option>
                            <?php $__currentLoopData = $governorates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($gov->id); ?>" <?php echo e($business->governorate_id == $gov->id ? 'selected' : ''); ?>>
                                    <?php echo e($gov->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="label">المنطقة *</label>
                        <select name="region_id" id="region-select" class="input" <?php echo e($business->governorate_id ? '' : 'disabled'); ?>>
                            <option value="">اختر المحافظة أولاً</option>
                            <?php if($business->governorate_id): ?>
                                <?php $__currentLoopData = $regions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $region): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($region->id); ?>" <?php echo e($business->region_id == $region->id ? 'selected' : ''); ?>>
                                        <?php echo e($region->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="label">العنوان بالتفصيل</label>
                        <input type="text" name="address_detail" value="<?php echo e(old('address_detail', $business->address_detail)); ?>" class="input">
                    </div>
                    <div class="md:col-span-2">
                        <label class="label">وصف المنشأة *</label>
                        <textarea name="description" rows="5" class="input"><?php echo e(old('description', $business->description)); ?></textarea>
                    </div>
                </div>
            </div>
            
            
            <div class="bg-slate-50 rounded-xl p-5">
                <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-share-alt text-sky-600"></i>
                    روابط التواصل الاجتماعي
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="label">فيسبوك</label>
                        <input type="url" name="facebook_url" value="<?php echo e(old('facebook_url', $business->facebook_url)); ?>" placeholder="https://facebook.com/..." class="input" dir="ltr">
                    </div>
                    <div>
                        <label class="label">انستغرام</label>
                        <input type="url" name="instagram_url" value="<?php echo e(old('instagram_url', $business->instagram_url)); ?>" placeholder="https://instagram.com/..." class="input" dir="ltr">
                    </div>
                    <div>
                        <label class="label">خرائط جوجل</label>
                        <input type="url" name="google_maps_url" value="<?php echo e(old('google_maps_url', $business->google_maps_url)); ?>" placeholder="https://maps.google.com/..." class="input" dir="ltr">
                    </div>
                </div>
            </div>
            
            
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
                    <?php $priceList = is_array($business->price_list) ? $business->price_list : []; ?>
                    <?php $__empty_1 = true; $__currentLoopData = $priceList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex gap-3 price-row items-center bg-white p-3 rounded-xl">
                        <input type="text" name="price_list[<?php echo e($index); ?>][name]" value="<?php echo e($item['name'] ?? ''); ?>" placeholder="اسم الخدمة" class="flex-1 input !bg-white">
                        <input type="text" name="price_list[<?php echo e($index); ?>][price]" value="<?php echo e($item['price'] ?? ''); ?>" placeholder="السعر (ل.س)" class="w-32 input !bg-white" dir="ltr">
                        <button type="button" class="remove-row bg-red-500/20 hover:bg-red-500 text-red-600 hover:text-white px-3 py-2.5 rounded-lg transition-all">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div id="no-prices-alert" class="text-center py-8 text-slate-500 border border-dashed border-slate-300 rounded-xl">
                        <i class="fas fa-box-open text-2xl mb-2 block"></i>
                        لا توجد أسعار مدخلة. أضف خدمات ومنتجات المنشأة.
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            
            <div class="bg-slate-50 rounded-xl p-5">
                <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-image text-purple-600"></i>
                    الصور
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="label">اللوجو الحالي</label>
                        <div class="mb-3">
                            <?php if($business->logo): ?>
                                <img src="<?php echo e(asset('public/' . $business->logo)); ?>" class="w-20 h-20 rounded-xl object-cover border border-slate-200" loading="lazy">
                            <?php else: ?>
                                <div class="w-20 h-20 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 text-xs">لا يوجد</div>
                            <?php endif; ?>
                        </div>
                        <input type="file" name="logo" accept="image/*" class="input !p-2">
                        <p class="text-[10px] text-slate-400 mt-1">JPG, PNG (max 2MB)</p>
                    </div>
                    <div>
                        <label class="label">صورة الغلاف الحالية</label>
                        <div class="mb-3">
                            <?php if($business->cover): ?>
                                <img src="<?php echo e(asset('public/' . $business->cover)); ?>" class="w-full h-24 rounded-xl object-cover border border-slate-200" loading="lazy">
                            <?php else: ?>
                                <div class="w-full h-24 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 text-xs">لا يوجد</div>
                            <?php endif; ?>
                        </div>
                        <input type="file" name="cover" accept="image/*" class="input !p-2">
                        <p class="text-[10px] text-slate-400 mt-1">JPG, PNG (max 3MB)</p>
                    </div>
                </div>
            </div>
            
            
            <div class="bg-slate-50 rounded-xl p-5">
                <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-map text-rose-600"></i>
                    الموقع الجغرافي
                </h3>
                <div id="map-picker"></div>
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="label">خط العرض (Latitude)</label>
                        <input type="text" name="latitude" id="lat-input" readonly value="<?php echo e(old('latitude', $business->latitude ?? '33.5138')); ?>" class="input bg-slate-100 font-mono">
                    </div>
                    <div>
                        <label class="label">خط الطول (Longitude)</label>
                        <input type="text" name="longitude" id="lng-input" readonly value="<?php echo e(old('longitude', $business->longitude ?? '36.2765')); ?>" class="input bg-slate-100 font-mono">
                    </div>
                </div>
            </div>
            
            
            <div class="flex gap-4">
                <button type="submit" class="btn-primary flex-1 py-3">
                    <i class="fas fa-save ml-2"></i> حفظ التغييرات
                </button>
                <a href="<?php echo e(route('admin.businesses.index')); ?>" class="btn-secondary flex-1 py-3 text-center">
                    <i class="fas fa-times ml-2"></i> إلغاء
                </a>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ========== المحافظة والمنطقة ==========
        const govSelect = document.getElementById('governorate-select');
        const regionSelect = document.getElementById('region-select');
        const currentRegionId = '<?php echo e($business->region_id); ?>';

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
            console.log('📡 جاري تحميل المناطق من:', url);

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
                console.log('✅ تم استلام البيانات:', data);
                
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
            console.log('🔄 تغيير المحافظة إلى:', governorateId);
            loadRegions(governorateId);
        });

        if (govSelect.value) {
            console.log('🔄 تحميل المناطق الأولية للمحافظة:', govSelect.value);
            loadRegions(govSelect.value, currentRegionId);
        }

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
        
        // ========== قائمة الأسعار ==========
        let priceIndex = <?php echo e(count($priceList ?? [])); ?>;
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
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u316371041/domains/aza-international.com/public_html/dlil/resources/views/admin-business-edit.blade.php ENDPATH**/ ?>