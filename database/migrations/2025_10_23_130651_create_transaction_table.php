<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            // Compte concerné
            $table->uuid('compte_id');
            
            // Type de transaction
            $table->enum('type', ['depot', 'retrait', 'virement', 'frais']);
            
            $table->decimal('montant', 15, 2);
            $table->string('devise')->default('FCFA');
            $table->string('description')->nullable();
            $table->timestamp('date_transaction')->useCurrent();

            // Statut de la transaction
            $table->enum('statut', ['en_attente', 'validee', 'annulee'])->default('en_attente');

            $table->timestamps();

            // Clé étrangère vers comptes
            $table->foreign('compte_id')
                  ->references('id')
                  ->on('comptes')
                  ->onDelete('cascade');

            // Index pour optimiser les recherches
            $table->index('compte_id');
            $table->index('type');
            $table->index('statut');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
