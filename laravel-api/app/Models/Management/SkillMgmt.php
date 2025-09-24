<?php

namespace App\Models\Management;

use App\Enums\SkillStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SkillMgmt extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'skill_mgmt';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'status',
        'is_display',
        'rank_order'
    ];

    /**
     * Get the parent skill
     *
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(SkillMgmt::class, 'parent_id');
    }

    /**
     * Get the child skills
     *
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(SkillMgmt::class, 'parent_id');
    }
}
