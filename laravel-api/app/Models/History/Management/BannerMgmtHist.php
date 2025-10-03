<?php

namespace App\Models\History\Management;

use Illuminate\Database\Eloquent\Model;

class BannerMgmtHist extends Model
{
    protected $table = 'banner_mgmt_hist';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
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
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'banner_mgmt_id' => 'integer',
        'title' => 'string',
        'slug' => 'string',
        'description' => 'string',
        'link' => 'string',
        'image' => 'string',
        'position' => 'string',
        'status' => 'integer',
        'action' => 'integer',
        'author_id' => 'integer',
        'created_at' => 'datetime',
    ];
}
