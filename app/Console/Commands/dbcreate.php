<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class DbCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:create {name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new MySQL database based on the database config file or the provided name';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $schemaName = $this->argument('name') ?: config("database.connections.mysql.database");
        $charset = config("database.connections.mysql.charset", 'utf8mb4');
        $collation = config("database.connections.mysql.collation", 'utf8mb4_unicode_ci');

        // Supprimer temporairement le nom de DB
        Config::set('database.connections.mysql.database', null);

        // Purger et reconnecter MySQL pour prendre en compte la config modifiée
        DB::purge('mysql');
        DB::reconnect('mysql');

        // Créer la base
        $query = "CREATE DATABASE IF NOT EXISTS `$schemaName` CHARACTER SET $charset COLLATE $collation;";
        DB::statement($query);

        $this->info("✅ Database `$schemaName` created successfully.");
    }
}
