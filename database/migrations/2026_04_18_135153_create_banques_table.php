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
        Schema::create('banques', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->enum('type', ['banque', 'caisse', 'autres'])->default('banque');
            $table->string('numero_compte')->nullable();
            $table->decimal('solde_initial', 15, 2)->default(0);
            $table->decimal('solde_actuel', 15, 2)->default(0);
            $table->string('devise', 10)->default('DH');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banques');
    }
};
