<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'business_id',
        'user_id',
        'reviewer_name',
        'rating',
        'comment',
        'reply',
        'replied_at',
        'is_approved'
    ];
    
    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean',
        'replied_at' => 'datetime',
        'created_at' => 'datetime',
    ];
    
    protected $appends = ['rating_stars', 'replied'];
    
    // ============================================================
    // ✅ العلاقات
    // ============================================================
    
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'زائر',
            'id' => null
        ]);
    }
    
    // ============================================================
    // ✅ الـ Accessors
    // ============================================================
    
    public function getRatingStarsAttribute(): string
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            $stars .= $i <= $this->rating ? '★' : '☆';
        }
        return $stars;
    }
    
    public function getRepliedAttribute(): bool
    {
        return !empty($this->reply);
    }
    
    // ============================================================
    // ✅ النطاقات (Scopes)
    // ============================================================
    
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }
    
    public function scopeForBusiness($query, $businessId)
    {
        return $query->where('business_id', $businessId);
    }
    
    public function scopeHighRated($query, $minRating = 4)
    {
        return $query->where('rating', '>=', $minRating);
    }
    
    public function scopeWithReplies($query)
    {
        return $query->whereNotNull('reply');
    }
    
    // ============================================================
    // ✅ دوال مساعدة
    // ============================================================
    
    public function addReply(string $replyText): void
    {
        $this->update([
            'reply' => $replyText,
            'replied_at' => now(),
        ]);
    }
    
    public function approve(): void
    {
        $this->update(['is_approved' => true]);
    }
    
    public function disapprove(): void
    {
        $this->update(['is_approved' => false]);
    }
}