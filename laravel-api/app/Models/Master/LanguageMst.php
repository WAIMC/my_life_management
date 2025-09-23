<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class LanguageMst extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'language_mst';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'abbreviation',
        'name',
        'is_active',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];
}
