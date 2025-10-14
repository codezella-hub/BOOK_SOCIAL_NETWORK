<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('evenements', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('title');
    $table->string('slug')->unique();
    $table->string('summary', 280)->nullable();
    $table->text('description')->nullable();

    // Use datetime to avoid strict-mode default issues
    $table->dateTime('starts_at');
    $table->dateTime('ends_at');

    $table->string('timezone', 64)->default('UTC');
    $table->string('location_text', 255)->nullable();
    $table->enum('status', ['draft', 'published', 'cancelled'])->default('draft');
    $table->enum('visibility', ['public', 'private'])->default('public');
    $table->unsignedInteger('capacity')->nullable();
    $table->string('cover_image_path')->nullable();

    // Optional event lifecycle fields should be nullable
    $table->dateTime('published_at')->nullable();
    $table->dateTime('cancelled_at')->nullable();

    $table->timestamps(); // these are nullable in modern Laravel schemas

    $table->index(['status', 'starts_at']);
    $table->index(['starts_at']);
});

    }

    public function down(): void {
        Schema::dropIfExists('evenements');
    }
};
