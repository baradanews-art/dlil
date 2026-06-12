<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'type'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($category) {
            if (empty($category->slug) && !empty($category->name)) {
                $category->slug = Str::slug($category->name, '-', 'ar');
            }
        });
    }

    public function businesses()
    {
        return $this->hasMany(Business::class);
    }
}