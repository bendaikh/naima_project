<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->string('type_compte')->default('client'); // client, fournisseur, general
            $table->string('type_facturation')->default('modele'); // modele, hong_kong, international
            $table->string('categorie'); // electronique, materiel, logiciel, service
            $table->date('dates_emission');
            $table->date('dates_echeance');
            $table->string('numero_facture')->unique();
            $table->decimal('prix_vente', 10, 2);
            $table->decimal('prix_achat', 10, 2);
            $table->decimal('quantite', 10, 2);
            $table->string('unite')->default('piece'); // dh, piece, kg, m
            $table->string('compte_revenu')->nullable();
            $table->string('compte_depense')->nullable();
            $table->string('image_path')->nullable();
            $table->string('entrepot')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
