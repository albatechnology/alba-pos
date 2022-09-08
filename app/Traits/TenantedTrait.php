<?php

namespace App\Traits;

use App\Models\Company;
use App\Models\Tenant;
use Illuminate\Support\Collection;

/**
 *
 */
trait TenantedTrait
{
    public function scopeTenanted($query)
    {
        $hasActiveTenant = tenancy()->getActiveTenant();
        if ($hasActiveTenant) return $query->tenantedActiveTenant($hasActiveTenant);

        $hasActiveCompany = tenancy()->getActiveCompany();
        if ($hasActiveCompany) return $query->tenantedActiveCompany($hasActiveCompany);

        $user = user();
        return $user->is_super_admin ? $query : $query->tenantedUserTenants();
    }

    public function scopeFindTenanted($query, int $id)
    {
        return $query->tenanted()->where('id', $id)->firstOrFail();
    }

    /**
     * active tenant selected
     * show only resource that belongs to the tenant selected
     * @param $query
     * @param Tenant $tenant
     * @return mixed
     */
    public function scopeTenantedActiveTenant($query, Tenant $tenant)
    {
        if (!$tenant) $tenant = tenancy()->getActiveTenant();
        if (method_exists(static::class, 'tenant')) {
            return $query->where('tenant_id', $tenant->id);
        } elseif (method_exists(static::class, 'company')) {
            return $query->where('company_id', $tenant->company_id);
        }
    }

    /**
     * active company selected
     * show only resource that belongs to the company selected
     * @param $query
     * @param Company $company
     * @return mixed
     */
    public function scopeTenantedActiveCompany($query, Company $activeCompany)
    {
        if (!$activeCompany) $activeCompany = tenancy()->getActiveCompany();

        // There is several possibility on how to relate company from the model
        // 1. company_id is available on the model
        // 2. Company relationship from tenant.company
        // 3. Company relationship from tenants.company (exceptional cases such as User model)
        if (method_exists(static::class, 'company')) {
            return $query->where('company_id', $activeCompany->id);
        } elseif (method_exists(static::class, 'tenant')) {
            return $query->whereHas('tenant', fn ($q) => $q->where('company_id', $activeCompany->id));
        } else {
            return $query->whereHas('tenants', fn ($q) => $q->where('company_id', $activeCompany->id)->whereIn('id', tenancy()->getTenants()->pluck('id')));
        }
    }

    public function scopeTenantedUserTenants($query, Collection $tenants = null)
    {
        if (!$tenants) $tenants = tenancy()->getTenants();

        if (method_exists(static::class, 'companies')) {
            $companies = tenancy()->getMyAllCompanies();
            return $query->whereHas('companies', fn ($q) => $q->whereIn('id', $companies->pluck('id')));
        } elseif (method_exists(static::class, 'company')) {
            $companies = tenancy()->getMyAllCompanies();
            return $query->whereIn('company_id', $companies->pluck('id'));
        } elseif (method_exists(static::class, 'tenant')) {
            return $query->whereIn('id', $tenants->pluck('id'));
        } elseif (method_exists(static::class, 'tenants')) {
            return $query->whereHas('tenants', fn ($q) => $q->whereIn('id', $tenants->pluck('id')));
        } else {
            throw new \Exception('Unknown relationship to tenant(s) from model ' . static::class);
        }
    }
}
