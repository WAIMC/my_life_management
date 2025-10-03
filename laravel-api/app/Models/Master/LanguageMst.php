<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class LanguageMst extends Model
{
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
        'created_at' => 'string',
        'updated_at' => 'string',
    ];
}
