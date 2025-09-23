<?php

namespace App\Models\History\Management;

use App\Models\Management\CategoryMgmt;
use App\Models\Master\AdminMst;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategoryMgmtHist extends Model
{
    protected $table = 'category_mgmt_hist';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'category_mgmt_id',
        'parent_id',
        'name',
        'slug',
        'description',
        'status',
        'is_display',
        'rank_order',
        'action',
        'author_id',
        'created_at'
    ];

    /**
     * Get the category that this history record belongs to
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(CategoryMgmt::class, 'category_mgmt_id');
    }

    /**
     * Get the author who made this change
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(AdminMst::class, 'author_id');
    }
}
