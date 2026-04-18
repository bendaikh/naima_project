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
        Schema::table('banques', function (Blueprint $table) {
            // Add new columns first
            $table->string('ref')->nullable()->after('id');
            $table->string('etat')->default('Ouvert')->after('devise');
            $table->string('pays')->nullable()->after('etat');
            $table->string('departement')->nullable()->after('pays');
            $table->string('domiciliation')->nullable()->after('departement');
            $table->string('web')->nullable()->after('domiciliation');
            $table->date('date')->nullable()->after('solde_actuel');
            $table->decimal('solde_minimum_autorise', 15, 2)->nullable()->after('date');
            $table->decimal('solde_minimum_desire', 15, 2)->nullable()->after('solde_minimum_autorise');
            $table->string('nom_banque')->nullable()->after('solde_minimum_desire');
            $table->string('code_iban')->nullable()->after('nom_banque');
            $table->string('code_bic_swift')->nullable()->after('code_iban');
            $table->string('nom_proprietaire')->nullable()->after('numero_compte');
            $table->text('adresse_proprietaire')->nullable()->after('nom_proprietaire');
            $table->string('code_postal_proprietaire')->nullable()->after('adresse_proprietaire');
            $table->string('ville_proprietaire')->nullable()->after('code_postal_proprietaire');
            $table->string('pays_proprietaire')->nullable()->after('ville_proprietaire');
            $table->string('compte_comptable')->nullable()->after('pays_proprietaire');
            $table->string('code_journal_comptable')->nullable()->after('compte_comptable');
        });
        
        Schema::table('banques', function (Blueprint $table) {
            // Rename columns
            $table->renameColumn('nom', 'libelle');
            $table->renameColumn('type', 'type_compte');
            $table->renameColumn('description', 'commentaire');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banques', function (Blueprint $table) {
            $table->renameColumn('libelle', 'nom');
            $table->renameColumn('type_compte', 'type');
            $table->renameColumn('commentaire', 'description');
        });
        
        Schema::table('banques', function (Blueprint $table) {
            $table->dropColumn([
                'ref', 'etat', 'pays', 'departement', 'domiciliation', 'web', 'date',
                'solde_minimum_autorise', 'solde_minimum_desire', 'nom_banque',
                'code_iban', 'code_bic_swift', 'nom_proprietaire', 'adresse_proprietaire',
                'code_postal_proprietaire', 'ville_proprietaire', 'pays_proprietaire',
                'compte_comptable', 'code_journal_comptable'
            ]);
        });
    }
};
