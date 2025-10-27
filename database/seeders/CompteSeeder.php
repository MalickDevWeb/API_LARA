<?php

namespace Database\Seeders;

use App\Models\Compte;
use Illuminate\Database\Seeder;

class CompteSeeder extends Seeder
{
    public function run(): void
    {
        // Récupère tous les numéros de compte existants
        $existingNumeros = Compte::pluck('numero_compte')->toArray();
        $forbiddenNumeros = $existingNumeros;

        $created = 0;
        $maxAttempts = 30;
        $attempts = 0;
        while ($created < 10 && $attempts < $maxAttempts) {
            $attrs = Compte::factory()->make()->toArray();
            if (in_array($attrs['numero_compte'], $forbiddenNumeros)) {
                $attempts++;
                continue;
            }
            unset($attrs['id'], $attrs['created_at'], $attrs['updated_at']);
            $compte = Compte::firstOrCreate(['numero_compte' => $attrs['numero_compte']], $attrs);
            $forbiddenNumeros[] = $attrs['numero_compte'];
            $created++;
            $attempts++;
        }
    }
}