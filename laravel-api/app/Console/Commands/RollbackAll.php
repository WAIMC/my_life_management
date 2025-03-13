<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RollbackAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:rollback-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback all migration steps in reverse order';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $paths = [
            'database/migrations/views',
            'database/migrations/triggers',
            'database/migrations/tables/histories',
            'database/migrations/tables/masters',
        ];

        foreach ($paths as $path) {
            $this->info("Rolling back: $path");
            $this->call('migrate:rollback', ['--path' => $path]);
        }

        $this->info('All rollbacks completed successfully.');
    }
}
