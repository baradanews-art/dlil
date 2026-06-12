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
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'status',
        'verification_type_label',
        'rating_avg',
        'reviews_count',
        'logo_url',
        'cover_url'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($business) {
            if (empty($business->slug) && !empty($business->title)) {
                $business->slug = static::generateUniqueSlug($business->title);
            }
        });
        
        static::updating(function ($business) {
            if ($business->isDirty('title') && empty($business->slug)) {
                $business->slug = static::generateUniqueSlug($business->title);
            }
        });
        
        static::saved(function () {
            static::clearCache();
        });
        
        static::deleted(function () {
            static::clearCache();
        });
    }

    public static function generateUniqueSlug($title)
    {
        $slug = Str::slug($title, '-', 'ar');
        $slug = $slug ?: 'business-' . rand(1000, 9999);
        $count = static::where('slug', 'LIKE', "{$slug}%")->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }

    public static function clearCache()
    {
        Cache::forget('featured_businesses');
        Cache::forget('top_rated_businesses');
        Cache::forget('categories_with_count');
    }

    // ============================================================
    // العلاقات
    // ============================================================
    
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function reviewsAvgRating()
    {
        return $this->reviews()
            ->selectRaw('avg(rating) as average, business_id')
            ->groupBy('business_id');
    }

    // ============================================================
    // 🖼️ دوال الصور (محسنة للعمل على الاستضافة المشتركة)
    // ============================================================
    
    /**
     * رابط اللوجو مع fallback
     */
 public function getLogoUrlAttribute()
{
    if (!empty($this->logo)) {
        $filename = basename($this->logo);
        return url("/image/logos/{$filename}");
    }
    return $this->getDefaultLogo();
}

public function getCoverUrlAttribute()
{
    if (!empty($this->cover)) {
        $filename = basename($this->cover);
        return url("/image/covers/{$filename}");
    }
    return $this->getDefaultCover();
}
    
    /**
     * لوجو افتراضي
     */
    private function getDefaultLogo()
    {
        $text = urlencode(substr($this->title ?? 'منشأة', 0, 2));
        return "https://placehold.co/200x200/1e293b/10b981?text={$text}";
    }
    
    /**
     * غلاف افتراضي حسب التصنيف
     */
    private function getDefaultCover()
    {
        $categoryIcons = [
            'مطاعم' => '🍔',
            'صيدليات' => '💊',
            'مفروشات' => '🛋️',
            'أسواق' => '🛒',
            'تعليم' => '📚',
            'صيانة' => '🔧',
        ];
        
        $icon = $categoryIcons[$this->category->name ?? ''] ?? '🏪';
        $categoryName = urlencode($this->category->name ?? 'منشأة');
        
        return "https://placehold.co/1200x400/0f172a/10b981?text={$icon}%20{$categoryName}";
    }

    // ============================================================
    // Accessors & Mutators
    // ============================================================
    
    public function getStatusAttribute(): string
    {
        return $this->is_approved ? 'approved' : 'pending';
    }

    public function getVerificationTypeAttribute($value): string
    {
        $types = ['none' => 'unverified', 'verified' => 'verified', 'official' => 'official'];
        return $types[$value] ?? 'unverified';
    }

    public function getVerificationTypeLabelAttribute(): string
    {
        $labels = ['none' => 'غير موثق', 'verified' => 'موثق', 'official' => 'رسمي معتمد'];
        return $labels[$this->attributes['verification_type'] ?? 'none'] ?? 'غير موثق';
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
        return Str::limit(strip_tags($this->description), 120, '...');
    }

    public function getGoogleMapsUrlAttribute(): ?string
    {
        return $this->Maps_url ?? null;
    }

    // ============================================================
    // Scopes
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

    public function scopeSearch($query, $term)
    {
        return $query->where('title', 'LIKE', "%{$term}%")
            ->orWhere('description', 'LIKE', "%{$term}%")
            ->orWhere('phone', 'LIKE', "%{$term}%");
    }

    public function scopeInCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeInLocation($query, $locationId)
    {
        return $query->where('location_id', $locationId);
    }

    // ============================================================
    // دوال مساعدة
    // ============================================================
    
    public function getSimilarBusinesses(int $limit = 4)
    {
        return self::approved()
            ->where('category_id', $this->category_id)
            ->where('id', '!=', $this->id)
            ->with(['category', 'location'])
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

    public function isOfficial(): bool
    {
        return $this->verification_type === 'official';
    }

    public function hasDelivery(): bool
    {
        return (bool) $this->delivery_available;
    }
}