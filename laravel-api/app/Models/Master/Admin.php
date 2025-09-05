<?php

namespace App\Models\Master;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
  use Notifiable;

  protected $table = 'admin_mst';

  /**
   * The attributes that are mass assignable.
   *
   * @var string[]
   */
  protected $fillable = [
    'email',
    'user_name',
    'first_name',
    'last_name',
    'password',
    'address',
    'phone_number',
    'birth',
    'gender',
    'status',
    'is_active',
    'avatar',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];
}
