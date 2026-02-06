<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avoirs', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('facture_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bon_retour_id')->constrained('bons_retour')->cascadeOnDelete();
            $table->date('date');
            $table->decimal('montant_ht', 12, 2)->default(0);
            $table->decimal('tva', 10, 2)->default(0);
            $table->decimal('montant_ttc', 12, 2)->default(0);
            $table->enum('statut', ['brouillon', 'emis', 'applique'])->default('brouillon');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('avoir_lignes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('avoir_id')->constrained()->cascadeOnDelete();
            $table->string('designation');
            $table->decimal('quantite', 10, 2);
            $table->decimal('prix_unitaire', 10, 2);
            $table->decimal('montant_ht', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avoir_lignes');
        Schema::dropIfExists('avoirs');
    }
};
