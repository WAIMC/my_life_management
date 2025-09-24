<?php

namespace App\Models\History\Management;

use App\Enums\ActionType;
use App\Enums\SkillStatus;
use App\Models\Management\SkillMgmt;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkillMgmtHist extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'skill_mgmt_hist';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
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
        'created_at'
    ];

    /**
     * Get the skill this history record belongs to
     *
     * @return BelongsTo
     */
    public function skill(): BelongsTo
    {
        return $this->belongsTo(SkillMgmt::class, 'skill_mgmt_id');
    }
}
