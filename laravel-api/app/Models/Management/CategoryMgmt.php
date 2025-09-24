<?php

namespace App\Models\Management;

use App\Models\History\Management\CategoryMgmtHist;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoryMgmt extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'category_mgmt';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'status',
        'is_display',
        'rank_order'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Get the parent category
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(CategoryMgmt::class, 'parent_id');
    }

    /**
     * Get the child categories
     */
    public function children(): HasMany
    {
        return $this->hasMany(CategoryMgmt::class, 'parent_id');
    }

    /**
     * Get all products associated with this category
     */
    public function products(): HasMany
    {
        return $this->hasMany(ProductMgmt::class, 'category_id');
    }

    /**
     * Get the skills associated with this category
     */
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(
            SkillMgmt::class,
            'category_skill_mgmt',
            'category_id',
            'skill_id'
        );
    }

    /**
     * Get the history records for this category
     */
    public function history(): HasMany
    {
        return $this->hasMany(CategoryMgmtHist::class, 'category_mgmt_id');
    }
}
