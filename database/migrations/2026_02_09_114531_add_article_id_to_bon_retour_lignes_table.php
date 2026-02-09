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
        Schema::table('bon_retour_lignes', function (Blueprint $table) {
            $table->foreignId('article_id')->nullable()->after('bon_retour_id')->constrained('articles')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bon_retour_lignes', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\Article::class, 'article_id');
        });
    }
};
