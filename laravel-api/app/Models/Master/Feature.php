<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'feature_mst';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['name', 'group_name', 'description', 'status'];

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = true;
}
