<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * The attributes should append
     *
     * @var array<int, string>
     */
    protected $appends = [
        'must_verify_email',
    ];

    /**
     * MustVerifyEmail attribute
     *
     * @return boolean
     */
    public function getMustVerifyEmailAttribute()
    {
        return auth()->user() instanceof MustVerifyEmail;
    }

    /**
     * Return all the permissions the model has via roles.
     */
    public function getPermissionsViaRoles(): Collection
    {
        $roleKeyName = app(Role::class)->getKeyName();

        // Find the roles that are not eagerly loaded
        $rolesNotEagerLoaded = $this->roles
            ->filter(fn($role) => !$role->relationLoaded('permissions'))
            ->pluck($roleKeyName);

        $eagerLoadedPermissions = collect();

        if ($rolesNotEagerLoaded->isNotEmpty()) {
            // Fetch all the permissions from the roles not eager loaded
            // This is kinda like eager-loading the permissions
            $eagerLoadedPermissions = $eagerLoadedPermissions->merge(
                config('permission.models.permission')::with('roles')
                    ->whereHas('roles', fn($query) => $query->whereIn($roleKeyName, $rolesNotEagerLoaded))
                    ->get()
            );
        }

        return $this->roles->flatMap(function ($role) use ($eagerLoadedPermissions, $roleKeyName) {
            if ($role->relationLoaded('permissions')) {
                return $role->permissions;
            }

            return $eagerLoadedPermissions->filter(function ($permission) use ($role, $roleKeyName) {
                return $permission->roles->pluck($roleKeyName)->contains($role->$roleKeyName);
            });
        });
    }
}
