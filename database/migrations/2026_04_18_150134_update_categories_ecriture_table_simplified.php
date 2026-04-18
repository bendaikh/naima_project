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
        Schema::table('categories_ecriture', function (Blueprint $table) {
            // Remove type and description columns - keep it simple with just nom
            $table->dropColumn(['type', 'description']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories_ecriture', function (Blueprint $table) {
            $table->enum('type', ['entree', 'sortie'])->nullable();
            $table->text('description')->nullable();
        });
    }
};
