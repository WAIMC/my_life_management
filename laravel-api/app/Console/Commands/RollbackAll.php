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
            'database/migrations/View',
            'database/migrations/Trigger',
            'database/migrations/Table/History/Management',
            'database/migrations/Table/History/Master',
            'database/migrations/Table/Management',
            'database/migrations/Table/Master',
            'database/migrations/Table/Other',
        ];

        foreach ($paths as $path) {
            $this->info("Rolling back: $path");
            $this->call('migrate:rollback', ['--path' => $path]);
        }

        $this->info('All rollbacks completed successfully.');
    }
}
