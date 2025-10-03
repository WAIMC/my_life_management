<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class FeatureMst extends Model
{
    protected $table = 'feature_mst';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'group_name',
        'description',
        'status',
        'is_delete',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'group_name' => 'string',
        'description' => 'string',
        'status' => 'integer',
        'is_delete' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
