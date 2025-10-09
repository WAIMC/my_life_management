<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetupProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up the project: migrate, seed, and sync features';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
      $this->info('Running migrations...');
      $this->call('migrate:all');
      $this->info('Migrations completed.');

//      $this->info('Seeding database...');
//      Artisan::call('db:seed');
//      $this->info('Database seeded.');

      $this->info('Syncing features...');
      $this->call('app:sync-api-permissions');
      $this->info('Features synced.');

      $this->info('Project setup complete.');
    }
}
