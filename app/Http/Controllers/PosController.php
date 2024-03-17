<?php

namespace App\Http\Controllers;

use App\Enums\TaxId;
use App\Models\Sale;
use Inertia\Inertia;
use App\Models\Client;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use App\Http\Requests\SaleRequest;

class PosController extends Controller
{
    public function create()
    {
        return Inertia::render('Sales/Create', [
            'clients' => Client::select('id', 'name', 'document_number')->get(),
        ]);
    }

    public function store(SaleRequest $request)
    {
        $sale = Sale::create([
            'client_id' => request('client_id'),
            'tax_id' => TaxId::tryFrom(request('tax_id'))
        ]);

        collect(request('items'))->each(fn ($item) => SaleDetail::create([
            'sale_id' => $sale->id,
            'product_id' => $item['product_id'],
            'quantity' => $item['quantity'],
            'price' => toCents($item['price']),
            'discount_percentage' => $item['discount_percentage'],
        ]));
        $sale->calc();

        session()->flash('success', 'Venta guardada exitosamente.');

        return back();
    }
}
