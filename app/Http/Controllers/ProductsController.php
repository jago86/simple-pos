<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index()
    {
        if (!request()->filled('name')) {
            return [];
        }

        $products = Product::when(request()->filled('name'), fn ($q) => $q->where('name', 'LIKE', '%'.request('name').'%'))
            ->get();

        return $products;
    }
}
