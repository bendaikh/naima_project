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
        Schema::create('virements_internes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('banque_source_id')->constrained('banques')->onDelete('cascade');
            $table->foreignId('banque_destination_id')->constrained('banques')->onDelete('cascade');
            $table->decimal('montant', 15, 2);
            $table->date('date_virement');
            $table->text('description')->nullable();
            $table->string('reference')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('virements_internes');
    }
};
