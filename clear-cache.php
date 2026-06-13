<?php
// clear.php - احذف هذا الملف فوراً بعد الاستخدام

// 1. مسح مجلد cache الخاص بـ Laravel
$cachePaths = [
    __DIR__ . '/bootstrap/cache',
    __DIR__ . '/storage/framework/views',
    __DIR__ . '/storage/framework/cache',
];

foreach ($cachePaths as $path) {
    if (is_dir($path)) {
        $files = glob($path . '/*.php');
        if (is_array($files)) {
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }
    }
}

// 2. إعادة تعيين ملف .env يدوياً (للتأكد)
$envPath = __DIR__ . '/.env';
if (file_exists($envPath)) {
    $envContent = file_get_contents($envPath);
    // التأكد من أن APP_KEY موجودة
    if (!str_contains($envContent, 'APP_KEY=') || str_contains($envContent, 'APP_KEY= ')) {
        // يمكنك إضافة سطر APP_KEY هنا إذا كان مفقوداً
    }
}

echo "✅ تم مسح التخزين المؤقت بنجاح!";
echo "<br><a href='" . rtrim(($_ENV['APP_URL'] ?? '/'), '/') . "'>الذهاب إلى الرئيسية</a>";
?>