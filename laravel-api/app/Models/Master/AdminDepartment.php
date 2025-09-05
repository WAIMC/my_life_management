<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminDepartment extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'admin_department_mst';

  public $incrementing = false;
  protected $primaryKey = null;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['admin_id', 'department_id'];

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = true;
}
