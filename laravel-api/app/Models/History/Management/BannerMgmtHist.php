<?php

namespace App\Models\History\Management;

use App\Models\Management\BannerMgmt;
use App\Models\Master\AdminMst;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BannerMgmtHist extends Model
{
    protected $table = 'banner_mgmt_hist';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'banner_mgmt_id',
        'title',
        'slug',
        'description',
        'link',
        'image',
        'position',
        'status',
        'action',
        'author_id',
        'created_at'
    ];

    /**
     * Get the banner that this history record belongs to
     */
    public function banner(): BelongsTo
    {
        return $this->belongsTo(BannerMgmt::class, 'banner_mgmt_id');
    }

    /**
     * Get the author who made this change
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(AdminMst::class, 'author_id');
    }
}
