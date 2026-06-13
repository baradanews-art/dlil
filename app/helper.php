<?php
// app/helper.php

if (!function_exists('public_upload_path')) {
    function public_upload_path($path = '') {
        // هذا المسار يجب أن يتطابق مع المسار الذي يعمل فيه test-upload.php
        // قد تحتاج لتعديل 'dlil' إذا كان الموقع في مجلد فرعي مختلف
        return base_path('dlil/uploads/' . $path);
    }
}