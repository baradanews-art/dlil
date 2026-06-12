<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'user_id',
        'reviewer_name', // تم إضافته لتمكين استقبال تقييمات الجمهور دون فرض تسجيل دخول
        'rating',
        'comment'
    ];

    // المراجعة تنتمي لنشاط تجاري محدد
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    // المراجعة كتبها مستخدم محدد (إذا كان مسجلاً)
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'زائر مجهول'
        ]);
    }
}