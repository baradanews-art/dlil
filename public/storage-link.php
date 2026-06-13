<?php
// public/storage-link.php
$target = __DIR__ . '/../storage/app/public';
$link = __DIR__ . '/storage';

if (!file_exists($link)) {
    symlink($target, $link);
    echo "✅ تم ربط مجلد التخزين بنجاح!";
} else {
    echo "⚠️ الرابط موجود بالفعل.";
}