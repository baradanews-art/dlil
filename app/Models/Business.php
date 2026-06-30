<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Business extends Model
{
    protected $table = 'businesses';
    
    protected $guarded = [];
    
    protected $casts = [
        'price_list' => 'array',
        'delivery_available' => 'boolean',
        'is_approved' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'views_count' => 'integer',
        'rating_avg' => 'decimal:1',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    protected $appends = [
        'status',
        'verification_type_label',
        'rating_avg',
        'reviews_count',
        'logo_url',
        'cover_url',
        'excerpt',
        'full_location',
        'governorate_name',
        'region_name',
    ];
    
    protected static function booted()
    {
        static::creating(function ($business) {
            if (empty($business->slug)) {
                $business->slug = static::generateUniqueSlug($business->title);
            }
        });
        
        static::updating(function ($business) {
            if ($business->isDirty('title') && empty($business->slug)) {
                $business->slug = static::generateUniqueSlug($business->title);
            }
        });
        
        static::saved(function () {
            Cache::forget('featured_businesses');
            Cache::forget('top_rated_businesses');
            Cache::forget('categories_with_count');
        });
        
        static::deleted(function () {
            Cache::forget('featured_businesses');
            Cache::forget('top_rated_businesses');
            Cache::forget('categories_with_count');
        });
    }
    
    public static function generateUniqueSlug($title)
    {
        $slug = Str::slug($title, '-', 'ar');
        $slug = $slug ?: 'business-' . rand(1000, 9999);
        $count = static::where('slug', 'LIKE', "{$slug}%")->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }
    
    // ============================================================
    // ✅ العلاقات (Relationships)
    // ============================================================
    
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    
    // ✅ العلاقة بالمحافظة (الأم)
    public function governorate(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'governorate_id');
    }
    
    // ✅ العلاقة بالمنطقة (الفرعية)
    public function region(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'region_id');
    }
    
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->latest();
    }
    
    // ============================================================
    // ✅ الـ Accessors
    // ============================================================
    
    public function getGovernorateNameAttribute(): string
    {
        return $this->governorate->name ?? 'غير محدد';
    }
    
    public function getRegionNameAttribute(): string
    {
        return $this->region->name ?? 'غير محدد';
    }
    
    public function getFullLocationAttribute(): string
    {
        $parts = [];
        if ($this->governorate) {
            $parts[] = $this->governorate->name;
        }
        if ($this->region) {
            $parts[] = $this->region->name;
        }
        return implode(' - ', $parts) ?: 'سوريا';
    }
    
    public function getLogoUrlAttribute()
    {
        if (!empty($this->logo) && file_exists(public_path($this->logo))) {
            return asset($this->logo);
        }
        return $this->getDefaultLogo();
    }
    
    public function getCoverUrlAttribute()
    {
        if (!empty($this->cover) && file_exists(public_path($this->cover))) {
            return asset($this->cover);
        }
        return $this->getDefaultCover();
    }
    
    private function getDefaultLogo()
    {
        $defaultLogoPath = public_path('uploads/logos/default.png');
        if (file_exists($defaultLogoPath)) {
            return asset('uploads/logos/default.png');
        }
        $text = urlencode(substr($this->title ?? 'منشأة', 0, 2));
        return "https://placehold.co/200x200/1e293b/10b981?text={$text}";
    }
    
    private function getDefaultCover()
    {
        $defaultCoverPath = public_path('uploads/covers/default.jpg');
        if (file_exists($defaultCoverPath)) {
            return asset('uploads/covers/default.jpg');
        }
        $categoryName = urlencode($this->category->name ?? 'منشأة');
        return "https://placehold.co/1200x400/0f172a/10b981?text={$categoryName}";
    }
    
    public function getStatusAttribute(): string
    {
        return $this->is_approved ? 'approved' : 'pending';
    }
    
    public function getVerificationTypeLabelAttribute(): string
    {
        return match ($this->verification_type) {
            'none' => 'غير موثق',
            'verified' => 'موثق ✓',
            'official' => 'رسمي معتمد 👑',
            default => 'غير موثق'
        };
    }
    
    public function getRatingAvgAttribute(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }
    
    public function getReviewsCountAttribute(): int
    {
        return $this->reviews()->count();
    }
    
    public function getExcerptAttribute(): string
    {
        return Str::limit(strip_tags($this->description), 120);
    }
    
    // ============================================================
    // ✅ النطاقات (Scopes)
    // ============================================================
    
    public function scopeApproved($query)
    {
        return $query->where('is_approved', 1);
    }
    
    public function scopePending($query)
    {
        return $query->where('is_approved', 0);
    }
    
    public function scopeVerified($query)
    {
        return $query->where('verification_type', 'verified');
    }
    
    public function scopeOfficial($query)
    {
        return $query->where('verification_type', 'official');
    }
    
    public function scopeDeliveryAvailable($query)
    {
        return $query->where('delivery_available', 1);
    }
    
    /**
     * ✅ البحث النصي
     */
    public function scopeSearch($query, $term)
    {
        if (empty($term)) return $query;
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'LIKE', "%{$term}%")
              ->orWhere('description', 'LIKE', "%{$term}%")
              ->orWhere('phone', 'LIKE', "%{$term}%")
              ->orWhere('address_detail', 'LIKE', "%{$term}%");
        });
    }
    
    /**
     * ✅ فلترة حسب المحافظة
     */
    public function scopeInGovernorate($query, $governorateId)
    {
        if (empty($governorateId)) return $query;
        return $query->where('governorate_id', $governorateId);
    }
    
    /**
     * ✅ فلترة حسب المنطقة
     */
    public function scopeInRegion($query, $regionId)
    {
        if (empty($regionId)) return $query;
        return $query->where('region_id', $regionId);
    }
    
    public function getSimilarBusinesses(int $limit = 4)
    {
        return self::approved()
            ->where('category_id', $this->category_id)
            ->where('id', '!=', $this->id)
            ->with(['category', 'governorate', 'region'])
            ->latest()
            ->take($limit)
            ->get();
    }
    
    public function updateAverageRating(): void
    {
        $avg = $this->reviews()->avg('rating');
        $this->rating_avg = round($avg, 1);
        $this->saveQuietly();
    }
    
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
    
    public function isVerified(): bool
    {
        return in_array($this->verification_type, ['verified', 'official']);
    }
}