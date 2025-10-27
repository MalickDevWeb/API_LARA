<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        // Récupère tous les ids existants
        $existingIds = Transaction::pluck('id')->toArray();
        $forbiddenIds = $existingIds;

        $created = 0;
        $maxAttempts = 40;
        $attempts = 0;
        while ($created < 20 && $attempts < $maxAttempts) {
            $attrs = Transaction::factory()->make()->toArray();
            if (in_array($attrs['id'], $forbiddenIds)) {
                $attempts++;
                continue;
            }
            unset($attrs['created_at'], $attrs['updated_at']);
            $transaction = Transaction::firstOrCreate(['id' => $attrs['id']], $attrs);
            $forbiddenIds[] = $attrs['id'];
            $created++;
            $attempts++;
        }
    }
}