<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bons_de_commande_lignes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bon_de_commande_id')->constrained('bons_de_commande')->onDelete('cascade');
            $table->foreignId('article_id')->nullable()->constrained('articles')->onDelete('set null');
            $table->string('product_name')->nullable(); // For products not yet in the system
            $table->decimal('quantity', 10, 2);
            $table->decimal('purchase_price', 10, 2);
            $table->decimal('received_quantity', 10, 2)->default(0); // Quantity actually received
            $table->timestamps();

            // Indexes
            $table->index('bon_de_commande_id');
            $table->index('article_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bons_de_commande_lignes');
    }
};
