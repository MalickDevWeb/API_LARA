<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         // Prevent seeding in production
         if (app()->environment('production')) {
             return;
         }

         $this->call(UsersTableSeeder::class);
         $this->call(ClientSeeder::class);
         $this->call(AdminSeeder::class);
         $this->call(CompteSeeder::class);
         $this->call(TransactionSeeder::class);
     }
}
