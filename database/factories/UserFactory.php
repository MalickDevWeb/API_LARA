<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = \App\Models\User::class;

    public function definition(): array
    {
        $prenom = $this->faker->firstName();
        $nom = $this->faker->lastName();

        return [
            'prenom' => $prenom,
            'nom' => $nom,
            'sexe' => $this->faker->randomElement(['M', 'F']),
            'date_naissance' => $this->faker->date(),
            'adresse' => $this->faker->address(),
            'login' => Str::lower($prenom.'.'.$nom),
            'email' => $this->faker->unique()->safeEmail(),
            'telephone' => $this->faker->phoneNumber(),
            'type' => $this->faker->randomElement(['ADMIN','CLIENT']),
            'password' => Hash::make('password123'), // mot de passe par défaut
        ];
    }
}
