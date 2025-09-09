<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

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
        return [
            'name' => $this->faker->words(2, true), // contoh: "Laptop Gaming"
            'description' => $this->faker->sentence(10),
            'status' => $this->faker->randomElement(['publish', 'draft']),
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'price' => $this->faker->numberBetween(10000, 5000000),
            'weight' => $this->faker->numberBetween(200, 5000), // gram
            'photo' => $this->faker->imageUrl(640, 480, 'products', true),
        ];
    }
}
