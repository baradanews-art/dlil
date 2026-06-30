<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title ?? 'الجهات الرسمية'); ?> | دليل سوريا التجاري</title>
    <meta name="description" content="<?php echo e($description ?? 'دليل الجهات الرسمية في سوريا'); ?>">
    <meta name="robots" content="index, follow">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        .entity-card {
            transition: all 0.3s ease;
        }
        .entity-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.2);
        }
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid #fff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 0.6s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-slate-50 font-sans antialiased">

    
    <div class="bg-gradient-to-r <?php echo e($bgColor ?? 'from-green-700 to-green-600'); ?> text-white py-16 px-4 text-center">
        <div class="max-w-4xl mx-auto">
            <div class="text-6xl mb-4">
                <i class="fas <?php echo e($icon ?? 'fa-landmark'); ?>"></i>
            </div>
            <h1 class="text-3xl md:text-4xl font-black"><?php echo e($title ?? 'الجهات الرسمية'); ?></h1>
            <p class="text-white/80 mt-2"><?php echo e($description ?? 'دليل شامل للجهات الرسمية في سوريا'); ?></p>
            <a href="<?php echo e(route('home')); ?>" class="inline-block mt-6 bg-white/20 hover:bg-white/30 rounded-xl px-6 py-2 text-sm transition-all">
                <i class="fas fa-arrow-right ml-2"></i> العودة للرئيسية
            </a>
        </div>
    </div>

    
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="bg-white rounded-2xl shadow-md p-6 mb-8">
            <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                <i class="fas fa-search text-emerald-600"></i> بحث وتصفية
            </h3>
            <form method="GET" action="<?php echo e(url()->current()); ?>" id="filterForm" class="space-y-4">
                
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                       placeholder="ابحث بالاسم، الوصف، أو العنوان، أو المحافظة/المنطقة..." 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    
                    <select name="city_id" id="city_id" class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm">
                        <option value="">جميع المحافظات</option>
                        <?php $__currentLoopData = $cities ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($city->id); ?>" <?php echo e(request('city_id') == $city->id ? 'selected' : ''); ?>><?php echo e($city->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    
                    
                    <select name="region_id" id="region_id" class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm">
                        <option value="">جميع المناطق</option>
                        <?php if($regions && count($regions) > 0): ?>
                            <?php $__currentLoopData = $regions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $region): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($region->id); ?>" <?php echo e(request('region_id') == $region->id ? 'selected' : ''); ?>><?php echo e($region->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </select>
                    
                    
                    <select name="sub_type" class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm">
                        <option value="">جميع الأنواع</option>
                        <?php $__currentLoopData = $subTypes ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e(request('sub_type') == $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                
                <div class="flex gap-3">
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-xl transition-all">
                        <i class="fas fa-search ml-1"></i> بحث
                    </button>
                    <a href="<?php echo e(url()->current()); ?>" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-6 py-2 rounded-xl transition-all">
                        <i class="fas fa-undo-alt ml-1"></i> إعادة ضبط
                    </a>
                </div>
            </form>
        </div>
        
        
        <div class="mb-4 flex justify-between items-center">
            <span class="text-sm text-slate-500">
                <i class="fas fa-building ml-1"></i> <?php echo e($entities->total()); ?> نتيجة
            </span>
            <?php if($entities->total() > 0): ?>
                <span class="text-xs text-slate-400">عرض <?php echo e($entities->firstItem()); ?> - <?php echo e($entities->lastItem()); ?> من <?php echo e($entities->total()); ?></span>
            <?php endif; ?>
        </div>
        
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__empty_1 = true; $__currentLoopData = $entities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <a href="<?php echo e(route('official.show', $entity->slug)); ?>" class="entity-card bg-white rounded-2xl shadow-md hover:shadow-xl transition-all p-6 block group">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 rounded-xl flex items-center justify-center transition-colors"
                         style="background-color: <?php echo e($entity->color == 'green' ? '#dcfce7' : ($entity->color == 'red' ? '#fee2e2' : '#dbeafe')); ?>">
                        <i class="fas <?php echo e($entity->icon); ?> text-2xl" style="color: <?php echo e($entity->color == 'green' ? '#059669' : ($entity->color == 'red' ? '#dc2626' : '#2563eb')); ?>"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 group-hover:text-emerald-600 transition-colors"><?php echo e($entity->name); ?></h3>
                        <?php if($entity->sub_type): ?>
                            <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full"><?php echo e($entity->sub_type_label); ?></span>
                        <?php endif; ?>
                        <?php if($entity->working_hours): ?>
                            <p class="text-xs text-slate-500 mt-1"><i class="far fa-clock ml-1"></i> <?php echo e($entity->working_hours); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                
                
                <?php if($entity->city || $entity->region): ?>
                    <p class="text-sm text-slate-500 flex items-center gap-2 mb-2">
                        <i class="fas fa-map-marker-alt text-emerald-600"></i>
                        <?php if($entity->region): ?>
                            <?php echo e($entity->region->name); ?>

                        <?php endif; ?>
                        <?php if($entity->city && $entity->region): ?>
                            , 
                        <?php endif; ?>
                        <?php if($entity->city): ?>
                            <?php echo e($entity->city->name); ?>

                        <?php endif; ?>
                    </p>
                <?php endif; ?>
                
                <?php if($entity->address): ?>
                    <p class="text-sm text-slate-600 flex items-center gap-2"><i class="fas fa-location-dot text-emerald-600"></i> <?php echo e(Str::limit($entity->address, 60)); ?></p>
                <?php endif; ?>
                
                <?php if($entity->phone): ?>
                    <p class="text-sm text-slate-600 flex items-center gap-2 mt-2"><i class="fas fa-phone-alt text-emerald-600"></i> <?php echo e($entity->phone); ?></p>
                <?php endif; ?>
                
                <?php if($entity->hotline): ?>
                    <p class="text-sm text-red-600 flex items-center gap-2 mt-1"><i class="fas fa-phone-alt"></i> طوارئ: <?php echo e($entity->hotline); ?></p>
                <?php endif; ?>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full text-center py-12 bg-white rounded-2xl">
                <i class="fas fa-building text-5xl text-slate-300 mb-3 block"></i>
                <p class="text-slate-500">لا توجد نتائج مطابقة لمعايير البحث</p>
                <p class="text-slate-400 text-sm mt-1">حاول تغيير معايير البحث أو كتابة اسم صحيح</p>
            </div>
            <?php endif; ?>
        </div>
        
        
        <?php if($entities->hasPages()): ?>
            <div class="mt-8">
                <?php echo e($entities->appends(request()->query())->links()); ?>

            </div>
        <?php endif; ?>
    </div>

    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const citySelect = document.getElementById('city_id');
            const regionSelect = document.getElementById('region_id');
            const filterForm = document.getElementById('filterForm');
            
            if (citySelect && regionSelect) {
                // عند تغيير المحافظة، نطلب المناطق عبر AJAX
                citySelect.addEventListener('change', function() {
                    const cityId = this.value;
                    
                    if (!cityId) {
                        regionSelect.innerHTML = '<option value="">جميع المناطق</option>';
                        regionSelect.disabled = false;
                        return;
                    }
                    
                    regionSelect.innerHTML = '<option value="">جاري تحميل المناطق...</option>';
                    regionSelect.disabled = true;
                    
                    fetch(`<?php echo e(url('/official/get-regions')); ?>/${cityId}`)
                        .then(response => response.json())
                        .then(data => {
                            regionSelect.innerHTML = '<option value="">جميع المناطق</option>';
                            if (data && data.length > 0) {
                                data.forEach(region => {
                                    const option = document.createElement('option');
                                    option.value = region.id;
                                    option.textContent = region.name;
                                    if (option.value == '<?php echo e(request('region_id')); ?>') {
                                        option.selected = true;
                                    }
                                    regionSelect.appendChild(option);
                                });
                                regionSelect.disabled = false;
                            } else {
                                regionSelect.innerHTML = '<option value="">لا توجد مناطق مسجلة</option>';
                                regionSelect.disabled = false;
                            }
                        })
                        .catch(error => {
                            console.error('خطأ في تحميل المناطق:', error);
                            regionSelect.innerHTML = '<option value="">حدث خطأ، حاول مرة أخرى</option>';
                            regionSelect.disabled = false;
                        });
                });
                
                // إذا كان هناك محافظة محددة مسبقاً (بعد البحث)، قم بتحميل المناطق فوراً
                if (citySelect.value) {
                    citySelect.dispatchEvent(new Event('change'));
                }
            }
        });
    </script>
</body>
</html><?php /**PATH /home/u316371041/domains/aza-international.com/public_html/dlil/resources/views/official/index.blade.php ENDPATH**/ ?>