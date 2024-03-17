<?php

namespace Database\Factories;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SaleDetail>
 */
class SaleDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sale_id' => fn () => Sale::factory()->create(),
            'product_id' => fn () => Product::factory()->create(),
            'quantity' => rand(1, 20),
            'price' => rand(10, 100),
        ];
    }
}
