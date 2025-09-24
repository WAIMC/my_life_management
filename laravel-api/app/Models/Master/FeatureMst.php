<?php

namespace App\Models\Master;

use App\Models\History\Master\FeatureMstHist;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeatureMst extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'feature_mst';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'group_name',
        'description',
        'status'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Get the APIs associated with this feature
     *
     * @return HasMany
     */
    public function apiMst(): HasMany
    {
        return $this->hasMany(ApiMst::class, 'feature_id');
    }

    /**
     * Get the history records for this feature
     *
     * @return HasMany
     */
    public function history(): HasMany
    {
        return $this->hasMany(FeatureMstHist::class, 'feature_mst_id');
    }
}
