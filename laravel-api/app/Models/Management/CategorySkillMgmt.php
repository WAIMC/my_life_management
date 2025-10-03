<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Model;

class CategorySkillMgmt extends Model
{
    protected $table = 'category_skill_mgmt';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'category_mgmt_id',
        'skill_mgmt_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'category_mgmt_id' => 'integer',
        'skill_mgmt_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
