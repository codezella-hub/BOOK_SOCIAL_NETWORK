<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evenements', function (Blueprint $table) {
            // Add lat if missing
            if (!Schema::hasColumn('evenements', 'lat')) {
                $table->decimal('lat', 10, 7)->nullable()->after('location_text');
                $table->index('lat', 'evenements_lat_index');
            }

            // Add lng if missing
            if (!Schema::hasColumn('evenements', 'lng')) {
                $table->decimal('lng', 10, 7)->nullable()->after('lat');
                $table->index('lng', 'evenements_lng_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('evenements', function (Blueprint $table) {
            // Drop indexes if they exist, then columns if they exist
            if (Schema::hasColumn('evenements', 'lat')) {
                // index name must match what was created
                $table->dropIndex('evenements_lat_index');
                $table->dropColumn('lat');
            }
            if (Schema::hasColumn('evenements', 'lng')) {
                $table->dropIndex('evenements_lng_index');
                $table->dropColumn('lng');
            }
        });
    }
};
