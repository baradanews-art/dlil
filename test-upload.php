<?php
// test-upload.php - احذف هذا الملف بعد الاختبار

$uploadDir = 'uploads/logos/';

if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
    echo "📁 تم إنشاء المجلد: " . $uploadDir . "<br>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_image'])) {
    $file = $_FILES['test_image'];
    $filename = time() . '_test_' . $file['name'];
    $destination = $uploadDir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        echo "✅ تم رفع الملف بنجاح!<br>";
        echo "📂 المسار: " . $destination . "<br>";
        echo "🔗 الرابط: <a href='" . $destination . "' target='_blank'>عرض الصورة</a><br>";
    } else {
        echo "❌ فشل رفع الملف<br>";
        echo "خطأ: " . error_get_last()['message'] ?? 'غير معروف';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>اختبار رفع الملفات</title>
</head>
<body>
    <h2>📤 اختبار رفع الصور</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="test_image" required>
        <button type="submit">رفع</button>
    </form>
    
    <hr>
    
    <h3>📁 الملفات الموجودة في مجلد uploads/logos/</h3>
    <?php
    if (is_dir($uploadDir)) {
        $files = scandir($uploadDir);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                echo "<a href='{$uploadDir}{$file}' target='_blank'>📷 {$file}</a><br>";
            }
        }
    }
    ?>
</body>
</html>