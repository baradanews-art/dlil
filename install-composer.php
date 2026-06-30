<?php
// تنفيذ أمر Composer عبر PHP
exec('composer require erag/laravel-pwa 2>&1', $output, $returnCode);
echo "<pre>";
print_r($output);
echo "</pre>";
echo $returnCode === 0 ? "✅ تم التثبيت بنجاح!" : "❌ حدث خطأ أثناء التثبيت";