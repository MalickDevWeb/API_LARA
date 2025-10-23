<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // Crée un utilisateur ADMIN fixe
        User::factory()->create([
            'prenom' => 'Admin',
            'nom' => 'Super',
            'sexe' => 'M',
            'login' => 'admin.super',
            'email' => 'admin@example.com',
            'type' => 'ADMIN',
            'password' => bcrypt('admin123'),
        ]);

        // Crée 10 utilisateurs aléatoires
        User::factory()->count(10)->create();
    }
}
