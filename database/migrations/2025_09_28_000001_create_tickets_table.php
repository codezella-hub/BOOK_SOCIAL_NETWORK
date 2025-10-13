<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evenement_id')->constrained('evenements')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('code')->unique();
            $table->timestamp('issued_at')->nullable();
            $table->string('pdf_path')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('redeemed_at')->nullable();
            $table->timestamps();

            $table->unique(['evenement_id', 'user_id']); // one active ticket per user per event
        });
    }

    public function down(): void {
        Schema::dropIfExists('tickets');
    }
};
