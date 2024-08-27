<?php

namespace App\Models\master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 't_admin_role';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['admin_id', 'role_id'];

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
      'admin_id' => 'Admin id',
      'role_id' => 'Role id',
    ];
  }
}
