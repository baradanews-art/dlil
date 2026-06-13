<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Ad extends Model
{
    protected $table = 'ads';
    
    protected $fillable = [
        'position',
        'type',
        'title',
        'content',
        'image_path',
        'link_url',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $appends = ['image_url'];

    /**
     * ✅ الحصول على رابط الصورة (تم إصلاح الكود بالكامل)
     */
    public function getImageUrlAttribute(): string
    {
        // إذا كان هناك مسار صورة
        if (!empty($this->image_path)) {
            // محاولة المسار في public/storage
            if (Storage::disk('public')->exists($this->image_path)) {
                return Storage::url($this->image_path);
            }
            
            // محاولة المسار المباشر في public/uploads
            $publicPath = public_path('uploads/ads/' . basename($this->image_path));
            if (file_exists($publicPath)) {
                return asset('uploads/ads/' . basename($this->image_path));
            }
            
            // محاولة اسم الملف فقط
            $fileName = basename($this->image_path);
            if (Storage::disk('public')->exists('ads/' . $fileName)) {
                return Storage::url('ads/' . $fileName);
            }
        }
        
        // ✅ صورة افتراضية حسب المكان
        return $this->getDefaultImage();
    }
    
    /**
     * ✅ صورة افتراضية للإعلان
     */
    private function getDefaultImage(): string
    {
        $title = urlencode($this->title ?? 'إعلان');
        $colors = ['1e293b', '334155', '0f172a', '2d3748'];
        $color = $colors[array_rand($colors)];
        
        if ($this->position == 'sidebar') {
            return "https://placehold.co/400x300/{$color}/10b981?text={$title}";
        }
        
        if ($this->position == 'home_top') {
            return "https://placehold.co/1200x200/{$color}/10b981?text={$title}";
        }
        
        return "https://placehold.co/728x90/{$color}/10b981?text={$title}";
    }

    /**
     * ✅ رابط الإعلان
     */
    public function getLinkAttribute(): ?string
    {
        return $this->link_url;
    }

    /**
     * ✅ نطاق (Scope) لتصفية الإعلانات النشطة فقط
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    /**
     * ✅ نطاق (Scope) حسب الموقع
     */
    public function scopePosition($query, $position)
    {
        return $query->where('position', $position);
    }
}