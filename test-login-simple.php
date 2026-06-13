<?php
// ملف اختبار للتحقق من بيانات المستخدم فقط (دون تحميل Laravel)
$host = 'localhost';
$dbname = 'u316371041_dalil';
$username = 'u316371041_dalil';
$password = 'Omar13!#';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->prepare("SELECT id, name, email, password, is_admin, role FROM users WHERE email = 'admin@example.com'");
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "<h3>✅ المستخدم موجود في قاعدة البيانات</h3>";
        echo "<pre>";
        print_r($user);
        echo "</pre>";
        
        // التحقق من كلمة المرور باستخدام دالة Laravel (بدون تحميل التطبيق)
        if (password_verify('12345678', $user['password'])) {
            echo "<p style='color:green; font-weight:bold;'>✅ كلمة المرور '12345678' صحيحة!</p>";
            echo "<p>يمكنك الآن تسجيل الدخول من <a href='/dlil/login'>هذا الرابط</a>.</p>";
        } else {
            echo "<p style='color:red; font-weight:bold;'>❌ كلمة المرور '12345678' غير صحيحة!</p>";
        }
    } else {
        echo "<h3>❌ المستخدم admin@example.com غير موجود في قاعدة البيانات</h3>";
        echo "<p>يرجى تشغيل أمر SQL لإضافة المستخدم أولاً.</p>";
    }
} catch (PDOException $e) {
    echo "<h3>❌ فشل الاتصال بقاعدة البيانات</h3>";
    echo "الخطأ: " . $e->getMessage();
}
?>