<?php
// storage-link.php - احذف هذا الملف بعد الاستخدام
$target = __DIR__ . '/storage/app/public';
$link = __DIR__ . '/public/storage';

if (!file_exists($link) && file_exists($target)) {
    if (symlink($target, $link)) {
        echo "✅ تم إنشاء الرابط الرمزي storage بنجاح<br>";
    } else {
        echo "❌ فشل إنشاء الرابط الرمزي. قد تحتاج لصلاحيات أعلى.<br>";
    }
} else {
    echo "الرابط موجود مسبقاً أو الهدف غير موجود.<br>";
}

echo "<a href='/'>العودة للموقع</a>";