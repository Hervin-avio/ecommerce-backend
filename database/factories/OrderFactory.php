<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invoice_id' => strtoupper('INV-' . $this->faker->unique()->numerify('#####')),
            'customer' => $this->faker->name(),
            'total' => $this->faker->numberBetween(50000, 2000000),
            'status' => $this->faker->randomElement(['baru', 'diproses', 'dikirim', 'selesai']),
            'order_date' => $this->faker->dateTimeThisYear(),
        ];
    }
}
