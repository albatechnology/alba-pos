<?php

namespace App\Models;

use App\Interfaces\TenantedInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Company extends Model implements TenantedInterface
{
    use SoftDeletes;
    protected $guarded = [];

    /**
     * override tenanted scope
     * @return mixed
     */
    public function scopeTenanted($query)
    {
        $activeCompany = tenancy()->getActiveCompany();
        if ($activeCompany) return $query->where('id', $activeCompany->id);

        $activeTenant = tenancy()->getActiveTenant();
        if ($activeTenant) return $query->where('id', $activeTenant->company->id);

        $user = user();
        if ($user->is_super_admin) return $query;
        // if ($user->is_admin) return $query->whereIn('id', $user->companies->pluck('id'));
        return $query->whereIn('id', $user->companies->pluck('id'));
    }

    public function scopeFindTenanted($query, int $id)
    {
        return $query->tenanted()->where('id', $id)->firstOrFail();
    }

    public function scopeTenantedMyAllCompanies($query)
    {
        return $query->whereIn('id', tenancy()->getMyAllCompanies()->pluck('id'));
    }

    /**
     * get tenants from given company id(s)
     *
     * @param int|array $company_id
     * @return Collection
     */
    public static function getTenantsCompany(int|array $company_id)
    {
        $company_ids = is_array($company_id) ? $company_id : [$company_id];
        return Tenant::whereIn('company_id', $company_ids)->get();
    }
}
