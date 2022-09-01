<?php

namespace App\Models;

use App\Interfaces\TenantedInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model implements TenantedInterface
{
    use SoftDeletes;
    public $table = 'tenants';
    protected $guarded = [];

    public function scopeTenanted($query)
    {
        $hasActiveTenant = tenancy()->getActiveTenant();
        if ($hasActiveTenant) return $query->where('id', $hasActiveTenant->id);

        $hasActiveCompany = tenancy()->getActiveCompany();
        if ($hasActiveTenant) return $query->where('company_id', $hasActiveCompany->id);

        $user = user();
        if ($user->is_super_admin) return $query;

        return $query->whereIn('id', tenancy()->getTenants()?->pluck('id') ?? []);
    }

    public function scopeFindTenanted($query, int $id)
    {
        return $query->tenanted()->where('id', $id)->firstOrFail();
    }

    public function scopeGetAllMyTenant($query){
        $user = user();
        if ($user->is_super_admin) return $query;
        return $query->whereIn('company_id', $user->companies->pluck('id'));
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // public function products()
    // {
    //     return $this->belongsTo(Company::class);
    // }
}
