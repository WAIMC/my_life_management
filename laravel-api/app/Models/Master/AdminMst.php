<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class AdminMst extends Model
{
    protected $table = 'admin_mst';

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
        'is_delete',
        'remember_token',
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
        'birth' => 'datetime',
        'gender' => 'integer',
        'status' => 'integer',
        'is_active' => 'boolean',
        'avatar' => 'string',
        'email_verified_at' => 'datetime',
        'is_delete' => 'boolean',
        'remember_token' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
