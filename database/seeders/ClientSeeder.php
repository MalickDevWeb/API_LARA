<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;
use App\Models\User;


class ClientSeeder extends Seeder
{
    public function run(): void
    {
        // Récupère tous les emails existants (pour éviter les doublons)
        $existingEmails = User::pluck('email')->toArray();
        $forbiddenEmails = array_merge($existingEmails, ['admin@example.com']);

        $created = 0;
        $maxAttempts = 20;
        $attempts = 0;
        while ($created < 5 && $attempts < $maxAttempts) {
            $attrs = User::factory()->make()->toArray();
            if (in_array($attrs['email'], $forbiddenEmails)) {
                $attempts++;
                continue;
            }
            unset($attrs['id'], $attrs['created_at'], $attrs['updated_at']);
            $user = User::firstOrCreate(['email' => $attrs['email']], $attrs);
            Client::firstOrCreate(['user_id' => $user->id]);
            $forbiddenEmails[] = $attrs['email'];
            $created++;
            $attempts++;
        }
    }
}