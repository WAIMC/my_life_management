<?php

namespace App\Models\master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentManagement extends Model
{
  use HasFactory;

  protected $table = 't_department_management';

  public const LENGTH_ATTR = [
    0   => 0,
  ];

  /**
   * The attributes that are mass assignable.
   *
   * @var string[]
   */
  protected $fillable = [
    'department_id',
    'policy_department_id',
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
      'department_id'        => 'Department ID',
      'policy_department_id' => 'Policy department ID',
      'created_at'           => 'created at',
      'updated_at'           => 'created at',
    ];
  }
}
