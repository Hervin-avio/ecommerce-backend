<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
        'category_id',
        'price',
        'weight',
        'photo'
    ];

    protected $appends = ['photo_url'];

    public function getPhotoUrlAttribute()
{
    if (!$this->photo) {
        return 'https://via.placeholder.com/150';
    }

    // kalau sudah URL, langsung pakai
    if (str_starts_with($this->photo, 'http')) {
        return $this->photo;
    }

    return asset('storage/' . $this->photo);
}

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
