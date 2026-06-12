<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OfficialEntity extends Model
{
    protected $table = 'official_entities';
    
    protected $fillable = [
        'name', 'slug', 'type', 'sub_type', 'description', 'phone', 'hotline',
        'website', 'facebook_url', 'twitter_url', 'instagram_url', 'youtube_url', 'linkedin_url',
        'email', 'address', 'region_id', 'city_id', 'working_hours', 
        'logo', 'latitude', 'longitude', 'sort_order', 'is_active'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];
    
    protected static function boot()
    {
        parent::boot();
        
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
    // العلاقات
    // ============================================================
    
    public function region()
    {
        return $this->belongsTo(Location::class, 'region_id');
    }
    
    public function city()
    {
        return $this->belongsTo(Location::class, 'city_id');
    }
    
    // ============================================================
    // دوال الألوان والأيقونات
    // ============================================================
    
    public function getColor()
    {
        $colors = [
            'government' => 'green',
            'security' => 'red',
            'help' => 'blue',
        ];
        
        return $colors[$this->type] ?? 'slate';
    }
    
    public function getColorAttribute()
    {
        return $this->getColor();
    }
    
    public function getIcon()
    {
        $icons = [
            'government' => 'fa-landmark',
            'security' => 'fa-shield-alt',
            'help' => 'fa-hand-holding-heart',
        ];
        
        return $icons[$this->type] ?? 'fa-building';
    }
    
    public function getIconAttribute()
    {
        return $this->getIcon();
    }
    
    public function getBgGradient()
    {
        $gradients = [
            'government' => 'from-green-700 to-green-600',
            'security' => 'from-red-700 to-red-600',
            'help' => 'from-blue-700 to-blue-600',
        ];
        
        return $gradients[$this->type] ?? 'from-slate-700 to-slate-600';
    }
    
    // ============================================================
    // دوال الصور والروابط
    // ============================================================
    
    public function getLogoUrlAttribute()
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
    
    public function getMapUrlAttribute()
    {
        if ($this->latitude && $this->longitude) {
            return "https://www.google.com/maps?q={$this->latitude},{$this->longitude}";
        }
        return null;
    }
    
    // ============================================================
    // دوال التسميات
    // ============================================================
    
    public function getSubTypeLabelAttribute()
    {
        $labels = [
            'police_station' => 'مركز شرطة',
            'criminal_investigation' => 'مباحث',
            'drug_enforcement' => 'مكافحة مخدرات',
            'traffic' => 'مرور',
            'passports' => 'جوازات',
            'civil_defense' => 'دفاع مدني',
            'emergency' => 'طوارئ',
            'hospital' => 'مستشفى',
            'clinic' => 'مركز صحي',
            'charity' => 'جمعية خيرية',
            'social_care' => 'رعاية اجتماعية',
            'orphanage' => 'دور أيتام',
            'shelter' => 'مركز إيواء',
            'ministry' => 'وزارة',
            'directorate' => 'مديرية',
            'municipality' => 'بلدية',
            'government_office' => 'مكتب حكومي',
        ];
        
        return $labels[$this->sub_type] ?? $this->sub_type;
    }
}