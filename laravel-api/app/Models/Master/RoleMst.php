<?php

namespace App\Models\Master;

use App\Models\History\Master\RoleMstHist;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoleMst extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'role_mst';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'permission',
        'is_active'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Get the admin role assignments for this role
     *
     * @return HasMany
     */
    public function adminRoles(): HasMany
    {
        return $this->hasMany(AdminRoleMst::class, 'role_id');
    }

    /**
     * Get the API role assignments for this role
     *
     * @return HasMany
     */
    public function apiRoles(): HasMany
    {
        return $this->hasMany(ApiRoleMst::class, 'role_id');
    }

    /**
     * Get the history records for this role
     *
     * @return HasMany
     */
    public function history(): HasMany
    {
        return $this->hasMany(RoleMstHist::class, 'role_mst_id');
    }
}
