<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bons_de_commande', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fournisseur_id')->constrained('fournisseurs')->onDelete('cascade');
            $table->date('order_date');
            $table->date('expected_delivery_date')->nullable();
            $table->enum('status', ['DRAFT', 'CONFIRMED', 'RECEIVED', 'COMPLETED', 'CANCELLED'])
                ->default('DRAFT');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes for better query performance
            $table->index('fournisseur_id');
            $table->index('status');
            $table->index('order_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bons_de_commande');
    }
};
