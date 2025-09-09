<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Elektronik',
            'Fashion',
            'Olahraga',
            'Kesehatan',
            'Makanan',
            'Minuman',
            'Buku',
            'Mainan',
            'Perabot',
            'Kendaraan'
        ];

        foreach ($categories as $name) {
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name), // ✅ slug otomatis dari name
            ]);
        }
    }
}
