<?php
// create-storage-link.php
$target = dirname(__DIR__) . '/storage/app/public';
$link = __DIR__ . '/storage';

if (file_exists($link)) {
    echo "⚠️ الرابط موجود بالفعل: " . $link . "<br>";
    echo '<a href="' . url('/storage') . '">اختبر الرابط</a>';
} else {
    if (symlink($target, $link)) {
        echo "✅ تم إنشاء الرابط بنجاح!<br>";
        echo "الهدف: " . $target . "<br>";
        echo "الرابط: " . $link . "<br>";
    } else {
        echo "❌ فشل إنشاء الرابط. قد تحتاج إلى صلاحيات إضافية.<br>";
    }
}

// إنشاء المجلدات إذا لم تكن موجودة
$folders = [
    dirname(__DIR__) . '/storage/app/public/logos',
    dirname(__DIR__) . '/storage/app/public/covers',
    dirname(__DIR__) . '/storage/app/public/ads'
];

foreach ($folders as $folder) {
    if (!file_exists($folder)) {
        mkdir($folder, 0755, true);
        echo "📁 تم إنشاء: " . $folder . "<br>";
    }
}

echo "<br>🎯 افتح الرابط: <a href='" . url('/storage') . "'>/storage</a>";