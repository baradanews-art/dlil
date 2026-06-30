<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;

trait ImageUploadTrait
{
    /**
     * رفع الصورة - مسار مخصص لمجلد dlil
     */
    public function uploadImage(UploadedFile $file, string $folder = 'images', int $width = null, int $height = null, int $quality = 80): ?string
    {
        try {
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // ✅ المسار الصحيح لمجلد dlil
            $relativePath = "uploads/{$folder}/" . $filename;
            
            // ✅ استخدام المسار المباشر لمجلد dlil
            $basePath = $_SERVER['DOCUMENT_ROOT'] . '/dlil/public';
            $fullPath = $basePath . '/' . $relativePath;
            $directory = $basePath . '/uploads/' . $folder;

            // إنشاء المجلد إذا لم يكن موجوداً
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            // نقل الملف
            $file->move($directory, $filename);

            return $relativePath;

        } catch (\Exception $e) {
            // محاولة بديلة باستخدام public_path()
            try {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $relativePath = "uploads/{$folder}/" . $filename;
                $fullPath = public_path($relativePath);
                $directory = public_path("uploads/{$folder}");

                if (!is_dir($directory)) {
                    mkdir($directory, 0755, true);
                }

                $file->move($directory, $filename);
                return $relativePath;
            } catch (\Exception $e2) {
                return null;
            }
        }
    }

    /**
     * حذف صورة قديمة
     */
    public function deleteOldImage(?string $path): void
    {
        if (empty($path)) {
            return;
        }

        // ✅ التحقق من وجود الملف في كلا المسارين
        $pathsToCheck = [
            $_SERVER['DOCUMENT_ROOT'] . '/dlil/public/' . $path,
            public_path($path)
        ];

        foreach ($pathsToCheck as $fullPath) {
            if (file_exists($fullPath) && is_file($fullPath)) {
                unlink($fullPath);
                break;
            }
        }
    }

    /**
     * رفع ملف عام
     */
    public function uploadFile(UploadedFile $file, string $folder = 'files'): ?string
    {
        try {
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $relativePath = "uploads/{$folder}/" . $filename;
            
            $basePath = $_SERVER['DOCUMENT_ROOT'] . '/dlil/public';
            $directory = $basePath . '/uploads/' . $folder;

            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            $file->move($directory, $filename);
            return $relativePath;

        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * حذف ملف عام
     */
    public function deleteOldFile(?string $path): void
    {
        $this->deleteOldImage($path);
    }
}