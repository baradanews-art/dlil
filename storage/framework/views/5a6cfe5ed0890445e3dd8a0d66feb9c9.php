

<?php $__env->startSection('seo'); ?>
    <?php
        if (!isset($seo)) {
            $seo = app(\App\Helpers\SeoHelper::class);
        }
        $seo->setTitle(\App\Models\Setting::get('site_name', 'دليل سوريا التجاري'))
            ->setDescription(\App\Models\Setting::get('site_description', 'دليلك الشامل للأعمال في سوريا ومحيطها'));
    ?>
    <?php echo $seo->render(); ?>

    
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "name": "<?php echo e(\App\Models\Setting::get('site_name', 'دليل سوريا التجاري')); ?>",
      "url": "<?php echo e(url('/')); ?>",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "<?php echo e(route('search')); ?>?search={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<section class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 min-h-[600px] flex items-center dark:from-black dark:to-slate-950">
    <div class="absolute inset-0 opacity-20 pointer-events-none">
        <div class="absolute top-20 right-10 w-80 h-80 bg-emerald-500 rounded-full filter blur-[100px] animate-pulse"></div>
        <div class="absolute bottom-20 left-10 w-96 h-96 bg-blue-600 rounded-full filter blur-[120px] animate-pulse delay-1000"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 py-20 text-center z-10 w-full">
        <div class="inline-flex items-center gap-2 bg-emerald-500/10 backdrop-blur-md rounded-full px-4 py-1.5 mb-6 border border-emerald-500/30">
            <span class="relative flex h-2.5 w-2.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
            </span>
            <span class="text-emerald-400 text-xs font-black tracking-wide">✨ المنصة التجارية الأحدث في سوريا</span>
        </div>
        
        <h1 class="text-4xl md:text-6xl lg:text-7xl font-black text-white tracking-tight leading-tight">
            <?php echo e(\App\Models\Setting::get('hero_title', 'دليلك الشامل للأعمال في سوريا')); ?>

        </h1>
        
        <p class="mt-6 text-base md:text-xl text-slate-300 max-w-3xl mx-auto font-medium">
            <?php echo e(\App\Models\Setting::get('hero_subtitle', 'أكبر دليل إلكتروني يضم آلاف المنشآت التجارية والخدمية مع تقييمات حقيقية')); ?>

        </p>
        
        <div class="mt-12 max-w-4xl mx-auto bg-white/10 backdrop-blur-xl p-3 rounded-3xl border border-white/10 shadow-2xl">
            <form action="<?php echo e(route('search')); ?>" method="GET" class="flex flex-col md:flex-row gap-2">
                <div class="relative flex-1">
                    <i class="fas fa-search absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="search" placeholder="اسم المنشأة، الخدمة، الكلمة الدلالية..." class="w-full bg-white dark:bg-slate-900 border-0 rounded-2xl py-4 pr-12 pl-4 text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-emerald-500 transition-all font-medium">
                </div>
                <div class="relative min-w-[180px]">
                    <i class="fas fa-map-marker-alt absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <select name="location" class="w-full bg-white dark:bg-slate-900 border-0 rounded-2xl py-4 pr-11 pl-4 text-slate-700 dark:text-slate-300 focus:ring-2 focus:ring-emerald-500 appearance-none font-medium">
                        <option value="">كل المحافظات</option>
                        <?php $__currentLoopData = $governorates ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($gov->slug); ?>"><?php echo e($gov->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-8 py-4 rounded-2xl transition-all duration-300 shadow-lg shadow-emerald-600/30 hover:scale-[1.02]">
                    <i class="fas fa-search ml-2"></i> ابحث الآن
                </button>
            </form>
        </div>

        <div class="mt-8 flex flex-wrap gap-4 justify-center">
            <a href="<?php echo e(route('business.create')); ?>" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm px-6 py-3.5 rounded-xl transition-all shadow-md">
                <i class="fas fa-plus-circle"></i> أضف منشأتك مجاناً
            </a>
            <a href="#businesses" class="inline-flex items-center gap-2 bg-white/5 hover:bg-white/10 text-white font-bold text-sm px-6 py-3.5 rounded-xl border border-white/10 transition-all">
                استكشف المنشآت <i class="fas fa-arrow-down"></i>
            </a>
        </div>
    </div>
    
    <div class="absolute bottom-0 left-0 right-0 pointer-events-none">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
            <path d="M0 64L60 69.3C120 75 240 85 360 80C480 75 600 53 720 48C840 43 960 53 1080 58.7C1200 64 1320 64 1380 64L1440 64V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0V64Z" class="fill-slate-50 dark:fill-slate-900"/>
        </svg>
    </div>
</section>


<section class="max-w-7xl mx-auto px-4 -mt-16 relative z-20">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="<?php echo e(route('official.government')); ?>" class="group relative overflow-hidden rounded-2xl shadow-xl transition-all duration-300 hover:scale-105 hover:shadow-2xl">
            <div class="absolute inset-0 bg-gradient-to-br from-green-700 to-green-800"></div>
            <div class="relative p-8 text-center text-white z-10">
                <div class="w-20 h-20 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform"><i class="fas fa-landmark text-3xl"></i></div>
                <h3 class="text-xl font-bold mb-2">مؤسسات حكومية</h3>
                <p class="text-green-100 text-sm">وزارات، دوائر، مديريات رسمية</p>
                <div class="mt-4 inline-flex items-center gap-2 text-sm bg-white/20 rounded-full px-4 py-1.5 group-hover:bg-white/30">استعرض <i class="fas fa-arrow-left text-xs"></i></div>
            </div>
        </a>
        <a href="<?php echo e(route('official.security')); ?>" class="group relative overflow-hidden rounded-2xl shadow-xl transition-all duration-300 hover:scale-105 hover:shadow-2xl">
            <div class="absolute inset-0 bg-gradient-to-br from-red-700 to-red-800"></div>
            <div class="relative p-8 text-center text-white z-10">
                <div class="w-20 h-20 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform"><i class="fas fa-shield-alt text-3xl"></i></div>
                <h3 class="text-xl font-bold mb-2">الأمن والنجدة</h3>
                <p class="text-red-100 text-sm">شرطة، دفاع مدني، إسعاف، طوارئ</p>
                <div class="mt-4 inline-flex items-center gap-2 text-sm bg-white/20 rounded-full px-4 py-1.5 group-hover:bg-white/30">استعرض <i class="fas fa-arrow-left text-xs"></i></div>
            </div>
        </a>
        <a href="<?php echo e(route('official.help')); ?>" class="group relative overflow-hidden rounded-2xl shadow-xl transition-all duration-300 hover:scale-105 hover:shadow-2xl">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-700 to-blue-800"></div>
            <div class="relative p-8 text-center text-white z-10">
                <div class="w-20 h-20 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform"><i class="fas fa-hand-holding-heart text-3xl"></i></div>
                <h3 class="text-xl font-bold mb-2">مراكز مساعدة</h3>
                <p class="text-blue-100 text-sm">مستشفيات، جمعيات خيرية، دعم اجتماعي</p>
                <div class="mt-4 inline-flex items-center gap-2 text-sm bg-white/20 rounded-full px-4 py-1.5 group-hover:bg-white/30">استعرض <i class="fas fa-arrow-left text-xs"></i></div>
            </div>
        </a>
    </div>
</section>


<?php if(isset($officialBusinesses) && count($officialBusinesses) > 0): ?>
<section class="max-w-7xl mx-auto px-4 py-16 bg-slate-50 dark:bg-slate-900 rounded-3xl my-8 shadow-sm">
    <div class="flex justify-between items-end mb-10">
        <div>
            <h2 class="text-2xl md:text-4xl font-black text-slate-900 dark:text-white flex items-center gap-3">
                <span class="p-2.5 bg-amber-500/10 text-amber-500 rounded-2xl"><i class="fas fa-crown"></i></span>
                منشآت موثقة رسمياً
            </h2>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-2">شركاء وجهات معتمدة وموثوقة بالكامل</p>
        </div>
        <a href="<?php echo e(route('search', ['verified' => 1])); ?>" class="text-emerald-600 dark:text-emerald-400 text-sm font-bold hover:underline flex items-center gap-1">عرض الكل <i class="fas fa-arrow-left"></i></a>
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php $__currentLoopData = $officialBusinesses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bus): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden group hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
            <div class="relative h-44 overflow-hidden bg-slate-100">
                <img src="<?php echo e(asset('public/' . $bus->cover)); ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" loading="lazy" alt="<?php echo e($bus->title); ?>" onerror="this.src='https://placehold.co/1200x400/0f172a/10b981?text=🏪'">
                <div class="absolute top-3 left-3">
                    <?php if($bus->verification_type == 'official'): ?>
                        <span class="bg-gradient-to-r from-amber-500 to-amber-600 text-white text-[11px] font-black px-3 py-1 rounded-xl shadow-md flex items-center gap-1"><i class="fas fa-award"></i> رسمي</span>
                    <?php else: ?>
                        <span class="bg-gradient-to-r from-blue-500 to-blue-600 text-white text-[11px] font-black px-3 py-1 rounded-xl shadow-md flex items-center gap-1"><i class="fas fa-check-circle"></i> موثق</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="p-5 flex-1 flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-slate-800 dark:text-white group-hover:text-emerald-600 transition-colors line-clamp-1 text-base"><?php echo e($bus->title); ?></h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 flex items-center gap-1"><i class="fas fa-map-marker-alt text-slate-400"></i> <?php echo e($bus->governorate->name ?? 'سوريا'); ?> <?php if($bus->region): ?> - <?php echo e($bus->region->name); ?> <?php endif; ?></p>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-700/60 flex justify-between items-center">
                    <div class="text-amber-400 text-xs flex gap-0.5">
                        <?php for($i=1;$i<=5;$i++): ?>
                            <i class="fas fa-star<?php echo e($i <= $bus->reviews_avg_rating ? '' : '-o'); ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <a href="<?php echo e(route('business.show', $bus->slug)); ?>" class="inline-flex items-center gap-1 bg-slate-50 dark:bg-slate-700 px-3 py-1.5 rounded-xl text-emerald-600 dark:text-emerald-400 text-xs font-bold hover:bg-emerald-50 dark:hover:bg-emerald-950/30 transition-all">تفاصيل <i class="fas fa-chevron-left text-[9px]"></i></a>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</section>
<?php endif; ?>


<section id="businesses" class="max-w-7xl mx-auto px-4 py-12">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h2 class="text-2xl md:text-4xl font-black text-slate-800 dark:text-white flex items-center gap-3">
                <span class="p-2.5 bg-emerald-500/10 text-emerald-600 rounded-2xl"><i class="fas fa-clock"></i></span>
                أحدث المنشآت المضافة
            </h2>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-2">انضموا إلينا حديثاً في الدليل</p>
        </div>
        <a href="<?php echo e(route('search')); ?>" class="text-emerald-600 text-sm font-bold hover:underline">عرض الكل <i class="fas fa-arrow-left mr-1"></i></a>
    </div>
    
    <div id="businesses-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <?php $__currentLoopData = $featuredBusinesses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bus): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/40 overflow-hidden group hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
            <div class="relative h-48 overflow-hidden bg-slate-100">
                <img src="<?php echo e(asset('public/' . $bus->cover)); ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" loading="lazy" alt="<?php echo e($bus->title); ?>" onerror="this.src='https://placehold.co/1200x400/0f172a/10b981?text=🏪'">
                <?php if(isset($bus->delivery_available) && $bus->delivery_available): ?>
                    <span class="absolute bottom-3 left-3 bg-emerald-500 text-white text-[10px] font-black px-2.5 py-1 rounded-xl shadow-sm">🛵 توصيل متاح</span>
                <?php endif; ?>
            </div>
            <div class="p-5 flex-1 flex flex-col justify-between">
                <div>
                    <div class="flex items-start gap-3">
                        <img src="<?php echo e(asset('public/' . $bus->logo)); ?>" class="w-11 h-11 rounded-xl object-cover border border-slate-100 dark:border-slate-700 shadow-inner bg-white" alt="<?php echo e($bus->title); ?>" loading="lazy" onerror="this.src='https://placehold.co/200x200/1e293b/10b981?text=🏪'">
                        <div class="flex-1 overflow-hidden">
                            <h3 class="font-bold text-slate-800 dark:text-white line-clamp-1 group-hover:text-emerald-600 transition-colors text-base"><?php echo e($bus->title); ?></h3>
                            <p class="text-xs text-slate-500 mt-0.5 flex items-center gap-1"><i class="fas fa-folder text-slate-300"></i> <?php echo e($bus->category->name ?? 'عام'); ?></p>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-3.5 line-clamp-2 leading-relaxed"><?php echo e(Str::limit($bus->description, 75)); ?></p>
                </div>
                
                <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-700/60 flex justify-between items-center">
                    <div class="text-amber-400 text-xs flex gap-0.5">
                        <?php for($i=1;$i<=5;$i++): ?>
                            <i class="fas fa-star<?php echo e($i <= $bus->reviews_avg_rating ? '' : '-o'); ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <a href="<?php echo e(route('business.show', $bus->slug)); ?>" class="text-emerald-600 dark:text-emerald-400 text-xs font-bold hover:underline flex items-center gap-0.5">التفاصيل <i class="fas fa-chevron-left text-[9px]"></i></a>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    
    <div id="loading-spinner" class="hidden justify-center my-6">
        <div class="animate-spin rounded-full h-10 w-10 border-4 border-emerald-500 border-t-transparent"></div>
    </div>

    <?php if($featuredBusinesses->hasMorePages()): ?>
    <div class="text-center mt-12">
        <button id="load-more" type="button" class="inline-flex items-center gap-2 bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-white hover:border-emerald-600 hover:text-emerald-600 font-bold px-8 py-3.5 rounded-2xl transition-all shadow-sm">
            <i class="fas fa-sync-alt"></i> اكتشف المزيد من المنشآت
        </button>
    </div>
    <?php endif; ?>
</section>


<?php if(isset($topRatedBusinesses) && count($topRatedBusinesses) > 0): ?>
<section class="bg-slate-100 dark:bg-slate-900/50 py-16 my-8">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h2 class="text-2xl md:text-4xl font-black text-slate-800 dark:text-white flex items-center gap-3">
                    <span class="p-2.5 bg-orange-500/10 text-orange-500 rounded-2xl"><i class="fas fa-fire"></i></span>
                    المنشآت الأعلى تقييماً
                </h2>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-2">الوجهات الأفضل بناءً على تجارب حقيقية</p>
            </div>
            <div class="flex gap-2 self-end">
                <button type="button" class="trending-prev w-11 h-11 bg-white dark:bg-slate-800 rounded-2xl shadow-sm hover:bg-emerald-600 hover:text-white transition-all"><i class="fas fa-chevron-right"></i></button>
                <button type="button" class="trending-next w-11 h-11 bg-white dark:bg-slate-800 rounded-2xl shadow-sm hover:bg-emerald-600 hover:text-white transition-all"><i class="fas fa-chevron-left"></i></button>
            </div>
        </div>
        
        <div class="swiper trending-swiper overflow-hidden px-1">
            <div class="swiper-wrapper">
                <?php $__currentLoopData = $topRatedBusinesses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bus): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="swiper-slide">
                    <div class="bg-white dark:bg-slate-800 rounded-3xl overflow-hidden shadow-sm border border-slate-100 dark:border-slate-700/40 group flex flex-col justify-between h-full">
                        <div class="relative h-48 overflow-hidden">
                            <img src="<?php echo e(asset('public/' . $bus->cover)); ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" loading="lazy" alt="<?php echo e($bus->title); ?>" onerror="this.src='https://placehold.co/1200x400/0f172a/10b981?text=🏪'">
                            <div class="absolute bottom-3 left-3 bg-black/60 backdrop-blur-md rounded-xl px-2.5 py-1 flex items-center gap-1.5">
                                <i class="fas fa-star text-amber-400 text-xs"></i> 
                                <span class="text-white text-xs font-black"><?php echo e(number_format($bus->reviews_avg_rating, 1)); ?></span>
                            </div>
                        </div>
                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="font-bold text-slate-800 dark:text-white text-base line-clamp-1 group-hover:text-emerald-600 transition-colors"><?php echo e($bus->title); ?></h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 line-clamp-2"><?php echo e(Str::limit($bus->description, 80)); ?></p>
                            </div>
                            <div class="flex justify-between items-center mt-5 pt-4 border-t border-slate-100 dark:border-slate-700/60">
                                <span class="text-xs text-slate-500 flex items-center gap-1"><i class="fas fa-map-marker-alt"></i> <?php echo e($bus->governorate->name ?? 'سوريا'); ?> <?php if($bus->region): ?> - <?php echo e($bus->region->name); ?> <?php endif; ?></span>
                                <a href="<?php echo e(route('business.show', $bus->slug)); ?>" class="text-emerald-600 dark:text-emerald-400 text-xs font-bold hover:underline">تفاصيل <i class="fas fa-arrow-left mr-1"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>


<?php if(isset($recommendedBusinesses) && count($recommendedBusinesses) > 0): ?>
<section class="max-w-7xl mx-auto px-4 py-12">
    <div class="bg-gradient-to-br from-emerald-600 via-emerald-700 to-teal-800 rounded-3xl p-6 md:p-10 shadow-xl relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white rounded-full filter blur-xl"></div>
        </div>
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 relative z-10 mb-8">
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-white flex items-center gap-2.5">
                    <i class="fas fa-lightbulb text-amber-300"></i> اقتراحات وتوصيات اليوم
                </h2>
                <p class="text-emerald-100 text-sm mt-1.5">أماكن ومنشآت منتقاة لك بعناية</p>
            </div>
            <div class="flex gap-2">
                <button type="button" class="rec-prev w-10 h-10 bg-white/10 hover:bg-white text-white hover:text-emerald-900 rounded-xl transition-all"><i class="fas fa-chevron-right"></i></button>
                <button type="button" class="rec-next w-10 h-10 bg-white/10 hover:bg-white text-white hover:text-emerald-900 rounded-xl transition-all"><i class="fas fa-chevron-left"></i></button>
            </div>
        </div>
        
        <div class="swiper rec-swiper overflow-hidden relative z-10">
            <div class="swiper-wrapper">
                <?php $__currentLoopData = $recommendedBusinesses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bus): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="swiper-slide">
                    <a href="<?php echo e(route('business.show', $bus->slug)); ?>" class="block bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm hover:shadow-xl transition-all hover:scale-[1.01]">
                        <div class="flex items-center gap-4">
                            <img src="<?php echo e(asset('public/' . $bus->logo)); ?>" class="w-16 h-16 rounded-xl object-cover border border-slate-100 bg-slate-50" loading="lazy" onerror="this.src='https://placehold.co/200x200/1e293b/10b981?text=🏪'">
                            <div class="overflow-hidden flex-1">
                                <h3 class="font-bold text-slate-900 dark:text-white text-sm truncate"><?php echo e($bus->title); ?></h3>
                                <p class="text-xs text-slate-500 mt-1 truncate"><?php echo e($bus->category->name ?? 'تصنيف عام'); ?></p>
                                <div class="text-amber-400 text-[11px] mt-1.5 flex gap-0.5">
                                    <?php for($i=1;$i<=5;$i++): ?><i class="fas fa-star<?php echo e($i <= ($bus->reviews_avg_rating ?? 0) ? '' : '-o'); ?>"></i><?php endfor; ?>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>


<?php if(isset($latestReviews) && count($latestReviews) > 0): ?>
<section class="bg-slate-950 py-16 my-8 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 relative z-10">
        <div class="text-center mb-12">
            <h2 class="text-2xl md:text-4xl font-black text-white flex items-center justify-center gap-3">
                <i class="fas fa-quote-right text-emerald-500 text-xl"></i> ماذا يقول زوارنا؟
            </h2>
            <p class="text-slate-400 text-sm mt-2">آراء وتجارب حقيقية من مجتمعنا</p>
        </div>
        
        <div class="swiper reviews-swiper overflow-hidden">
            <div class="swiper-wrapper">
                <?php $__currentLoopData = $latestReviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="swiper-slide">
                    <div class="bg-white/5 backdrop-blur-md rounded-2xl p-6 border border-white/5 h-full flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 bg-emerald-600/20 text-emerald-400 rounded-full flex items-center justify-center font-bold text-sm">
                                    <?php echo e(Str::substr($review->reviewer_name ?? 'ز', 0, 1)); ?>

                                </div>
                                <div>
                                    <div class="font-bold text-white text-sm"><?php echo e($review->reviewer_name ?? 'زائر'); ?></div>
                                    <div class="text-amber-400 text-[10px] mt-0.5 flex gap-0.5">
                                        <?php for($i=1;$i<=5;$i++): ?>
                                            <i class="fas fa-star <?php echo e($i <= $review->rating ? 'text-amber-400' : 'text-white/20'); ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </div>
                            <p class="text-slate-300 text-sm leading-relaxed italic">" <?php echo e(Str::limit($review->comment, 120)); ?> "</p>
                        </div>
                        <div class="mt-4 pt-4 border-t border-white/5 text-[11px] text-slate-500 flex justify-between items-center">
                            <span><?php echo e($review->created_at ? $review->created_at->diffForHumans() : ''); ?></span>
                            <a href="<?php echo e(route('business.show', $review->business->slug ?? '#')); ?>" class="text-emerald-400 hover:underline truncate max-w-[150px]">@ <span class="font-bold"><?php echo e($review->business->title ?? ''); ?></span></a>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="swiper-pagination !static mt-8"></div>
        </div>
    </div>
</section>
<?php endif; ?>


<section class="max-w-7xl mx-auto px-4 py-12">
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm p-8 border border-slate-100 dark:border-slate-700/50">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div class="stat-item" data-count="<?php echo e($stats['total'] ?? 0); ?>">
                <div class="w-14 h-14 bg-emerald-500/10 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-3 text-xl"><i class="fas fa-store"></i></div>
                <div class="stat-number text-2xl md:text-3xl font-black text-slate-800 dark:text-white">0</div>
                <div class="text-xs text-slate-500 mt-1.5 font-medium">منشأة تجارية</div>
            </div>
            <div class="stat-item" data-count="<?php echo e($stats['categories'] ?? 0); ?>">
                <div class="w-14 h-14 bg-blue-500/10 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-3 text-xl"><i class="fas fa-th-large"></i></div>
                <div class="stat-number text-2xl md:text-3xl font-black text-slate-800 dark:text-white">0</div>
                <div class="text-xs text-slate-500 mt-1.5 font-medium">تصنيف خدمي</div>
            </div>
            <div class="stat-item" data-count="<?php echo e($stats['reviews'] ?? 0); ?>">
                <div class="w-14 h-14 bg-amber-500/10 text-amber-500 rounded-2xl flex items-center justify-center mx-auto mb-3 text-xl"><i class="fas fa-star"></i></div>
                <div class="stat-number text-2xl md:text-3xl font-black text-slate-800 dark:text-white">0</div>
                <div class="text-xs text-slate-500 mt-1.5 font-medium">تقييم حقيقي</div>
            </div>
            <div class="stat-item" data-count="<?php echo e($stats['government_entities'] ?? 0); ?>">
                <div class="w-14 h-14 bg-green-500/10 text-green-600 rounded-2xl flex items-center justify-center mx-auto mb-3 text-xl"><i class="fas fa-landmark"></i></div>
                <div class="stat-number text-2xl md:text-3xl font-black text-slate-800 dark:text-white">0</div>
                <div class="text-xs text-slate-500 mt-1.5 font-medium">مؤسسة رسمية</div>
            </div>
        </div>
    </div>
</section>


<section class="max-w-7xl mx-auto px-4 py-12">
    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-slate-800 dark:to-slate-800/50 rounded-3xl p-8 text-center border border-emerald-100 dark:border-emerald-800/30">
        <i class="fas fa-heart text-emerald-500 text-4xl mb-3 block"></i>
        <h3 class="text-2xl font-black text-slate-800 dark:text-white">ساهم في تحسين الدليل</h3>
        <p class="text-slate-600 dark:text-slate-400 mt-2 max-w-2xl mx-auto">هل جربت إحدى هذه المنشآت؟ شاركنا تجربتك بتقييم صادق يساعد الآخرين في اختيار الأفضل.</p>
        <div class="flex flex-wrap gap-4 justify-center mt-6">
            <a href="#businesses" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-6 py-3 rounded-xl transition-all shadow-md">
                <i class="fas fa-star ml-2"></i> قيم منشأة
            </a>
            <a href="<?php echo e(route('business.create')); ?>" class="bg-white dark:bg-slate-700 border-2 border-emerald-600 text-emerald-600 dark:text-white hover:bg-emerald-50 dark:hover:bg-slate-600 font-bold px-6 py-3 rounded-xl transition-all">
                <i class="fas fa-plus-circle ml-2"></i> أضف منشأتك
            </a>
        </div>
        <p class="text-xs text-slate-400 mt-6">تقييماتك تساعد آلاف المستخدمين في اختيار أفضل الخدمات</p>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Counter Animation
        const statItems = document.querySelectorAll('.stat-item');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const element = entry.target;
                    const target = parseFloat(element.dataset.count);
                    const numberEl = element.querySelector('.stat-number');
                    let current = 0;
                    const isFloat = target % 1 !== 0;
                    const increment = target / 40;
                    const timer = setInterval(() => {
                        current += increment;
                        if (current >= target) {
                            numberEl.textContent = isFloat ? target.toFixed(1) : Math.round(target).toLocaleString();
                            clearInterval(timer);
                        } else {
                            numberEl.textContent = isFloat ? current.toFixed(1) : Math.floor(current).toLocaleString();
                        }
                    }, 25);
                    observer.unobserve(element);
                }
            });
        }, { threshold: 0.3 });
        statItems.forEach(item => observer.observe(item));
        
        // Swiper configurations
        const swiperOptions = {
            slidesPerView: 1,
            spaceBetween: 24,
            loop: true,
            autoplay: { delay: 4000, disableOnInteraction: false },
            breakpoints: { 640: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } }
        };

        if (document.querySelector('.trending-swiper')) {
            new Swiper('.trending-swiper', {
                ...swiperOptions,
                navigation: { nextEl: '.trending-next', prevEl: '.trending-prev' }
            });
        }
        if (document.querySelector('.reviews-swiper')) {
            new Swiper('.reviews-swiper', {
                ...swiperOptions,
                pagination: { el: '.swiper-pagination', clickable: true },
                breakpoints: { 768: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } }
            });
        }
        if (document.querySelector('.rec-swiper')) {
            new Swiper('.rec-swiper', {
                ...swiperOptions,
                navigation: { nextEl: '.rec-next', prevEl: '.rec-prev' },
                breakpoints: { 640: { slidesPerView: 2 }, 768: { slidesPerView: 3 }, 1024: { slidesPerView: 4 } }
            });
        }
        
        // Load More AJAX
        let currentPage = 2;
        const loadMoreBtn = document.getElementById('load-more');
        const container = document.getElementById('businesses-container');
        const spinner = document.getElementById('loading-spinner');
        
        if (loadMoreBtn && container) {
            loadMoreBtn.addEventListener('click', async function() {
                loadMoreBtn.disabled = true;
                if(spinner) spinner.classList.remove('hidden');
                if(spinner) spinner.classList.add('flex');
                
                const url = new URL(window.location.href);
                url.searchParams.set('page', currentPage);
                
                try {
                    const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    if (!response.ok) throw new Error('Network response was not ok');
                    
                    const html = await response.text();
                    if(html.trim() === '') {
                        loadMoreBtn.remove();
                        return;
                    }
                    
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = html;
                    const newCardsContainer = tempDiv.querySelector('#businesses-container');
                    const newCardsHtml = newCardsContainer ? newCardsContainer.innerHTML : html;
                    container.insertAdjacentHTML('beforeend', newCardsHtml);
                    currentPage++;
                    
                    if (!html.includes('load-more')) {
                        loadMoreBtn.remove();
                    }
                } catch(e) { 
                    console.error('Error loading more businesses:', e); 
                } finally { 
                    loadMoreBtn.disabled = false; 
                    if(spinner) spinner.classList.remove('flex');
                    if(spinner) spinner.classList.add('hidden');
                }
            });
        }
    });

    function toggleBookmark(id) {
        let bookmarks = JSON.parse(localStorage.getItem('saved_businesses')) || [];
        if (bookmarks.includes(id)) {
            bookmarks = bookmarks.filter(bId => bId !== id);
            alert('تم الإزالة من المفضلة');
        } else {
            bookmarks.push(id);
            alert('تم الحفظ في المفضلة بنجاح!');
        }
        localStorage.setItem('saved_businesses', JSON.stringify(bookmarks));
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u316371041/domains/aza-international.com/public_html/dlil/resources/views/index.blade.php ENDPATH**/ ?>