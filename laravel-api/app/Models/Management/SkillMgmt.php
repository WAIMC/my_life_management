<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillMgmt extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'skill_mgmt';

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
}
