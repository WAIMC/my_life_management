<?php

namespace Database\Seeders;

use App\Models\Management\CategoryMgmt;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // Create root account with all permissions
    $this->call([
      RootAccountSeeder::class,
    ]);
  }
}
