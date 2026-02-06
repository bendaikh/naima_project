<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bons_livraison', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('facture_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('devis_id')->nullable()->constrained()->nullOnDelete();
            $table->date('date');
            $table->string('statut')->default('en_attente'); // livre, en_attente
            $table->timestamps();
        });

        Schema::create('bon_livraison_lignes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bon_livraison_id')->constrained('bons_livraison')->cascadeOnDelete();
            $table->string('designation');
            $table->decimal('quantite', 10, 2)->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bon_livraison_lignes');
        Schema::dropIfExists('bons_livraison');
    }
};
