<?php
// run-setup.php - احذف هذا الملف فوراً بعد التشغيل
$basePath = __DIR__ . '/..';
chdir($basePath);

// 1. إنشاء الرابط الرمزي لـ storage
$target = __DIR__ . '/storage/app/public';
$link = __DIR__ . '/public/storage';
if (!file_exists($link) && file_exists($target)) {
    symlink($target, $link);
    echo "✅ تم إنشاء الرابط الرمزي storage <br>";
}

// 2. تشغيل migrations (إذا أردت)
// system('php artisan migrate --force');

// 3. تفعيل cache
system('php artisan config:cache');
system('php artisan route:cache');
system('php artisan view:cache');
echo "✅ تم تفعيل الكاش <br>";

// 4. clear cache just in case
system('php artisan optimize');

echo "✅ اكتمل الإعداد. <a href='/'>الذهاب للموقع</a>";