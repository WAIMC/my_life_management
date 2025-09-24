<?php

namespace App\Models\History\Management;

use Illuminate\Database\Eloquent\Model;
use App\Models\Management\SocialMgmt;

class SocialMgmtHist extends Model
{
    protected $table = 'social_mgmt_hist';
    
    public $timestamps = false;
    
    protected $fillable = [
        'id',
        'social_mgmt_id',
        'name',
        'slug',
        'link',
        'image',
        'status',
        'is_display',
        'rank_order',
        'action',
        'author_id',
        'created_at'
    ];
    
    /**
     * Get the social that owns the history
     */
    public function social()
    {
        return $this->belongsTo(SocialMgmt::class, 'social_mgmt_id');
    }
}