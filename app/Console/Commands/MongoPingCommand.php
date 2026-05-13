<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MongoPingCommand extends Command
{
    protected $signature = 'mongo:ping';

    protected $description = 'Ping the configured MongoDB connection (uses DB_*_MONGO from .env)';

    public function handle(): int
    {
        if (! extension_loaded('mongodb')) {
            $this->error('PHP extension "mongodb" is not loaded. Install ext-mongodb and enable it in php.ini.');

            return self::FAILURE;
        }

        try {
            $result = DB::connection('mongodb')->getMongoDB()->command(['ping' => 1]);
            $ok = $result->toArray()[0]['ok'] ?? null;
            if ($ok == 1.0) {
                $db = (string) config('database.connections.mongodb.database');
                $this->info("MongoDB OK — connected to database \"{$db}\".");

                return self::SUCCESS;
            }
            $this->warn('Unexpected ping response: '.json_encode($result->toArray()));

            return self::FAILURE;
        } catch (\Throwable $e) {
            $this->error('MongoDB connection failed: '.$e->getMessage());
            $this->line('Check that mongod is running and DB_HOST_MONGO / DB_PORT_MONGO / credentials in .env are correct.');

            return self::FAILURE;
        }
    }
}
