<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_mouvements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('articles')->cascadeOnDelete();
            $table->enum('type', ['entree', 'sortie']); // Entrée (IN) or Sortie (OUT)
            $table->decimal('quantite', 10, 2);
            $table->string('reference_type')->nullable(); // BonLivraison, BonRetour, Ajustement, etc.
            $table->unsignedBigInteger('reference_id')->nullable(); // ID of the related document
            $table->string('motif')->nullable(); // Reason: livraison, retour, ajustement, etc.
            $table->date('date');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // User who made the movement
            $table->text('description')->nullable(); // Additional details
            $table->timestamps();

            // Indexes for better query performance
            $table->index('article_id');
            $table->index('type');
            $table->index('reference_type');
            $table->index('date');
            $table->index(['article_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_mouvements');
    }
};
