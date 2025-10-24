<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Compte;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CompteFactory extends Factory
{
    protected $model = Compte::class;

    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'numero_compte' => $this->faker->unique()->numerify('##########'),
            'titulaire_id' => Client::factory(),
            'type' => $this->faker->randomElement(['epargne', 'cheque']),
            'devise' => 'FCFA',
            'statut' => $this->faker->randomElement(['actif', 'bloque', 'ferme']),
            'motif_blocage' => $this->faker->optional()->sentence(),
            'metadata' => null,
        ];
    }
}