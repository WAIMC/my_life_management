<?php

namespace App\Models\Management;

use App\Models\History\Management\SliderMgmtHist;
use App\Traits\HasSoftDelete;
use App\Traits\HasStatus;
use App\Traits\HasHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SliderMgmt extends Model
{
    use HasSoftDelete, HasStatus, HasHistory;

    protected $table = 'slider_mgmt';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'title',
        'slug',
        'link',
        'image',
        'status',
        'is_delete',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'slug' => 'string',
        'link' => 'string',
        'image' => 'string',
        'status' => 'integer',
        'is_delete' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the history records for the slider.
     *
     * @return HasMany
     */
    public function history(): HasMany
    {
        return $this->hasMany(SliderMgmtHist::class, 'slider_mgmt_id');
    }
}
