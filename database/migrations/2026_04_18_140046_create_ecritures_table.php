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
        Schema::create('ecritures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('banque_id')->constrained('banques')->onDelete('cascade');
            $table->foreignId('categorie_id')->nullable()->constrained('categories_ecriture')->onDelete('set null');
            $table->enum('type', ['entree', 'sortie']);
            $table->decimal('montant', 15, 2);
            $table->text('description');
            $table->date('date_ecriture');
            $table->string('reference')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecritures');
    }
};
