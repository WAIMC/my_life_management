<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Model;

class SkillDescriptionMgmt extends Model
{
    protected $table = 'skill_description_mgmt';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'parent_id',
        'title',
        'summary',
        'article',
        'status',
        'is_display',
        'rank_order',
        'skill_mgmt_id',
        'is_delete',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'parent_id' => 'integer',
        'title' => 'string',
        'summary' => 'string',
        'article' => 'string',
        'status' => 'integer',
        'is_display' => 'boolean',
        'rank_order' => 'integer',
        'skill_mgmt_id' => 'integer',
        'is_delete' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
