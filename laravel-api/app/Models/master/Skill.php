<?php

namespace App\Models\master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
  use HasFactory;

  public const LENGTH_ATTR = [
    0   => 0,
    50  => 50, // name. slug
  ];

  public const SKILL_STATUS = [
    'inactive'  => 0,
    'active'    => 1,
    'waiting'   => 2,
    'suspended' => 3,
  ];

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 't_skill';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['parent_id', 'name', 'slug', 'status', 'rank_order', 'is_display'];

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
      'id'          => 'Skill ID',
      'parent_id'   => 'Parent id',
      'name'        => 'Name',
      'slug'        => 'Slug',
      'status'      => 'Status',
      'is_display'  => 'Display',
      'rank_order'  => 'Rank order',
      'created_at'  => 'created at',
      'updated_at'  => 'created at',
    ];
  }
}
