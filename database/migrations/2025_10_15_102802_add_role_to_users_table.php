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
        Schema::table('users', function (Blueprint $table) {
            // Ajoute une colonne 'role' de type string après la colonne 'email'
            // Lui donne la valeur 'user' par défaut pour tous les utilisateurs existants
            $table->string('role')->after('email')->default('user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Cette fonction permet d'annuler la migration si besoin
            $table->dropColumn('role');
        });
    }
};
