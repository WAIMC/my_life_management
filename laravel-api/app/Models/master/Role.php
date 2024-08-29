<?php

namespace App\Models\master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 't_role';

  public const IS_ACTIVE = [
    'disabled' => false,
    'enable'   => true
  ];

  public const LENGTH_ATTR = [
    0   => 0,
    30  => 30, // name
    50  => 50, // permission
  ];

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['name', 'permission', 'is_active'];

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = true;

  /**
   * Attributes for Role
   *
   * @return array<string, string>
   */
  public static function attributes(): array
  {
    return [
      'id'         => 'Role ID',
      'name'       => 'Role name',
      'permission' => 'Role description',
      'is_active'  => 'Active role',
    ];
  }
}
