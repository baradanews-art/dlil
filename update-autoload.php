<?php
// update-autoload.php - احذف هذا الملف بعد الاستخدام
exec('cd ' . __DIR__ . ' && composer dump-autoload 2>&1', $output, $returnCode);
echo "<pre>";
print_r($output);
echo "</pre>";
if ($returnCode === 0) {
    echo "✅ تم تحديث autoloader بنجاح!";
} else {
    echo "❌ حدث خطأ. قد تحتاج لتشغيل composer يدوياً عبر SSH.";
}
echo "<br><a href='/'>العودة للموقع</a>";