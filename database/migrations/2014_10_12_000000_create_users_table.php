<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('prenom');
            $table->string('nom');

            $table->string('sexe');
            $table->date('date_naissance');
            $table->string('adresse')->nullable();
            $table->string('login')->unique()->nullable();  // index UNIQUE automatique
            $table->string('email')->unique()->nullable();  // index UNIQUE automatique
            $table->string('telephone')->nullable();

            $table->enum('type', ['ADMIN', 'CLIENT'])->default('CLIENT');
            $table->string('password')->nullable();

            $table->timestamps();

            // Index supplémentaires pour optimiser les recherches
            $table->index('telephone');              // recherche rapide par prrenom
            $table->index('nom');                 // recherche rapide par nom
            $table->index(['prenom', 'nom']);     // recherche rapide par prénom + nom
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
