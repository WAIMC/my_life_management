<?php

namespace App\Models\master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
  use HasFactory;

  protected $table = 't_department';

  public const LENGTH_ATTR = [
    0   => 0,
    50  => 50, // code, name
  ];

  public const STATUS = [
    'inactive'  => 0,
    'active'    => 1,
    'planned'   => 2,
  ];

  /**
   * The attributes that are mass assignable.
   *
   * @var string[]
   */
  protected $fillable = ['code', 'name', 'status'];

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
      'id'         => 'Department ID',
      'code'       => 'code',
      'name'       => 'name',
      'status'     => 'status',
      'created_at' => 'created at',
      'updated_at' => 'created at',
    ];
  }
}
