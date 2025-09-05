<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'role_mst';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['name', 'permission', 'is_active'];

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = true;
}
