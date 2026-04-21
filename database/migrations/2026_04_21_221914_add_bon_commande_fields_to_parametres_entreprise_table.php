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
        Schema::table('parametres_entreprise', function (Blueprint $table) {
            $table->string('prefixe_bon_commande')->default('BC-')->after('prefixe_bon_retour');
            $table->integer('prochain_numero_bon_commande')->default(1)->after('prochain_numero_bon_retour');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parametres_entreprise', function (Blueprint $table) {
            $table->dropColumn(['prefixe_bon_commande', 'prochain_numero_bon_commande']);
        });
    }
};
