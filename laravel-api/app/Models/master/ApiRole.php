<?php

namespace App\Models\master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiRole extends Model
{
  use HasFactory;

  public const LENGTH_ATTR = [
    0   => 0,
  ];

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 't_api_role';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['api_id', 'role_id'];

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
      'api_id' => 'API id',
      'role_id' => 'Role id',
    ];
  }
}
