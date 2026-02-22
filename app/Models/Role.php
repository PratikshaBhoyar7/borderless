<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    const ADMIN = 'admin';
    const USER = 'user';
    const DATA_ENTRY = 'data_entry';
    const ANALYTICS_VIEWER = 'analytics_viewer';

    /**
     * Get the users that belong to this role.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user');
    }

    /**
     * Get the permissions that belong to this role.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    /**
     * Check if role has a specific permission.
     */
    public function hasPermission($permission)
    {
        return $this->permissions()
                    ->where('name', $permission)
                    ->orWhere('display_name', $permission)
                    ->exists();
    }

    /**
     * Get all permission names for this role.
     */
    public function getPermissionNames()
    {
        return $this->permissions()->pluck('name')->toArray();
    }
}
