<?php

namespace Database\Factories;

use App\Models\Compte;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'compte_id' => Compte::factory(),
            'type' => $this->faker->randomElement(['depot', 'retrait', 'virement', 'frais']),
            'montant' => $this->faker->randomFloat(2, 1, 10000),
            'devise' => 'FCFA',
            'description' => $this->faker->optional()->sentence(),
            'date_transaction' => $this->faker->dateTime(),
            'statut' => $this->faker->randomElement(['en_attente', 'validee', 'annulee']),
        ];
    }
}