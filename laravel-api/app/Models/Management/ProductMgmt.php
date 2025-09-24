<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductMgmt extends Model
{
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
        'category_id',
        'code',
        'name',
        'slug',
        'description',
        'status',
        'is_display',
        'rank_order',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(CategoryMgmt::class, 'category_id');
    }
}
