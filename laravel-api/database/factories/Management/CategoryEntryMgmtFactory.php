<?php

namespace Database\Factories\Management;

use App\Models\Management\CategoryMgmt;
use App\Models\Management\CategoryEntryMgmt;
use App\Models\Management\EntryMgmt;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryEntryMgmtFactory extends Factory
{
  protected $model = CategoryEntryMgmt::class;

  public function definition()
  {
    return [
      'category_mgmt_id' => CategoryMgmt::factory(),
      'entry_mgmt_id' => EntryMgmt::factory(),
    ];
  }
}
