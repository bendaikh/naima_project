<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avoirs_fournisseur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fournisseur_id')->constrained('fournisseurs')->onDelete('cascade');
            $table->foreignId('bon_retour_fournisseur_id')
                ->constrained('bons_retour_fournisseur')->onDelete('cascade');
            $table->date('avoir_date');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['PENDING', 'RECEIVED', 'CANCELLED'])
                ->default('PENDING');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('fournisseur_id');
            $table->index('bon_retour_fournisseur_id');
            $table->index('status');
            $table->index('avoir_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avoirs_fournisseur');
    }
};
