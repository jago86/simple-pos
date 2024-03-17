<?php

namespace Database\Factories;

use App\Enums\TaxId;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sale>
 */
class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => fn () => Client::factory()->create(),
            'subtotal' => 0,
            'tax_id' => TaxId::Iva,
        ];
    }
}
