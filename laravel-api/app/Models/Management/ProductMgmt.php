<?php

namespace App\Models\Management;

use App\Models\History\Management\ProductMgmtHist;
use App\Traits\HasSoftDelete;
use App\Traits\HasStatus;
use App\Traits\HasHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductMgmt extends Model
{
    use HasSoftDelete, HasStatus, HasHistory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_mgmt';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_mgmt_id',
        'code',
        'name',
        'slug',
        'description',
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
        'category_mgmt_id' => 'integer',
        'code' => 'string',
        'name' => 'string',
        'slug' => 'string',
        'description' => 'string',
        'status' => 'integer',
        'is_display' => 'boolean',
        'rank_order' => 'integer',
        'is_delete' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the category that owns the product.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(CategoryMgmt::class, 'category_mgmt_id');
    }

    /**
     * Get the history records for the product.
     *
     * @return HasMany
     */
    public function history(): HasMany
    {
        return $this->hasMany(ProductMgmtHist::class, 'product_mgmt_id');
    }
}
