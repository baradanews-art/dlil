<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
     * رابط الصورة مع fallback (محسن للاستضافة المشتركة)
     */
     
     
  public function getImageUrlAttribute()
{
    if (!empty($this->image_path)) {
        $fileName = basename($this->image_path);
        return url('/uploads/ads/' . $fileName);
    }
    return $this->getDefaultImage();
}
        
        // محاولة المسار في public/uploads/ads/
        if (file_exists(public_path($this->image_path))) {
            return asset($this->image_path);
        }
        
        // محاولة اسم الملف فقط
        $fileName = basename($this->image_path);
        if (file_exists(public_path('uploads/ads/' . $fileName))) {
            return asset('uploads/ads/' . $fileName);
        }
        
        // محاولة المسار القديم (للتوافق)
        if (file_exists(public_path('storage/' . $this->image_path))) {
            return asset('storage/' . $this->image_path);
        }
        
        return $this->getDefaultImage();
    }
    
    /**
     * صورة افتراضية للإعلان
     */
    private function getDefaultImage()
    {
        $title = urlencode($this->title ?? 'إعلان');
        
        if ($this->position == 'sidebar') {
            return "https://placehold.co/300x250/1e293b/10b981?text={$title}";
        }
        
        if ($this->position == 'home_top') {
            return "https://placehold.co/1200x200/1e293b/10b981?text={$title}";
        }
        
        return "https://placehold.co/728x90/1e293b/10b981?text={$title}";
    }

    /**
     * رابط الإعلان
     */
    public function getLinkAttribute()
    {
        return $this->link_url;
    }

    /**
     * سكوب لتصفية الإعلانات النشطة فقط
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    /**
     * سكوب حسب الموقع
     */
    public function scopePosition($query, $position)
    {
        return $query->where('position', $position);
    }
}