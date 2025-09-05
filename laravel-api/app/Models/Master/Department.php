<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
  use HasFactory;

  protected $table = 'department_mst';

  /**
   * The attributes that are mass assignable.
   *
   * @var string[]
   */
  protected $fillable = ['code', 'name', 'status'];

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = true;
}
