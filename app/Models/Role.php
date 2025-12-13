<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    // This extends Spatie's Role model to maintain compatibility
    // while allowing for any custom methods if needed
    
    protected $fillable = [
        'name',
        'guard_name',
    ];

    // Add any custom methods here if needed
}
