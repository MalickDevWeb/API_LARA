<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use App\Models\User;

use Illuminate\Support\Arr;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Récupère tous les emails existants (pour éviter les doublons)
        $existingEmails = User::pluck('email')->toArray();
        $forbiddenEmails = array_merge($existingEmails, ['admin@example.com']);

        $created = 0;
        $maxAttempts = 20;
        $attempts = 0;
        while ($created < 2 && $attempts < $maxAttempts) {
            $attrs = User::factory()->make()->toArray();
            if (in_array($attrs['email'], $forbiddenEmails)) {
                $attempts++;
                continue;
            }
            unset($attrs['id'], $attrs['created_at'], $attrs['updated_at']);
            $user = User::firstOrCreate(['email' => $attrs['email']], $attrs);
            Admin::firstOrCreate(['user_id' => $user->id], ['role' => Arr::random(['admin', 'super_admin'])]);
            $forbiddenEmails[] = $attrs['email'];
            $created++;
            $attempts++;
        }
    }
}