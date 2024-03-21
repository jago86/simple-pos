<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Apronax',
            'price' => 50,
            'stock' => 100,
            'category_id' => 1,
        ]);
        Product::create([
            'name' => 'Buprex Migra',
            'price' => 51,
            'stock' => 100,
            'category_id' => 1,
        ]);
        Product::create([
            'name' => 'Umbral 1G',
            'price' => 48,
            'stock' => 100,
            'category_id' => 1,
        ]);
        Product::create([
            'name' => 'Base 108',
            'price' => 1200,
            'stock' => 100,
            'category_id' => 2,
        ]);
    }
}
