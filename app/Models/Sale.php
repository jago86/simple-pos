<?php

namespace App\Models;

use Money\Money;
use App\Enums\TaxId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'tax_id' => TaxId::class,
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function details()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function calc()
    {
        $subtotalCents = $this->details()
            ->get()
            ->sum(fn (SaleDetail $saleDetail) => $saleDetail->total);

        $subtotalMoney = Money::USD($subtotalCents);
        $this->subtotal = $subtotalMoney->getAmount();
        $this->total = $subtotalMoney->add($subtotalMoney->multiply(0.12))->getAmount();
        $this->save();

        return $this;
    }
}
