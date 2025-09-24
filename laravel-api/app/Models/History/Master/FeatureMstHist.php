<?php

namespace App\Models\History\Master;

use App\Models\Master\FeatureMst;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeatureMstHist extends Model
{
    use HasFactory;

    protected $table = 'feature_mst_hist';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'feature_mst_id',
        'name',
        'group_name',
        'description',
        'status',
        'action',
        'author_id',
        'created_at'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the feature that this history record belongs to
     *
     * @return BelongsTo
     */
    public function feature(): BelongsTo
    {
        return $this->belongsTo(FeatureMst::class, 'feature_mst_id');
    }
}
