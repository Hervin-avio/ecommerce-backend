<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
{
    $photos = [
        'products/produk1.jpg',
        'products/produk2.jpg',
        'products/produk3.jpg',
    ];

    return [
        'name' => $this->faker->words(2, true),
        'description' => $this->faker->sentence(10),
        'status' => $this->faker->randomElement(['publish', 'draft']),
        'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
        'price' => $this->faker->numberBetween(10000, 5000000),
        'weight' => $this->faker->numberBetween(200, 5000), // gram
        'photo' => $this->faker->randomElement($photos), // pilih salah satu file yang ada di storage
    ];
}

}
