<?php

namespace App\Models;

use App\Enums\UserLevelEnum;
use App\Interfaces\TenantedInterface;
use App\Traits\TenantedTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements TenantedInterface
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes, TenantedTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

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
        'level' => UserLevelEnum::class,
        'created_at' => 'datetime:d-m-Y H:i',
        'updated_at' => 'datetime:d-m-Y H:i',
        'email_verified_at' => 'datetime',
    ];

    protected function photo(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? $value : asset('images/user.png'),
        );
    }

    public function getIsSuperAdminAttribute(): bool
    {
        if ($this->level->is(UserLevelEnum::SUPER_ADMIN)) return true;
        return false;
        // if ($this->hasRole('super-admin')) return true;
        // return $this->roles()->where('id', 1)->exists();
    }

    public function getIsAdminAttribute(): bool
    {
        if ($this->level->is(UserLevelEnum::ADMIN)) return true;
        return false;
        // if ($this->hasRole('admin')) return true;
        // return $this->roles()->where('id', 2)->exists();
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'user_companies');
    }

    public function tenants()
    {
        return $this->belongsToMany(Tenant::class, 'user_tenants');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}
