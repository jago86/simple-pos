<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    public function index()
    {
        return Client::when(request()->filled('name'), fn ($q) => $q->where('name', 'LIKE', '%'.request('name').'%'))
            ->get();
    }
}
