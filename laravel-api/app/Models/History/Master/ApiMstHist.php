<?php

namespace App\Models\History\Master;

use App\Models\Master\AdminMst;
use App\Models\Master\ApiMst;
use App\Models\Master\FeatureMst;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiMstHist extends Model
{
    protected $table = 'api_mst_hist';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'api_mst_id',
        'type',
        'name',
        'path',
        'is_active',
        'feature_id',
        'action',
        'author_id',
        'created_at'
    ];

    /**
     * Get the API master that this history record belongs to
     */
    public function apiMst(): BelongsTo
    {
        return $this->belongsTo(ApiMst::class, 'api_mst_id');
    }

    /**
     * Get the feature that this API is associated with
     */
    public function feature(): BelongsTo
    {
        return $this->belongsTo(FeatureMst::class, 'feature_id');
    }

    /**
     * Get the author who made this change
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(AdminMst::class, 'author_id');
    }
}
