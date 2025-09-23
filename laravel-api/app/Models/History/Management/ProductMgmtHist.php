<?php

namespace App\Models\History\Management;

use App\Models\Management\CategoryMgmt;
use App\Models\Management\ProductMgmt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductMgmtHist extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_mgmt_hist';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_mgmt_id',
        'category_id',
        'code',
        'name',
        'slug',
        'description',
        'status',
        'is_display',
        'rank_order',
        'action',
        'author_id',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the product that this history record belongs to.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(ProductMgmt::class, 'product_mgmt_id');
    }

    /**
     * Get the category associated with this history record.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(CategoryMgmt::class, 'category_id');
    }
}
