<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run all migration steps in order';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $paths = [
            'database/migrations/tables/masters',
            'database/migrations/tables/histories',
            'database/migrations/triggers',
            'database/migrations/views',
        ];

        foreach ($paths as $path) {
            $this->info("Migrating: $path");
            $this->call('migrate', ['--path' => $path]);
        }

        $this->info('All migrations completed successfully.');
    }
}
