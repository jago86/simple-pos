<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Team;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $user = \App\Models\User::factory()->create([
            'name' => 'Jairo',
            'email' => 'jago86@gmail.com',
            'password' => bcrypt(123456),
        ]);
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0] . "'s Team",
            'personal_team' => true,
        ]));

        $user = \App\Models\User::factory()->create([
            'name' => 'Cristhian Álvarez',
            'email' => 'cristhianmi@hotmail.es',
            'password' => bcrypt(12345678),
        ]);
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0] . "'s Team",
            'personal_team' => true,
        ]));

        $this->call(CategorySeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(ClientSeeder::class);
    }
}
