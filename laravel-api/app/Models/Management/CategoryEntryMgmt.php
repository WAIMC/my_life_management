<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategoryEntryMgmt extends Model
{
  protected $table = 'category_entry_mgmt';
  public $incrementing = false;
  protected $primaryKey = null;

  /**
   * The attributes that are mass assignable.
   *
   * @var string[]
   */
  protected $fillable = [
    'category_mgmt_id',
    'entry_mgmt_id',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'category_mgmt_id' => 'integer',
    'entry_mgmt_id' => 'integer',
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
   * Get the entry that owns this relationship.
   *
   * @return BelongsTo
   */
  public function entry(): BelongsTo
  {
    return $this->belongsTo(EntryMgmt::class, 'entry_mgmt_id');
  }
}
