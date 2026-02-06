<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parametres_entreprise', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->nullable();
            $table->text('adresse')->nullable();
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('logo')->nullable();
            $table->decimal('tva_par_defaut', 5, 2)->default(20);
            $table->string('prefixe_devis')->default('DEV-');
            $table->string('prefixe_facture')->default('FAC-');
            $table->string('prefixe_bon_livraison')->default('BL-');
            $table->string('prefixe_bon_retour')->default('BR-');
            $table->integer('prochain_numero_devis')->default(1);
            $table->integer('prochain_numero_facture')->default(1);
            $table->integer('prochain_numero_bon_livraison')->default(1);
            $table->integer('prochain_numero_bon_retour')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parametres_entreprise');
    }
};
