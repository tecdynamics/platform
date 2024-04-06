<?php

namespace Tec\ACL\Models;

use Tec\ACL\Concerns\HasPreferences;
use Tec\ACL\Contracts\HasPermissions as HasPermissionsContract;
use Tec\ACL\Contracts\HasPreferences as HasPreferencesContract;
use Tec\ACL\Notifications\ResetPasswordNotification;
use Tec\ACL\Traits\PermissionTrait;
use Tec\Base\Casts\SafeContent;
use Tec\Base\Models\BaseModel;
use Tec\Base\Supports\Avatar;
use Tec\Media\Facades\RvMedia;
use Tec\Media\Models\MediaFile;
use Exception;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends BaseModel implements
    HasPermissionsContract,
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract,
    HasPreferencesContract
{
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;
    use HasApiTokens;
    use HasFactory;
    use PermissionTrait {
        PermissionTrait::hasPermission as traitHasPermission;
        PermissionTrait::hasAnyPermission as traitHasAnyPermission;
    }
    use Notifiable;
    use HasPreferences;

    protected $table = 'users';

    protected $fillable = [
        'username',
        'email',
        'first_name',
        'last_name',
        'password',
        'avatar_id',
        'permissions',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'permissions' => 'json',
        'username' => SafeContent::class,
        'first_name' => SafeContent::class,
        'last_name' => SafeContent::class,
    ];

    protected function firstName(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucfirst((string)$value),
            set: fn ($value) => ucfirst((string)$value),
        );
    }

    protected function lastName(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucfirst((string)$value),
            set: fn ($value) => ucfirst((string)$value),
        );
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->first_name . ' ' . $this->last_name,
        );
    }

    protected function activated(): Attribute
    {
        return Attribute::make(
            get: fn (): bool => $this->activations()->where('completed', true)->exists(),
        );
    }
    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getKey() ? route('users.profile.view', $this->getKey()) : null,
        );
    }
    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->avatar->url) {
                    return RvMedia::url($this->avatar->url);
                }

                try {
                    return (new Avatar())->create($this->name)->toBase64();
                } catch (Exception) {
                    return RvMedia::getDefaultImage();
                }
            },
        );
    }
    /**
     * @return string
     * @deprecated
     */
    public function getFullName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getNameAttribute()
    {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }
    public function avatar(): BelongsTo
    {
        return $this->belongsTo(MediaFile::class)->withDefault();
    }

    public function roles(): BelongsToMany
    {
        return $this
            ->belongsToMany(Role::class, 'role_users', 'user_id', 'role_id')
            ->withTimestamps();
    }

    public function isSuperUser(): bool
    {
        return $this->super_user || $this->hasAccess(ACL_ROLE_SUPER_USER);
    }

    public function hasPermission(string|array  $permission): bool
    {
        if ($this->isSuperUser()) {
            return true;
        }

        return $this->hasAccess($permission);
    }

    public function hasAnyPermission(string|array  $permissions): bool
    {
        if ($this->isSuperUser()) {
            return true;
        }

        return $this->hasAnyAccess($permissions);
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function activations(): HasMany
    {
        return $this->hasMany(Activation::class, 'user_id');
    }

    public function inRole($role): bool
    {
        $roleId = null;
        if ($role instanceof Role) {
            $roleId = $role->getKey();
        }

        foreach ($this->roles as $instance) {
            if ($role instanceof Role) {
                if ($instance->getKey() === $roleId) {
                    return true;
                }
            } elseif ($instance->getKey() == $role || $instance->slug == $role) {
                return true;
            }
        }

        return false;
    }

    public function delete(): bool|null
    {
        if ($this->exists) {
            $this->activations()->delete();
            $this->roles()->detach();
        }

        return parent::delete();
    }
}
