<?php

namespace App\Models\master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 't_category';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['parent_id', 'name', 'slug', 'description', 'status', 'is_display', 'rank_order'];

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = true;
}
