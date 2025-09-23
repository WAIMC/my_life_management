<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategorySkillMgmt extends Model
{
    protected $table = 'category_skill_mgmt';
    public $timestamps = false;
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'category_id',
        'skill_id',
        'created_at',
        'updated_at'
    ];

    /**
     * Get the category that owns this relationship
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(CategoryMgmt::class, 'category_id');
    }

    /**
     * Get the skill that owns this relationship
     */
    public function skill(): BelongsTo
    {
        return $this->belongsTo(SkillMgmt::class, 'skill_id');
    }
}
