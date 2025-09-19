<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BannerMgmt extends Model
{
    protected $table = 'banner_mgmt';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'title',
        'slug',
        'description',
        'link',
        'image',
        'position',
        'status',
        'created_at',
        'updated_at'
    ];

    /**
     * Get the history records for this banner
     */
    public function history(): HasMany
    {
        return $this->hasMany(BannerMgmtHist::class, 'banner_mgmt_id');
    }
}
