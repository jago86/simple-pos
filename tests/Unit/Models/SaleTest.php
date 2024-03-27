<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Enums\TaxId;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SaleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_calc_the_subtotal_of_a_sale()
    {
        $sale = Sale::factory()->create();
        SaleDetail::factory()->for($sale)->createQuietly([
            'quantity' => 2,
            'price' => 10,
        ]);
        SaleDetail::factory()->for($sale)->createQuietly([
            'quantity' => 2,
            'price' => 30,
        ]);
        $this->assertEquals(0, $sale->subtotal);

        $sale->calc();
        $this->assertEquals(80, $sale->subtotal);
    }

    /** @test */
    public function can_calc_the_total_of_a_sale()
    {
        $sale = Sale::factory()->create([
            'tax_id' => TaxId::Iva->value,
        ]);
        SaleDetail::factory()->for($sale)->createQuietly([
            'quantity' => 2,
            'price' => 10,
        ]);
        SaleDetail::factory()->for($sale)->createQuietly([
            'quantity' => 2,
            'price' => 30,
        ]);
        $this->assertEquals(0, $sale->subtotal);

        $sale->calc();
        $this->assertEquals(80, $sale->subtotal);
        $this->assertEquals(90, $sale->total);
    }
}
