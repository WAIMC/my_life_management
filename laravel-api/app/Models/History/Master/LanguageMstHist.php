<?php

namespace App\Models\History\Master;

use App\Models\Master\LanguageMst;
use Illuminate\Database\Eloquent\Model;

class LanguageMstHist extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'language_mst_hist';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'language_mst_id',
        'abbreviation',
        'name',
        'is_active',
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
     * Get the language that owns the history.
     */
    public function language()
    {
        return $this->belongsTo(LanguageMst::class, 'language_mst_id');
    }

    /**
     * Get the admin that created the history.
     */
    public function author()
    {
        return $this->belongsTo('App\Models\Master\AdminMst', 'author_id');
    }
}
