<?php

namespace App\Models\Management;

use App\Models\History\Management\SocialMgmtHist;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SocialMgmt extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'social_mgmt';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'link',
        'image',
        'status',
        'is_display',
        'rank_order',
        'is_delete',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_display' => 'boolean',
        'status' => 'integer',
        'rank_order' => 'integer',
    ];

    /**
     * Get the social history records associated with the social.
     */
    public function histories(): HasMany
    {
        return $this->hasMany(SocialMgmtHist::class, 'social_mgmt_id');
    }
}
