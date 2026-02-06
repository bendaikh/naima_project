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
            // Add new columns if they don't exist
            if (!Schema::hasColumn('articles', 'nom')) {
                $table->string('nom')->nullable();
            }
            if (!Schema::hasColumn('articles', 'ugs')) {
                $table->string('ugs')->unique()->nullable();
            }
            if (!Schema::hasColumn('articles', 'impot')) {
                $table->decimal('impot', 5, 2)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            if (Schema::hasColumn('articles', 'nom')) {
                $table->dropColumn('nom');
            }
            if (Schema::hasColumn('articles', 'ugs')) {
                $table->dropColumn('ugs');
            }
            if (Schema::hasColumn('articles', 'impot')) {
                $table->dropColumn('impot');
            }
        });
    }
};
