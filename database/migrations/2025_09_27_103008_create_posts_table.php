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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->text('content_P'); 
            $table->dateTime('P_created_at')->useCurrent();

            $table->foreignId('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreignId('topic_id')
                ->references('id')
                ->on('topics')
                ->onDelete('cascade');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
