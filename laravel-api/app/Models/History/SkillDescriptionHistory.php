<?php

namespace App\Models\History;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillDescriptionHistory extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'h_skill_description';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'skill_description_id',
        'parent_id',
        'title',
        'summary',
        'article',
        'status',
        'rank_order',
        'is_display',
        'skill_id',
        'action',
        'author_id',
        'created_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
       'created_at' => 'datetime',
    ];
}
