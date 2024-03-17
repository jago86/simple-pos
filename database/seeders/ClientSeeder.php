<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Client::create([
            'name' => 'John Doe',
            'document_number' => '1051791385',
        ]);
        Client::create([
            'name' => 'Susan Doe',
            'document_number' => '0903495585',
        ]);
    }
}
