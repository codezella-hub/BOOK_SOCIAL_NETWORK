<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('comment_likes', function (Blueprint $table) {
            $table->id();
            $table->dateTime('CL_created_at')->useCurrent();

            $table->foreignId('liked_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('commentId')->constrained('comments')->cascadeOnDelete();

            $table->unique(['liked_by','commentId']); // 1 like par (user,comment)
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('comment_likes');
    }
};