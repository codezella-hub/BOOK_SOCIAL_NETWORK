<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feed_back_books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('rating'); // Note de 1 à 5
            $table->text('comment')->nullable();
            $table->enum('sentiment', ['positive', 'neutral', 'negative'])->nullable();
            $table->timestamps();

            // Un utilisateur ne peut noter qu'une fois un livre
            $table->unique(['book_id', 'user_id']);

            // Index pour les recherches
            $table->index(['book_id', 'rating']);
            $table->index(['book_id', 'sentiment']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feed_back_books');
    }
};
