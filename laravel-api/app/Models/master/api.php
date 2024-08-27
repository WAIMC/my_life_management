<?php

namespace App\Models\master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api extends Model
{
  use HasFactory;

  public const TYPE_OF_METHOD = [
    0 => "GET",
    1 => "POST",
    2 => "PUT",
    3 => "PATCH",
    4 => "DELETE",
  ];

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 't_api';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['type', 'name', 'path', 'is_valid', 'feature_id'];

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
      'table_name' => 'api',
      'id'         => 'API ID',
      'type'       => 'Type of api',
      'name'       => 'Api name',
      'path'       => 'Api path',
      'is_valid'   => 'Api valid',
      'feature_id' => 'Feature ID',
    ];
  }
}
