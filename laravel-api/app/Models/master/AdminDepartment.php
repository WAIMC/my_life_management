<?php

namespace App\Models\master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminDepartment extends Model
{
  use HasFactory;

  public const LENGTH_ATTR = [
    0 => 0
  ];
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 't_admin_department';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['admin_id', 'department_id'];

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = true;

  /**
   * Attributes for api
   *
   * @return array<string, string>
   */
  public static function attributes(): array
  {
    return [
      'admin_id'      => 'Admin id',
      'department_id' => 'Department id',
    ];
  }
}
