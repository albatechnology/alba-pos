<?php

namespace App\Models;

use App\Enums\UserLevelEnum;
use App\Interfaces\TenantedInterface;
use App\Traits\TenantedTrait;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class User extends Authenticatable implements TenantedInterface, HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes, TenantedTrait, InteractsWithMedia;

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

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('users')
            ->useFallbackUrl('/https://www.tibs.org.tw/images/default.jpg')
            ->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(368)
            ->height(232)
            ->sharpen(10);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
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
