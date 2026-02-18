<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bons_retour_fournisseur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bon_de_commande_id')->constrained('bons_de_commande')->onDelete('cascade');
            $table->foreignId('fournisseur_id')->constrained('fournisseurs')->onDelete('cascade');
            $table->date('return_date');
            $table->enum('return_type', ['REPLACEMENT', 'REFUND'])->default('REFUND');
            $table->string('return_reason')->nullable();
            $table->enum('status', ['PENDING', 'COMPLETED', 'CANCELLED'])
                ->default('PENDING');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('bon_de_commande_id');
            $table->index('fournisseur_id');
            $table->index('status');
            $table->index('return_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bons_retour_fournisseur');
    }
};
