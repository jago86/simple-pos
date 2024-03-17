<?php

namespace App\Http\Controllers;

use App\Enums\TaxId;
use App\Models\Sale;
use Inertia\Inertia;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use App\Http\Requests\SaleRequest;

class SalesController extends Controller
{
    public function index()
    {
        return Inertia::render('Sales/Index', [
            'sales' => Sale::with('client')->paginate(50),
        ]);
    }

    public function edit(Sale $sale)
    {
        return Inertia::render('Sales/Edit', [
            'sale' => $sale->load(['client', 'details.product']),
        ]);
    }

    public function update(Sale $sale, SaleRequest $request)
    {
        $sale->update([
            'client_id' => request('client_id'),
        ]);

        $saleItems = collect($request->input('items'));

        // Deleted details
        $sale->details
            ->filter(fn (SaleDetail $saleDetail) => !$saleItems->contains('product_id', $saleDetail->product_id))
            ->each(fn (SaleDetail $saleDetail) => $saleDetail->delete());

        // Existing details
        $sale->details
            ->filter(fn (SaleDetail $saleDetail) => $saleItems->contains('product_id', $saleDetail->product_id))
            ->each(function (SaleDetail $saleDetail) use ($saleItems) {
                $item = $saleItems->first(fn ($item) => $item['product_id'] == $saleDetail->product_id);
                $saleDetail->update([
                    'quantity' => $item['quantity'],
                    'discount_percentage' => $item['discount_percentage'],
                ]);
            });

        // New details
        $saleItems
            ->filter(fn ($item) => !$sale->details->contains('product_id', $item['product_id']))
            ->each(fn ($item) => SaleDetail::create([
                'sale_id' => $sale->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => toCents($item['price']),
                'discount_percentage' => $item['discount_percentage'],
            ]));

        $sale->calc();

        session()->flash('success', 'Venta guardada exitosamente.');

        return redirect(route('sales.index'));
    }

    public function destroy(Sale $sale)
    {
        $sale->delete();

        session()->flash('success', 'La venta ha sido borrada.');

        return back();
    }
}
