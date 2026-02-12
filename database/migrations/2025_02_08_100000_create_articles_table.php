<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('articles')) {
            Schema::create('articles', function (Blueprint $table) {
                $table->id();
                $table->string('nom');
                $table->text('description')->nullable();
                $table->decimal('prix_vente', 10, 2);
                $table->decimal('prix_achat', 10, 2);
                $table->decimal('quantite', 10, 2);
                $table->string('unite');
                $table->timestamps();
                $table->engine = 'InnoDB';
            });
        } else {
            // Ensure existing table uses InnoDB engine for foreign key support
            DB::statement('ALTER TABLE articles ENGINE = InnoDB');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
