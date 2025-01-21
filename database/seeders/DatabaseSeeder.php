<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Cria o usuário
        User::factory()->withPersonalTeam()->create([
            'name' => 'eduardo',
            'email' => 'eduardom@email.com',
            'password' => Hash::make('password'),
        ]);

        // E agora chama seu seeder de conf_kr
        $this->call([
            ConfKrSeeder::class,
            ConfMacroprocessosSeeder::class,
            ConfOrgaoSeeder::class,
            ConfOeSeeder::class,
            // se tiver mais seeders, pode adicionar aqui
        ]);
    }
}
