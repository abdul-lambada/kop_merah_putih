<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'permissions',
        'level',
        'is_active',
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_active' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user')
            ->withPivot(['assigned_at', 'assigned_by'])
            ->withTimestamps();
    }

    public function hasPermission($permission)
    {
        return in_array($permission, $this->permissions ?? []);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByLevel($query, $minLevel = 0)
    {
        return $query->where('level', '>=', $minLevel);
    }

    public function getPermissionListAttribute()
    {
        return implode(', ', array_keys($this->permissions ?? []));
    }
}
