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
        Schema::table('ecritures', function (Blueprint $table) {
            // Remove old columns
            $table->dropForeign(['banque_id']);
            $table->dropForeign(['categorie_id']);
            $table->dropColumn(['banque_id', 'type', 'montant', 'date_ecriture', 'reference', 'description', 'categorie_id']);
        });
        
        Schema::table('ecritures', function (Blueprint $table) {
            // Add new columns
            $table->string('ref')->nullable()->after('id');
            $table->text('description')->after('ref');
            $table->date('date_valeur')->after('description');
            $table->string('type')->nullable()->after('date_valeur');
            $table->string('numero')->nullable()->after('type');
            $table->string('tiers_utilisateur')->nullable()->after('numero');
            $table->foreignId('compte_bancaire_id')->after('tiers_utilisateur')->constrained('banques')->onDelete('cascade');
            $table->foreignId('categorie_id')->nullable()->after('compte_bancaire_id')->constrained('categories_ecriture')->onDelete('set null');
            $table->decimal('debit', 15, 2)->default(0)->after('categorie_id');
            $table->decimal('credit', 15, 2)->default(0)->after('debit');
            $table->decimal('solde', 15, 2)->default(0)->after('credit');
            $table->string('releve')->nullable()->after('solde');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ecritures', function (Blueprint $table) {
            $table->dropForeign(['compte_bancaire_id']);
            $table->dropForeign(['categorie_id']);
            $table->dropColumn([
                'ref', 'description', 'date_valeur', 'type', 'numero', 
                'tiers_utilisateur', 'compte_bancaire_id', 'categorie_id', 'debit', 'credit', 'solde', 'releve'
            ]);
        });
        
        Schema::table('ecritures', function (Blueprint $table) {
            $table->foreignId('banque_id')->constrained('banques')->onDelete('cascade');
            $table->foreignId('categorie_id')->nullable()->constrained('categories_ecriture')->onDelete('set null');
            $table->enum('type', ['entree', 'sortie']);
            $table->decimal('montant', 15, 2);
            $table->text('description');
            $table->date('date_ecriture');
            $table->string('reference')->nullable();
        });
    }
};
