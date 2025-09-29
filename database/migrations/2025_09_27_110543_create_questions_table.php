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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_quiz');
            $table->text('question_text');
            $table->string('option_a');
            $table->string('option_b');
            $table->string('option_c');
            $table->string('option_d');
            $table->string('correct_answer'); // A, B, C, ou D
            $table->decimal('points', 5, 2);
            $table->integer('order_position');
            $table->timestamps();

            // Clé étrangère vers la table quizzes
            $table->foreign('id_quiz')->references('id_quiz')->on('quizzes')->onDelete('cascade');

            // Index pour améliorer les performances
            $table->index('order_position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
