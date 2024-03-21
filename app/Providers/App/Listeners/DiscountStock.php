<?php

namespace App\Providers\App\Listeners;

use App\Models\SaleDetail;
use App\Providers\App\Events\SaleCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DiscountStock
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SaleCreated $event): void
    {
        $event->sale->loadMissing(['details' => ['product']]);
        $event->sale->details->each(function (SaleDetail $saleDetail) {
            $saleDetail->product->update([
                'stock' => $saleDetail->product->stock - $saleDetail->quantity,
            ]);
        });
    }
}
