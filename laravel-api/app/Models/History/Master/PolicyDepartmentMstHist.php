<?php

namespace App\Models\History\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Master\PolicyDepartmentMst;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PolicyDepartmentMstHist extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'policy_department_mst_hist';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'policy_department_mst_id',
        'table_name',
        'row_id',
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
     * Get the policy department record associated with the history.
     *
     * @return BelongsTo
     */
    public function policyDepartment(): BelongsTo
    {
        return $this->belongsTo(PolicyDepartmentMst::class, 'policy_department_mst_id');
    }
}
