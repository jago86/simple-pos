<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index()
    {
        if (!request()->filled('name') and !request()->filled('category_id') and !request()->wantsJson()) {
            return [];
        }

        $products = Product::when(request()->filled('category_id'), fn ($q) => $q->where('category_id', request('category_id')))
            ->when(request()->filled('name'), fn ($q) => $q->where('name', 'LIKE', '%'.request('name').'%'))
            ->get();

        return $products;
    }
}
