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
        Schema::create('remises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donation_id')->constrained('donations')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('date_rendez_vous');
            $table->string('lieu');
            $table->enum('statut', ['en_attente', 'prevu', 'effectue', 'annule'])->default('en_attente');
            $table->timestamps();
            
            // Index pour améliorer les performances
            $table->index(['donation_id', 'statut']);
            $table->index(['user_id', 'statut']);
            $table->index(['admin_id', 'date_rendez_vous']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remises');
    }
};
