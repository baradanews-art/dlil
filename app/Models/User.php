<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'role',
        'avatar',
        'phone',
        'is_active',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
        'is_active' => 'boolean',
    ];
    
    protected $appends = ['avatar_url', 'is_super_admin'];
    
    // ============================================================
    // ✅ الـ Accessors
    // ============================================================
    
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && file_exists(public_path($this->avatar))) {
            return asset($this->avatar);
        }
        
        return "https://ui-avatars.com/api/?background=10b981&color=fff&name=" . urlencode($this->name);
    }
    
    public function getIsSuperAdminAttribute(): bool
    {
        return $this->role === 'super_admin';
    }
    
    // ============================================================
    // ✅ التحقق من الصلاحيات
    // ============================================================
    
    public function isAdmin(): bool
    {
        return $this->is_admin || in_array($this->role, ['admin', 'super_admin']);
    }
    
    public function canAccessAdmin(): bool
    {
        return $this->isAdmin() && $this->is_active;
    }
}