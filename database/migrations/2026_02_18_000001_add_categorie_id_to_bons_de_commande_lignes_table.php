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
        Schema::table('bons_de_commande_lignes', function (Blueprint $table) {
            $table->foreignId('categorie_id')
                ->nullable()
                ->after('product_name')
                ->constrained('categories')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bons_de_commande_lignes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('categorie_id');
        });
    }
};
