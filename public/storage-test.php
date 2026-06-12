<?php
// storage-test.php
$path = 'ads/99KNNGAqq7S4Ru7evlrcCEMsehxN0jcR9zcSh5k4.jpg';
$fullPath = __DIR__ . '/../storage/app/public/' . $path;

echo "المسار الكامل: " . $fullPath . "<br>";
echo "الملف موجود؟ " . (file_exists($fullPath) ? 'نعم ✅' : 'لا ❌') . "<br>";

if (file_exists($fullPath)) {
    echo "<img src='data:image/jpeg;base64," . base64_encode(file_get_contents($fullPath)) . "' style='max-width:300px'>";
    echo "<br>✅ يمكن عرض الصورة عبر base64";
} else {
    echo "❌ الصورة غير موجودة في هذا المسار";
}