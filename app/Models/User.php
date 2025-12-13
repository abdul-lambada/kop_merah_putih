<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'full_name',
        'phone',
        'birth_date',
        'address',
        'email_notifications',
        'theme',
        'language',
        'timezone',
        'notification_email',
        'notification_push',
        'notification_sms',
        'avatar',
        'member_number',
        'last_login_at',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birth_date' => 'date',
        'last_login_at' => 'datetime',
        'email_notifications' => 'boolean',
        'notification_email' => 'boolean',
        'notification_push' => 'boolean',
        'notification_sms' => 'boolean',
    ];

    /**
     * Get the roles associated with the user.
     */
    // public function roles()
    // {
    //     return $this->belongsToMany(Role::class, 'role_user')
    //         ->withPivot(['assigned_at', 'assigned_by'])
    //         ->withTimestamps();
    // }

    // /**
    //  * Check if user has a specific role.
    //  */
    // public function hasRole($roleSlug)
    // {
    //     return $this->roles()->where('slug', $roleSlug)->exists();
    // }

    // /**
    //  * Check if user has any of the given roles.
    //  */
    // public function hasAnyRole($roleSlugs)
    // {
    //     return $this->roles()->whereIn('slug', (array) $roleSlugs)->exists();
    // }

    // /**
    //  * Get the highest level role of the user.
    //  */
    // public function getHighestRoleLevel()
    // {
    //     return $this->roles()->min('level') ?? 999;
    // }
}
