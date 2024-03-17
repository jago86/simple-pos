<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Enums\TaxId;
use App\Models\User;
use App\Models\Sale;
use App\Models\Client;
use App\Models\Product;
use App\Models\SaleDetail;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PosStoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_store_a_quotation()
    {
        $user = User::factory()->create();
        Client::factory()->create(['id' => 123]);
        Product::factory()->count(2)->state(new Sequence(['id' => 100], ['id' => 200]))->create();

        $response = $this->actingAs($user)->post(route('pos.store'), [
            'client_id' => 123,
            'tax_id' => TaxId::Iva->value,
            'items' => [
                [
                    'product_id' => 100,
                    'quantity' => 5,
                    'price' => 2,
                    'discount_percentage' => 5,
                ],
                [
                    'product_id' => 200,
                    'quantity' => 2,
                    'price' => 1.5,
                    'discount_percentage' => 10,
                ],
            ]
        ])->assertStatus(302);

        $this->assertEquals(1, Sale::count());
        $this->assertEquals(2, SaleDetail::count());
        $sale = Sale::first();
        [$saleDetailA, $saleDetailB] = SaleDetail::get();
        $this->assertEquals(123, $sale->client_id);
        $this->assertEquals(1220, $sale->subtotal);
        $this->assertEquals(TaxId::Iva, $sale->tax_id);
        $this->assertEquals(1366, $sale->total);

        $this->assertEquals(100, $saleDetailA->product_id);
        $this->assertEquals(5, $saleDetailA->quantity);
        $this->assertEquals(200, $saleDetailA->price);

        $this->assertEquals(200, $saleDetailB->product_id);
        $this->assertEquals(2, $saleDetailB->quantity);
        $this->assertEquals(150, $saleDetailB->price);
    }

    /** @test */
    public function can_store_a_quotation_with_discount()
    {
        $user = User::factory()->create();
        Client::factory()->create(['id' => 123]);
        Product::factory()->count(2)->state(new Sequence(['id' => 100], ['id' => 200]))->create();

        $response = $this->actingAs($user)->post(route('pos.store'), [
            'client_id' => 123,
            'tax_id' => TaxId::Iva->value,
            'items' => [
                [
                    'product_id' => 100,
                    'quantity' => 2,
                    'price' => 0.5,
                    'discount_percentage' => 5,
                ],
                [
                    'product_id' => 200,
                    'quantity' => 4,
                    'price' => 1,
                    'discount_percentage' => 10,
                ],
            ]
        ])->assertStatus(302);

        $this->assertEquals(1, Sale::count());
        $this->assertEquals(2, SaleDetail::count());
        $sale = Sale::first();
        [$saleDetailA, $saleDetailB] = SaleDetail::get();
        $this->assertEquals(123, $sale->client_id);
        $this->assertEquals(455, $sale->subtotal);
        $this->assertEquals(TaxId::Iva, $sale->tax_id);
        $this->assertEquals(510, $sale->total);

        $this->assertEquals(100, $saleDetailA->product_id);
        $this->assertEquals(2, $saleDetailA->quantity);
        $this->assertEquals(50, $saleDetailA->price);
        $this->assertEquals(5, $saleDetailA->discount_percentage);

        $this->assertEquals(200, $saleDetailB->product_id);
        $this->assertEquals(4, $saleDetailB->quantity);
        $this->assertEquals(100, $saleDetailB->price);
        $this->assertEquals(10, $saleDetailB->discount_percentage);
    }

    /** @test */
    public function client_id_is_required()
    {

    }
}
