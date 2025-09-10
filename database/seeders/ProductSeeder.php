<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name' => 'Produk Expedita',
                'description' => 'Deskripsi produk expedita',
                'status' => 'publish',
                'category_id' => 1, // pastikan category_id ada
                'price' => 150000,
                'weight' => 1.2,
                'photo' => 'products/produk1.jpg',
            ],
            [
                'name' => 'Produk Non',
                'description' => 'Deskripsi produk non',
                'status' => 'publish',
                'category_id' => 2,
                'price' => 120000,
                'weight' => 0.8,
                'photo' => 'products/produk2.jpg',
            ],
            [
                'name' => 'Produk Accusamus',
                'description' => 'Deskripsi produk accusamus',
                'status' => 'draft',
                'category_id' => 3,
                'price' => 200000,
                'weight' => 2.0,
                'photo' => 'products/produk3.jpg',
            ],
        ];

        foreach ($products as $p) {
            Product::create($p);
        }
    }
}
