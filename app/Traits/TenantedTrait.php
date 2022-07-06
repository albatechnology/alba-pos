<?php

namespace App\Traits;

use App\Models\Company;
use App\Models\Tenant;

/**
 *
 */
trait TenantedTrait
{
    public function scopeTenanted($query)
    {
        $hasActiveTenant = tenancy()->getActiveTenant();
        $user = user();

        if($hasActiveTenant) return $this->tenantedActiveTenant($hasActiveTenant);

        if($user->is_super_admin) return $query;
    }

    public function scopeFindTenanted($query, int $id)
    {
    }

    /**
     * active tenant selected
     * show only resource that belongs to the tenant selected
     * @param $query
     * @param Tenant $tenant
     * @return mixed
     */
    public function scopeTenantedActiveTenant($query, Tenant $tenant){
        if(!$tenant) $tenant = tenancy()->getActiveTenant();
        return $query->where('tenant_id', $tenant->id);
    }

    /**
     * active company selected
     * show only resource that belongs to the company selected
     * @param $query
     * @param Company $company
     * @return mixed
     */
    public function scopeTenantedActiveCompany($query, Company $company){
        if(!$company) $tenant = tenancy()->setActiveCompany();
        return $query->whereHas('company', function($q){

        });
        return $query->where('tenant_id', $tenant->id);
    }
}
