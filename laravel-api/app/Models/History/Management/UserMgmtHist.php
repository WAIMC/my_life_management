<?php

namespace App\Models\History\Management;

use Illuminate\Database\Eloquent\Model;

class UserMgmtHist extends Model
{
  protected $table = 'user_mgmt_hist';
  const UPDATED_AT = null;

  /**
   * The attributes that are mass assignable.
   *
   * @var string[]
   */
  protected $fillable = [
    'user_mgmt_id',
    'email',
    'user_name',
    'password',
    'first_name',
    'last_name',
    'address',
    'phone_number',
    'birth',
    'gender',
    'status',
    'is_active',
    'avatar',
    'email_verified_at',
    'remember_token',
    'action',
    'author_id',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'id' => 'integer',
    'user_mgmt_id' => 'integer',
    'email' => 'string',
    'user_name' => 'string',
    'password' => 'string',
    'first_name' => 'string',
    'last_name' => 'string',
    'address' => 'string',
    'phone_number' => 'string',
    'birth' => 'datetime',
    'gender' => 'integer',
    'status' => 'integer',
    'is_active' => 'boolean',
    'avatar' => 'string',
    'email_verified_at' => 'datetime',
    'remember_token' => 'string',
    'action' => 'integer',
    'author_id' => 'integer',
    'created_at' => 'datetime',
  ];
}
