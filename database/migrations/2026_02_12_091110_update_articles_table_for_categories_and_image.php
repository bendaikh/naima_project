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
            // Add categorie_id foreign key
            $table->foreignId('categorie_id')->nullable()->after('nom')->constrained('categories')->onDelete('set null');
            
            // Rename image_path to image
            if (Schema::hasColumn('articles', 'image_path')) {
                $table->renameColumn('image_path', 'image');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            // Drop foreign key and column
            if (Schema::hasColumn('articles', 'categorie_id')) {
                $table->dropForeignKeyIfExists(['categorie_id']);
                $table->dropColumn('categorie_id');
            }
            
            // Rename image back to image_path
            if (Schema::hasColumn('articles', 'image')) {
                $table->renameColumn('image', 'image_path');
            }
        });
    }
};
