<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// App\Models\Product.php
class Product extends Model
{
    protected $fillable = [
        'name', 'description', 'status', 'category_id', 'price', 'weight', 'photo'
    ];

    protected $appends = ['photo_url'];

    public function getPhotoUrlAttribute()
    {
        if ($this->photo && !str_starts_with($this->photo, 'http')) {
            return "https://via.placeholder.com/640x480.png/{$this->photo}";
        }
        return $this->photo;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
