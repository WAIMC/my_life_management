<?php

namespace App\Models\master;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
  use Notifiable;

  protected $table = 't_admin';
  public const TYPE = 'admin';
  public const ROOT = 'root';

  public static $status = [
    'inactive'  => 0,
    'active'    => 1,
    'waiting'   => 2,
    'suspended' => 3,
  ];

  public static $isActive = [
    'disabled' => false,
    'enable'   => true
  ];

  public static $gender = [
    'male'   => 0,
    'female' => 1
  ];

  public static $lengthAttr = [
    'min'    => 0,
    'twenty' => 20, // first_name, last_name, phone_number
    'thirty' => 30, // email, avatar
    'fifty'  => 50, // user_name
    'oneH'   => 100 // password, address
  ];

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

  /**
   * Get custom attributes for validator errors.
   *
   * @return array<string, string>
   */
  public static function attributes(): array
  {
    return [
      'email'             => 'email',
      'user_name'         => 'user name',
      'password'          => 'password',
      'first_name'        => 'first name',
      'last_name'         => 'last name',
      'address'           => 'address',
      'phone_number'      => 'phone_number',
      'birth'             => 'birth',
      'gender'            => 'gender',
      'status'            => 'status',
      'is_active'         => 'is active',
      'avatar'            => 'avatar',
      'email_verified_at' => 'email verified',
      'remember_token'    => 'remember token',
      'created_at'        => 'created at',
      'updated_at'        => 'created at',
    ];
  }
}
