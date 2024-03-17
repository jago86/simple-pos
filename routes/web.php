<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\PosController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\ProductsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('clients', [ClientsController::class, 'index'])
        ->name('clients.index');

    Route::get('products', [ProductsController::class, 'index'])
        ->name('products.index');

    Route::get('pos/create', [PosController::class, 'create'])
        ->name('pos.create');

    Route::post('pos', [PosController::class, 'store'])
        ->name('pos.store');

    Route::get('sales', [SalesController::class, 'index'])
        ->name('sales.index');

    Route::get('sales/{sale}/edit', [SalesController::class, 'edit'])
        ->name('sales.edit');

    Route::put('sales/{sale}', [SalesController::class, 'update'])
        ->name('sales.update');

    Route::delete('sales/{sale}', [SalesController::class, 'destroy'])
        ->name('sales.delete');
});
