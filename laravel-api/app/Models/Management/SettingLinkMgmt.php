<?php

namespace App\Models\Management;

use App\Models\History\Management\SettingLinkMgmtHist;
use App\Traits\HasSoftDelete;
use App\Traits\HasHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SettingLinkMgmt extends Model
{
  use HasSoftDelete, HasHistory, HasFactory;

  protected $table = 'setting_link_mgmt';

  /**
   * The attributes that are mass assignable.
   *
   * @var string[]
   */
  protected $fillable = [
    'key',
    'value',
    'is_delete',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'id' => 'integer',
    'key' => 'string',
    'value' => 'string',
    'is_delete' => 'boolean',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
  ];

  /**
   * Get the history records for the setting link.
   *
   * @return HasMany
   */
  public function history(): HasMany
  {
    return $this->hasMany(SettingLinkMgmtHist::class, 'setting_link_mgmt_id');
  }
}
