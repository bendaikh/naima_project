<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bons_retour_fournisseur_lignes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bon_retour_fournisseur_id')
                ->constrained('bons_retour_fournisseur')->onDelete('cascade');
            $table->foreignId('bon_de_commande_ligne_id')
                ->constrained('bons_de_commande_lignes')->onDelete('cascade');
            $table->foreignId('article_id')->constrained('articles')->onDelete('cascade');
            $table->decimal('quantity_received', 10, 2); // From bon_de_commande_ligne
            $table->decimal('quantity_already_returned', 10, 2)->default(0);
            $table->decimal('return_quantity', 10, 2); // Quantity being returned
            $table->timestamps();

            // Indexes
            $table->index('bon_retour_fournisseur_id');
            $table->index('bon_de_commande_ligne_id');
            $table->index('article_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bons_retour_fournisseur_lignes');
    }
};
