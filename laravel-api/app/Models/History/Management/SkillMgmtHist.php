<?php

namespace App\Models\History\Management;

use Illuminate\Database\Eloquent\Model;

class SkillMgmtHist extends Model
{
    protected $table = 'skill_mgmt_hist';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'skill_mgmt_id',
        'parent_id',
        'name',
        'slug',
        'status',
        'is_display',
        'rank_order',
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
        'skill_mgmt_id' => 'integer',
        'parent_id' => 'integer',
        'name' => 'string',
        'slug' => 'string',
        'status' => 'integer',
        'is_display' => 'boolean',
        'rank_order' => 'integer',
        'action' => 'integer',
        'author_id' => 'integer',
        'created_at' => 'datetime',
    ];
}
