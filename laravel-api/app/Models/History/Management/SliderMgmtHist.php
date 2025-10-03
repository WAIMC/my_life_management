<?php

namespace App\Models\History\Management;

use Illuminate\Database\Eloquent\Model;

class SliderMgmtHist extends Model
{
    protected $table = 'slider_mgmt_hist';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'slider_mgmt_id',
        'title',
        'slug',
        'link',
        'image',
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
        'slider_mgmt_id' => 'integer',
        'title' => 'string',
        'slug' => 'string',
        'link' => 'string',
        'image' => 'string',
        'status' => 'integer',
        'action' => 'integer',
        'author_id' => 'integer',
        'created_at' => 'datetime',
    ];
}
