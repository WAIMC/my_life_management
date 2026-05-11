<?php

namespace App\Models\Management;

use App\Models\History\Management\EntryDescriptionMgmtHist;
use App\Traits\HasSoftDelete;
use App\Traits\HasStatus;
use App\Traits\HasHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EntryDescriptionMgmt extends Model
{
  use HasSoftDelete, HasStatus, HasHistory, HasFactory;

  protected $table = 'entry_description_mgmt';

  /**
   * The attributes that are mass assignable.
   *
   * @var string[]
   */
  protected $fillable = [
    'title',
    'summary',
    'article',
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
    'title' => 'string',
    'summary' => 'string',
    'article' => 'array',
    'status' => 'integer',
    'is_display' => 'boolean',
    'rank_order' => 'integer',
    'is_delete' => 'boolean',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
  ];



  /**
   * Get the history records for the entry description.
   *
   * @return HasMany
   */
  public function history(): HasMany
  {
    return $this->hasMany(EntryDescriptionMgmtHist::class, 'entry_description_mgmt_id');
  }
}
