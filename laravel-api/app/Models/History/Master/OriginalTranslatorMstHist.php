<?php

namespace App\Models\History\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Master\OriginalTranslatorMst;

class OriginalTranslatorMstHist extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'original_translator_mst_hist';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'original_translator_mst_id',
        'table',
        'column',
        'field_id',
        'action',
        'author_id',
        'created_at',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the original translator record associated with the history.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function originalTranslator()
    {
        return $this->belongsTo(OriginalTranslatorMst::class, 'original_translator_mst_id');
    }
}
