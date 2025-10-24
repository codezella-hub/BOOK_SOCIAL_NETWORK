<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book_transaction_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->foreignId('borrower_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('lender_id')->constrained('users')->onDelete('cascade');
            $table->date('borrowed_date');
            $table->date('due_date');
            $table->date('returned_date')->nullable();
            $table->boolean('returned')->default(false);
            $table->boolean('returned_approved')->default(false);
            $table->enum('status', ['pending', 'approved', 'rejected', 'borrowed', 'returned', 'completed'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Index pour les recherches fréquentes
            $table->index(['book_id', 'borrower_id']);
            $table->index(['lender_id', 'status']);
            $table->index(['due_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_transaction_histories');
    }
};
