<?php

namespace App\Models\master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class api extends Model
{
  use HasFactory;

  public const TYPE_OF_METHOD = [
    0 => "GET",
    1 => "POST",
    2 => "PUT",
    3 => "PATCH",
    4 => "DELETE",
  ];

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 't_role';

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
