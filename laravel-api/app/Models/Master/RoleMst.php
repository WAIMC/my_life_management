<?php

namespace App\Models\Master;

use App\Models\History\Master\RoleMstHist;
use App\Traits\HasSoftDelete;
use App\Traits\HasStatus;
use App\Traits\HasHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoleMst extends Model
{
  use HasSoftDelete, HasStatus, HasHistory, HasFactory;

  protected $table = 'role_mst';

  /**
   * The attributes that are mass assignable.
   *
   * @var string[]
   */
  protected $fillable = [
    'name',
    'permission',
    'is_active',
    'is_delete',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'id' => 'integer',
    'name' => 'string',
    'permission' => 'string',
    'is_active' => 'boolean',
    'is_delete' => 'boolean',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
  ];

  /**
   * Get the admins associated with the role.
   *
   * @return BelongsToMany
   */
  public function admins(): BelongsToMany
  {
    return $this->belongsToMany(
      AdminMst::class,
      'admin_role_mst',
      'role_mst_id',
      'admin_mst_id'
    )->withTimestamps();
  }

  /**
   * Get the APIs associated with the role.
   *
   * @return BelongsToMany
   */
  public function apis(): BelongsToMany
  {
    return $this->belongsToMany(
      ApiMst::class,
      'api_role_mst',
      'role_mst_id',
      'api_mst_id'
    )->withTimestamps();
  }

  /**
   * Get the history records for the role.
   *
   * @return HasMany
   */
  public function history(): HasMany
  {
    return $this->hasMany(RoleMstHist::class, 'role_mst_id');
  }
}
