<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('bon_retour_lignes');
        Schema::dropIfExists('bons_retour');

        Schema::create('bons_retour', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bon_livraison_id')->nullable()->constrained('bons_livraison')->nullOnDelete();
            $table->foreignId('facture_id')->nullable()->constrained()->nullOnDelete();
            $table->date('date');
            $table->text('motif')->nullable();
            $table->timestamps();
        });

        Schema::create('bon_retour_lignes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bon_retour_id')->constrained('bons_retour')->cascadeOnDelete();
            $table->string('designation');
            $table->decimal('quantite', 10, 2)->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bon_retour_lignes');
        Schema::dropIfExists('bons_retour');
    }
};
