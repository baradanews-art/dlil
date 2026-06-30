<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Location extends Model
{
    protected $guarded = [];
    
    protected $appends = ['full_name', 'businesses_count'];
    
    // ============================================================
    // ✅ توليد Slug يدوياً
    // ============================================================
    protected static function booted()
    {
        static::creating(function ($location) {
            if (empty($location->slug)) {
                $baseSlug = Str::slug($location->name, '-', 'ar');
                $location->slug = $baseSlug ?: 'location-' . rand(1000, 9999);
            }
        });
    }
    
    // ============================================================
    // ✅ العلاقات
    // ============================================================
    
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'parent_id');
    }
    
    public function children(): HasMany
    {
        return $this->hasMany(Location::class, 'parent_id');
    }
    
    // ✅ العلاقة القديمة (للتعديلات المستقبلية - يمكن إزالتها)
    public function businesses(): HasMany
    {
        return $this->hasMany(Business::class, 'location_id');
    }
    
    // ✅ العلاقة بالمحافظات (المنشآت التي تتبع هذه المحافظة)
    public function governorateBusinesses(): HasMany
    {
        return $this->hasMany(Business::class, 'governorate_id');
    }
    
    // ✅ العلاقة بالمناطق (المنشآت التي تتبع هذه المنطقة)
    public function regionBusinesses(): HasMany
    {
        return $this->hasMany(Business::class, 'region_id');
    }
    
    // ============================================================
    // ✅ الـ Accessors
    // ============================================================
    
    public function getFullNameAttribute(): string
    {
        if ($this->parent) {
            return $this->parent->name . ' - ' . $this->name;
        }
        return $this->name;
    }
    
    public function getBusinessesCountAttribute(): int
    {
        // ✅ إصلاح: نبحث في governorate_id و region_id بدلاً من location_id
        $count = 0;
        
        // إذا كان هذا الموقع محافظة (ليس له أب)
        if ($this->parent_id === null) {
            $count = Business::where('governorate_id', $this->id)
                ->where('is_approved', 1)
                ->count();
        } else {
            // إذا كان هذا الموقع منطقة (له أب)
            $count = Business::where('region_id', $this->id)
                ->where('is_approved', 1)
                ->count();
        }
        
        return $count;
    }
    
    // ============================================================
    // ✅ النطاقات
    // ============================================================
    
    public function scopeGovernorates($query)
    {
        return $query->whereNull('parent_id');
    }
    
    public function scopeOrdered($query)
    {
        return $query->orderBy('name');
    }
}