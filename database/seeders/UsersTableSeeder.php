<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
         // Crée l'utilisateur ADMIN si non existant
         User::firstOrCreate(
             ['email' => 'admin@example.com'],
             [
                 'prenom' => 'Admin',
                 'nom' => 'Super',
                 'sexe' => 'M',
                 'login' => 'admin.super',
                 'type' => 'ADMIN',
                 'password' => bcrypt('admin123'), // Note: Use a strong password in production
             ]
         );

         // Crée 10 utilisateurs aléatoires (idempotent)
         for ($i = 0; $i < 10; $i++) {
             // Générer des attributs jusqu'à obtenir un email différent de l'admin
             do {
                 $attrs = User::factory()->make()->toArray();
             } while (isset($attrs['email']) && $attrs['email'] === 'admin@example.com');

             // Garantir l'absence d'ID/timestamps dans les attributs
             unset($attrs['id'], $attrs['created_at'], $attrs['updated_at']);
             User::firstOrCreate(['email' => $attrs['email']], $attrs);
         }
     }
}
