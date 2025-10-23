<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // php artisan make:migration add_geo_to_evenements_table
    public function up(): void
    {
        Schema::table('evenements', function (Blueprint $table) {
            // Add lat/lng for nearby queries
            // DECIMAL(10,7) is a common choice for ~1.11cm precision at equator
            $table->decimal('lat', 10, 7)->nullable()->after('location_text');
            $table->decimal('lng', 10, 7)->nullable()->after('lat');

            // Optional: add simple indexes for faster filtering/sorting by geolocation later
            $table->index('lat');
            $table->index('lng');
        });
    }

    public function down(): void
    {
        Schema::table('evenements', function (Blueprint $table) {
            // Drop only what this migration added
            $table->dropIndex(['lat']);   // drops index on `lat`
            $table->dropIndex(['lng']);   // drops index on `lng`
            $table->dropColumn(['lat','lng']);
        });
    }
};
