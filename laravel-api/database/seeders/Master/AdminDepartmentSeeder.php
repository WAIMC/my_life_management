<?php

namespace Database\Seeders\Master;

use App\Models\Master\AdminDepartment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AdminDepartment::factory()->count(10)->create();
    }
}
