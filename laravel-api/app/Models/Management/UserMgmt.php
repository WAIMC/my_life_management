<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Model;

class UserMgmt extends Model
{
    protected $table = 'user_mgmt';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
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
        'is_delete',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'email' => 'string',
        'user_name' => 'string',
        'password' => 'string',
        'first_name' => 'string',
        'last_name' => 'string',
        'address' => 'string',
        'phone_number' => 'string',
        'birth' => 'string',
        'gender' => 'integer',
        'status' => 'integer',
        'is_active' => 'boolean',
        'avatar' => 'string',
        'email_verified_at' => 'string',
        'remember_token' => 'string',
        'is_delete' => 'boolean',
        'created_at' => 'string',
        'updated_at' => 'string',
    ];
}
