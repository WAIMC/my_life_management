<?php

namespace App\Models\Management;

use App\Models\History\Management\BannerMgmtHist;
use App\Traits\HasSoftDelete;
use App\Traits\HasStatus;
use App\Traits\HasHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class BannerMgmt extends Model
{
  use HasSoftDelete, HasStatus, HasHistory, HasFactory;

  protected $table = 'banner_mgmt';

  /**
   * The attributes that are mass assignable.
   *
   * @var string[]
   */
  protected $fillable = [
    'title',
    'slug',
    'description',
    'link',
    'image',
    'position',
    'status',
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
    'slug' => 'string',
    'description' => 'string',
    'link' => 'string',
    'image' => 'string',
    'position' => 'string',
    'status' => 'integer',
    'is_delete' => 'boolean',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
  ];

  /**
   * Get the history records for the banner.
   *
   * @return HasMany
   */
  public function history(): HasMany
  {
    return $this->hasMany(BannerMgmtHist::class, 'banner_mgmt_id');
  }
}
