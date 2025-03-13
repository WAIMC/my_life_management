<?php

namespace App\Models\master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolicyDepartment extends Model
{
  use HasFactory;

  protected $table = 't_policy_department';

  public const LENGTH_ATTR = [
    0   => 0,
    20  => 20, // table_name
  ];

  /**
   * The attributes that are mass assignable.
   *
   * @var string[]
   */
  protected $fillable = [
    'table_name',
    'row_id',
  ];

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = true;

  /**
   * Get custom attributes for validator errors.
   *
   * @return array<string, string>
   */
  public static function attributes(): array
  {
    return [
      'id'         => 'Policy department ID',
      'table_name' => 'table name',
      'row_id'     => 'row id',
      'created_at' => 'created at',
      'updated_at' => 'created at',
    ];
  }
}
