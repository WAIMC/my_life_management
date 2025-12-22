<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategorySkillMgmt extends Model
{
  protected $table = 'category_skill_mgmt';
  public $incrementing = false;
  protected $primaryKey = null;

  /**
   * The attributes that are mass assignable.
   *
   * @var string[]
   */
  protected $fillable = [
    'category_mgmt_id',
    'skill_mgmt_id',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'category_mgmt_id' => 'integer',
    'skill_mgmt_id' => 'integer',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
  ];

  /**
   * Get the category that owns this relationship.
   *
   * @return BelongsTo
   */
  public function category(): BelongsTo
  {
    return $this->belongsTo(CategoryMgmt::class, 'category_mgmt_id');
  }

  /**
   * Get the skill that owns this relationship.
   *
   * @return BelongsTo
   */
  public function skill(): BelongsTo
  {
    return $this->belongsTo(SkillMgmt::class, 'skill_mgmt_id');
  }
}
