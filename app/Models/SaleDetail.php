<?php

namespace App\Models;

use Money\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Untested
    protected function subtotal(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => $attributes['price'] * $attributes['quantity']
        );
    }

    // Untested
    public function total(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) =>
                Money::USD($this->subtotal)
                    ->multiply($attributes['discount_percentage'])
                    ->divide(100)
                    ->multiply(-1)
                    ->add(Money::USD($this->subtotal))
                    ->getAmount()
        );
    }
}
