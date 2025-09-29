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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_quiz');
            $table->unsignedBigInteger('id_user');
            $table->integer('attempt_number');
            $table->decimal('score', 5, 2);
            $table->decimal('percentage', 5, 2);
            $table->integer('total_questions');
            $table->integer('correct_answers');
            $table->boolean('passed')->default(false);
            $table->datetime('started_at');
            $table->datetime('completed_at');
            $table->timestamps();

            // Clés étrangères
            $table->foreign('id_quiz')->references('id_quiz')->on('quizzes')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');

            // Index unique pour éviter les doublons
            $table->unique(['id_quiz', 'id_user', 'attempt_number'], 'unique_quiz_user_attempt');
            $table->index('passed');
            $table->index('completed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
