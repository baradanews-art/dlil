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
    
    public function businesses(): HasMany
    {
        return $this->hasMany(Business::class);
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
        return $this->businesses()->where('is_approved', 1)->count();
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