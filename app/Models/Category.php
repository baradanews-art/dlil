<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'icon', 'type'];
    
    protected $appends = ['businesses_count'];
    
    // ============================================================
    // ✅ توليد Slug يدوياً
    // ============================================================
    protected static function booted()
    {
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name, '-', 'ar');
            }
        });
    }
    
    // ============================================================
    // ✅ العلاقات
    // ============================================================
    
    public function businesses(): HasMany
    {
        return $this->hasMany(Business::class);
    }
    
    public function approvedBusinesses(): HasMany
    {
        return $this->hasMany(Business::class)->where('is_approved', 1);
    }
    
    // ============================================================
    // ✅ الـ Accessors
    // ============================================================
    
    public function getBusinessesCountAttribute(): int
    {
        return $this->approvedBusinesses()->count();
    }
    
    // ============================================================
    // ✅ النطاقات
    // ============================================================
    
    public function scopeWithBusinessCount($query)
    {
        return $query->withCount(['businesses as approved_businesses_count' => function ($q) {
            $q->where('is_approved', 1);
        }]);
    }
    
    public function scopeOrdered($query)
    {
        return $query->orderBy('name');
    }
}