<?php

namespace App\Models\Management;

use App\Models\History\Management\SkillDescriptionMgmtHist;
use App\Traits\HasSoftDelete;
use App\Traits\HasStatus;
use App\Traits\HasHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SkillDescriptionMgmt extends Model
{
    use HasSoftDelete, HasStatus, HasHistory;

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

    /**
     * Get the skill that owns the description.
     *
     * @return BelongsTo
     */
    public function skill(): BelongsTo
    {
        return $this->belongsTo(SkillMgmt::class, 'skill_mgmt_id');
    }

    /**
     * Get the history records for the skill description.
     *
     * @return HasMany
     */
    public function history(): HasMany
    {
        return $this->hasMany(SkillDescriptionMgmtHist::class, 'skill_description_mgmt_id');
    }
}
