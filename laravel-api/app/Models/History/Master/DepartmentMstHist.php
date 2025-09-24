<?php

namespace App\Models\History\Master;

use App\Models\Master\DepartmentMst;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DepartmentMstHist extends Model
{
    use HasFactory;

    protected $table = 'department_mst_hist';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'department_mst_id',
        'code',
        'name',
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
     * Get the department that this history record belongs to
     *
     * @return BelongsTo
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(DepartmentMst::class, 'department_mst_id');
    }
}
