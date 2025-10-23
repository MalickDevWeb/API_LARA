<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comptes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('numero_compte')->unique();
            $table->unsignedBigInteger('titulaire_id'); // FK vers client

            $table->enum('type', ['epargne', 'cheque'])->default('epargne');

            $table->decimal('solde', 15, 2)->default(0);
            $table->string('devise')->default('FCFA');

            // Statut uniquement pour les comptes épargne
            $table->enum('statut', ['actif', 'bloque', 'ferme'])
                  ->nullable()
                  ->comment('Applicable uniquement pour les comptes épargne');

            $table->text('motif_blocage')->nullable();

            // JSON flexible pour metadata (version, dernière modification, etc.)
            $table->json('metadata')->nullable();

            $table->timestamps();

            // 🔹 Clé étrangère vers client
            $table->foreign('titulaire_id')
                  ->references('user_id')
                  ->on('client')
                  ->onDelete('cascade');

            // 🔹 Index pour optimiser les recherches
            $table->index('titulaire_id');
            $table->index('statut');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comptes');
    }
};
