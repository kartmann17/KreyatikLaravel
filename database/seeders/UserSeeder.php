<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Création d'un utilisateur administrateur
        User::create([
            'name' => 'Administrateur',
            'email' => 'kreyatik@gmail.com',
            'password' => Hash::make('Mia131024@'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Création d'un utilisateur client
        User::create([
            'name' => 'Client',
            'email' => 'client@example.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'email_verified_at' => now(),
        ]);
    }
}
