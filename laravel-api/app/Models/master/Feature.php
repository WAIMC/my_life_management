<?php

namespace App\Models\master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
  use HasFactory;

  public const FEATURE_STATUS = [
    'inactive' => 0,
    'active' => 1,
    'planned' => 2,
  ];

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 't_feature';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['name', 'group_name', 'description', 'status'];

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = true;

  /**
   * Attributes for feature
   *
   * @return array<string, string>
   */
  public static function attributes(): array
  {
    return [
      'table_name' => 'Feature',
      'id' => 'Feature ID',
      'name' => 'Feature name',
      'group_name' => 'Feature group name',
      'description' => 'Feature description',
      'status' => 'Feature status',
    ];
  }
}
