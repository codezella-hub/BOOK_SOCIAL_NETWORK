<?php
// 1. Migration Quiz modifiée (sans relation Book)

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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id('id_quiz');
            $table->string('title');
            $table->text('description');
            $table->enum('difficulty_level', ['beginner', 'intermediate', 'advanced']);
            $table->integer('nb_questions');
            $table->integer('max_attempts');
            $table->integer('time_limit'); // en minutes
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('id_book'); // Simple attribut, pas de relation
            $table->timestamps();

            // Suppression de la clé étrangère - plus de relation avec table books
            // Index pour améliorer les performances
            $table->index('is_active');
            $table->index('difficulty_level');
            $table->index('id_book'); // Index pour les recherches par livre
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
