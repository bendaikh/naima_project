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
        Schema::table('articles', function (Blueprint $table) {
            $table->string('numero_facture')->nullable()->unique()->after('quantite_stock');
            $table->string('compte_revenu')->nullable()->after('numero_facture');
            $table->string('compte_depense')->nullable()->after('compte_revenu');
            $table->string('image')->nullable()->after('compte_depense');
            $table->string('entrepot')->nullable()->after('image');
            $table->string('ugs')->nullable()->after('entrepot');
            $table->decimal('impot', 10, 2)->nullable()->after('ugs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn('numero_facture');
            $table->dropColumn('compte_revenu');
            $table->dropColumn('compte_depense');
            $table->dropColumn('image');
            $table->dropColumn('entrepot');
            $table->dropColumn('ugs');
            $table->dropColumn('impot');
        });
    }
};
