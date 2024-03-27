<?php

namespace App\Http\Controllers;

use App\Enums\TaxId;
use App\Models\Sale;
use Inertia\Inertia;
use App\Models\Client;
use App\Models\Category;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use App\Http\Requests\SaleRequest;
use Illuminate\Support\Facades\DB;
use App\Providers\App\Events\SaleCreated;

class PosController extends Controller
{
    public function create()
    {
        return Inertia::render('Sales/Create', [
            // 'clients' => Client::select('id', 'name', 'document_number')->get(),
            'categories' => Category::all(),
            'tags' => DB::table('products')->distinct()->pluck('tag'),
        ]);
    }

    public function store(SaleRequest $request)
    {
        $sale = Sale::create([
            'client_id' => request('client_id'),
            'tax_id' => TaxId::tryFrom(request('tax_id'))
        ]);

        collect($request->input('items'))->each(fn ($item) => SaleDetail::create([
            'sale_id' => $sale->id,
            'product_id' => $item['product_id'],
            'quantity' => $item['quantity'],
            'price' => toCents($item['price']),
            'discount_percentage' => $item['discount_percentage'],
        ]));
        $sale->calc();

        SaleCreated::dispatch($sale);

        session()->flash('success', 'Venta guardada exitosamente.');

        return back();
    }
}
