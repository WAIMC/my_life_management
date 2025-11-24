<?php

namespace App\Models\Management;

use App\Traits\HasSoftDelete;
use App\Traits\HasStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class GoogleDriveConfig extends Model
{
    use HasSoftDelete, HasStatus;

    protected $table = 'google_drive_configs';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'credentials_json',
        'root_folder_id',
        'is_active',
        'status',
        'is_delete',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'credentials_json' => 'encrypted', // Automatically encrypt/decrypt
        'root_folder_id' => 'string',
        'is_active' => 'boolean',
        'status' => 'integer',
        'is_delete' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'credentials_json', // Hide sensitive data from API responses
    ];

    /**
     * Scope to get only active configuration.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('is_delete', false)
            ->where('status', 1);
    }

    /**
     * Get the credentials as array.
     *
     * @return array
     */
    public function getCredentialsArrayAttribute(): array
    {
        return json_decode($this->credentials_json, true) ?? [];
    }

    /**
     * Check if credentials JSON is valid.
     *
     * @return bool
     */
    public function hasValidCredentials(): bool
    {
        $credentials = $this->credentials_array;
        
        return isset($credentials['type']) 
            && isset($credentials['project_id'])
            && isset($credentials['private_key'])
            && isset($credentials['client_email']);
    }
}
