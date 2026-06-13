<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class OfficialEntity extends Model
{
    protected $table = 'official_entities';
    
    protected $fillable = [
        'name', 'slug', 'type', 'sub_type', 'description', 'phone', 'hotline',
        'website', 'facebook_url', 'twitter_url', 'instagram_url', 'youtube_url', 'linkedin_url',
        'email', 'address', 'city_id', 'region_id', 'working_hours',
        'logo', 'latitude', 'longitude', 'sort_order', 'is_active'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];
    
    protected $appends = ['color', 'icon', 'logo_url', 'type_label', 'sub_type_label'];
    
    // ============================================================
    // ✅ توليد Slug يدوياً
    // ============================================================
    protected static function booted()
    {
        static::creating(function ($entity) {
            if (empty($entity->slug)) {
                $entity->slug = Str::slug($entity->name, '-', 'ar');
            }
        });
        
        static::updating(function ($entity) {
            if ($entity->isDirty('name')) {
                $entity->slug = Str::slug($entity->name, '-', 'ar');
            }
        });
    }
    
    // ============================================================
    // ✅ العلاقات
    // ============================================================
    
    public function region(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'region_id');
    }
    
    public function city(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'city_id');
    }
    
    // ============================================================
    // ✅ الـ Accessors
    // ============================================================
    
    public function getColorAttribute(): string
    {
        return match ($this->type) {
            'government' => 'green',
            'security' => 'red',
            'help' => 'blue',
            default => 'slate',
        };
    }
    
    public function getIconAttribute(): string
    {
        return match ($this->type) {
            'government' => 'fa-landmark',
            'security' => 'fa-shield-alt',
            'help' => 'fa-hand-holding-heart',
            default => 'fa-building',
        };
    }
    
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'government' => 'حكومية',
            'security' => 'أمن ونجدة',
            'help' => 'مركز مساعدة',
            default => 'مؤسسة',
        };
    }
    
    public function getLogoUrlAttribute(): string
    {
        if ($this->logo && file_exists(public_path($this->logo))) {
            return asset($this->logo);
        }
        
        $defaults = [
            'government' => 'https://placehold.co/100x100/166534/ffffff?text=🏛️',
            'security' => 'https://placehold.co/100x100/991b1b/ffffff?text=🛡️',
            'help' => 'https://placehold.co/100x100/1e40af/ffffff?text=🤝',
        ];
        
        return $defaults[$this->type] ?? 'https://placehold.co/100x100/1e293b/ffffff?text=📌';
    }
    
    public function getSubTypeLabelAttribute(): string
    {
        $labels = [
            'police_station' => 'مركز شرطة',
            'criminal_investigation' => 'مباحث',
            'drug_enforcement' => 'مكافحة مخدرات',
            'traffic' => 'مرور',
            'passports' => 'جوازات',
            'civil_defense' => 'دفاع مدني',
            'emergency' => 'طوارئ',
            'ministry' => 'وزارة',
            'directorate' => 'مديرية',
            'municipality' => 'بلدية',
            'government_office' => 'مكتب حكومي',
            'hospital' => 'مستشفى',
            'clinic' => 'مركز صحي',
            'charity' => 'جمعية خيرية',
            'social_care' => 'رعاية اجتماعية',
        ];
        
        return $labels[$this->sub_type] ?? $this->sub_type;
    }
    
    // ============================================================
    // ✅ النطاقات
    // ============================================================
    
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}