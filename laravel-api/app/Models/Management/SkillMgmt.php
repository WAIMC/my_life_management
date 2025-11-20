<?php

namespace App\Models\Management;

use App\Models\History\Management\SkillMgmtHist;
use App\Traits\HasSoftDelete;
use App\Traits\HasStatus;
use App\Traits\HasHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SkillMgmt extends Model
{
    use HasSoftDelete, HasStatus, HasHistory;

    protected $table = 'skill_mgmt';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'status',
        'is_display',
        'rank_order',
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
        'name' => 'string',
        'slug' => 'string',
        'status' => 'integer',
        'is_display' => 'boolean',
        'rank_order' => 'integer',
        'is_delete' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the descriptions for the skill.
     *
     * @return HasMany
     */
    public function descriptions(): HasMany
    {
        return $this->hasMany(SkillDescriptionMgmt::class, 'skill_mgmt_id');
    }

    /**
     * Get the categories associated with the skill.
     *
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            CategoryMgmt::class,
            'category_skill_mgmt',
            'skill_mgmt_id',
            'category_mgmt_id'
        )->withTimestamps();
    }

    /**
     * Get the history records for the skill.
     *
     * @return HasMany
     */
    public function history(): HasMany
    {
        return $this->hasMany(SkillMgmtHist::class, 'skill_mgmt_id');
    }
}
