<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class UpdateCategoryNameSeeder extends Seeder
{
    public function run(): void
    {
        // Peta id ke nama & slug baru
        $map = [
            1 => ['name' => 'Elektronik', 'slug' => 'elektronik'],
            2 => ['name' => 'Fashion', 'slug' => 'fashion'],
            3 => ['name' => 'Makanan & Minuman', 'slug' => 'makanan-minuman'],
            4 => ['name' => 'Olahraga', 'slug' => 'olahraga'],
            5 => ['name' => 'Kesehatan & Kecantikan', 'slug' => 'kesehatan-kecantikan'],
            6 => ['name' => 'Peralatan Rumah Tangga', 'slug' => 'peralatan-rumah-tangga'],
            7 => ['name' => 'Buku & Alat Tulis', 'slug' => 'buku-alat-tulis'],
            8 => ['name' => 'Mainan & Hobi', 'slug' => 'mainan-hobi'],
            9 => ['name' => 'Otomotif', 'slug' => 'otomotif'],
            10 => ['name' => 'Pertanian', 'slug' => 'pertanian'],
        ];

        foreach ($map as $id => $data) {
            Category::where('id', $id)->update($data);
        }
    }
}
