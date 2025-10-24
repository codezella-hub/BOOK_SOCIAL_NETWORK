<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            // colonne puis index puis FK (ordre compatible MySQL)
            $table->unsignedBigInteger('parentId')->nullable()->after('postId');

            $table->index(['postId','parentId'], 'comments_post_parent_idx');

            $table->foreign('parentId', 'comments_parent_fk')
                  ->references('id')->on('comments')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign('comments_parent_fk');
            $table->dropIndex('comments_post_parent_idx');
            $table->dropColumn('parentId');
        });
    }
};
