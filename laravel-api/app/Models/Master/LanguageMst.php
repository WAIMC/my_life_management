<?php

namespace App\Models\Master;

use App\Models\History\Master\LanguageMstHist;
use App\Traits\HasSoftDelete;
use App\Traits\HasStatus;
use App\Traits\HasHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LanguageMst extends Model
{
    use HasSoftDelete, HasStatus, HasHistory;

    protected $table = 'language_mst';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'abbreviation',
        'name',
        'is_active',
        'is_delete',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'abbreviation' => 'string',
        'name' => 'string',
        'is_active' => 'boolean',
        'is_delete' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the translations associated with the language.
     *
     * @return BelongsToMany
     */
    public function translations(): BelongsToMany
    {
        return $this->belongsToMany(
            TranslationMst::class,
            'translation_language_mst',
            'language_mst_id',
            'translation_mst_id'
        )->withTimestamps();
    }

    /**
     * Get the history records for the language.
     *
     * @return HasMany
     */
    public function history(): HasMany
    {
        return $this->hasMany(LanguageMstHist::class, 'language_mst_id');
    }
}
