<?php

namespace App\Repositories\Landlord;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class DatabaseRepository
{
    public function __construct() {

    }
    public function createDatabase(): string|bool
    {
        Log::info("creating a new tenant database - started", ['process' => '[database-repository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        return $this->tenantDatabaseMysqlUser();
    }
    public function deleteDatabase($database_name): void
    {
        Log::info("deleting a database ($database_name) - started", ['process' => '[database-repository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        $this->deleteDatabaseDirect($database_name);
    }
    public function tenantDatabaseMysqlUser(): bool|string
    {
        Log::info("create tenant database (method: mysql_user)  - started", ['process' => '[database-repository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);

        if (!$database_name = $this->createDatabaseDirect('score_saas_')) {
            Log::error("create tenant database failed - database could not be created", ['process' => '[database-repository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }

        //add user to database - does not seem to be required because the mysql user that created the database already has permissions on the database
        /*
        if (!$this->grantDatabaseDirect($database_name, env('DB_USERNAME'), env('DB_HOST'))) {
        Log::error("create tenant database failed - granting database permission failed", ['process' => '[database-repository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        return false;
        }
         */

        //all ok
        Log::info("create tenant database ($database_name) - completed", ['process' => '[database-repository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        return $database_name;

    }
    public function createDatabaseDirect($prefix = ''): bool|string
    {
        $database_name = databaseName($prefix);

        Log::info("creating database started", ['process' => '[database-repository][direct]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);

        //create a new database
        try {
            DB::connection('tenant')->statement("CREATE DATABASE $database_name");
        } catch (Exception $e) {
            Log::critical("creating database failed (" . $e->getMessage() . ")", ['process' => '[create-tenant-database][direct]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'database_name' => $database_name]);
            return false;
        }
        Log::info("database ($database_name) created", ['process' => '[database-repository][direct]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);

        return $database_name;

    }
    public function deleteDatabaseDirect($database_name = ''): bool
    {
        if ($database_name == '') {
            Log::error("deleting a database failed - no database name was provided", ['process' => '[database-repository][direct]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        }
        try {
            DB::statement('DROP DATABASE IF EXISTS ' . $database_name);
        } catch (Exception $e) {
            Log::critical("deleting a database ($database_name) failed - (" . $e->getMessage() . ")", ['process' => '[create-tenant-database][direct]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'database_name' => $database_name]);
            return false;
        }
        Log::info("deleting database ($database_name) - completed", ['process' => '[database-repository][direct]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        return true;
    }
}
