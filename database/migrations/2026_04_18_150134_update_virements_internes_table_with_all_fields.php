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
        Schema::table('virements_internes', function (Blueprint $table) {
            // Remove old foreign keys first
            $table->dropForeign(['banque_source_id']);
            $table->dropForeign(['banque_destination_id']);
        });
        
        Schema::table('virements_internes', function (Blueprint $table) {
            // Remove old columns
            $table->dropColumn(['banque_source_id', 'banque_destination_id', 'date_virement', 'reference']);
            
            // Add new columns
            $table->foreignId('de_compte_id')->after('id')->constrained('banques')->onDelete('cascade');
            $table->foreignId('vers_compte_id')->after('de_compte_id')->constrained('banques')->onDelete('cascade');
            $table->string('type')->default('Virement bancaire')->after('vers_compte_id');
            $table->date('date')->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('virements_internes', function (Blueprint $table) {
            $table->dropForeign(['de_compte_id']);
            $table->dropForeign(['vers_compte_id']);
        });
        
        Schema::table('virements_internes', function (Blueprint $table) {
            $table->dropColumn(['de_compte_id', 'vers_compte_id', 'type', 'date']);
            
            $table->foreignId('banque_source_id')->constrained('banques')->onDelete('cascade');
            $table->foreignId('banque_destination_id')->constrained('banques')->onDelete('cascade');
            $table->date('date_virement');
            $table->string('reference')->nullable();
        });
    }
};
