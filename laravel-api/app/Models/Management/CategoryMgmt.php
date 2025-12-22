<?php

namespace App\Models\Management;

use App\Models\History\Management\CategoryMgmtHist;
use App\Traits\HasSoftDelete;
use App\Traits\HasStatus;
use App\Traits\HasHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryMgmt extends Model
{
  use HasSoftDelete, HasStatus, HasHistory, HasFactory;

  protected $table = 'category_mgmt';

  /**
   * The attributes that are mass assignable.
   *
   * @var string[]
   */
  protected $fillable = [
    'parent_id',
    'name',
    'slug',
    'description',
    'status',
    'is_display',
    'rank_order',
    'is_delete',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'id' => 'integer',
    'parent_id' => 'integer',
    'name' => 'string',
    'slug' => 'string',
    'description' => 'string',
    'status' => 'integer',
    'is_display' => 'boolean',
    'rank_order' => 'integer',
    'is_delete' => 'boolean',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
  ];

  /**
   * Get the parent category.
   *
   * @return BelongsTo
   */
  public function parent(): BelongsTo
  {
    return $this->belongsTo(CategoryMgmt::class, 'parent_id');
  }

  /**
   * Get the child categories.
   *
   * @return HasMany
   */
  public function children(): HasMany
  {
    return $this->hasMany(CategoryMgmt::class, 'parent_id');
  }

  /**
   * Get the products in this category.
   *
   * @return HasMany
   */
  public function products(): HasMany
  {
    return $this->hasMany(ProductMgmt::class, 'category_mgmt_id');
  }

  /**
   * Get the skills associated with this category.
   *
   * @return BelongsToMany
   */
  public function skills(): BelongsToMany
  {
    return $this->belongsToMany(
      SkillMgmt::class,
      'category_skill_mgmt',
      'category_mgmt_id',
      'skill_mgmt_id'
    )->withTimestamps();
  }

  /**
   * Get the history records for the category.
   *
   * @return HasMany
   */
  public function history(): HasMany
  {
    return $this->hasMany(CategoryMgmtHist::class, 'category_mgmt_id');
  }
}
