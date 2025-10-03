<?php

namespace App\Models\History\Management;

use Illuminate\Database\Eloquent\Model;

class SettingLinkMgmtHist extends Model
{
    protected $table = 'setting_link_mgmt_hist';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'setting_link_id',
        'key',
        'value',
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
        'setting_link_id' => 'integer',
        'key' => 'string',
        'value' => 'string',
        'action' => 'integer',
        'author_id' => 'integer',
        'created_at' => 'datetime',
    ];
}
