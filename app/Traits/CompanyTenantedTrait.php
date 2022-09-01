<?php

namespace App\Traits;

use App\Models\Company;
use Illuminate\Support\Collection;

/**
 *
 */
trait CompanyTenantedTrait
{
    public function scopeTenanted($query)
    {
        $hasActiveCompany = tenancy()->getActiveCompany();
        if ($hasActiveCompany) return $query->tenantedActiveCompany($hasActiveCompany);

        $user = user();
        return $user->is_super_admin ? $query : $query->tenantedUserCompanies();
    }

    public function scopeFindTenanted($query, int $id)
    {
        return $query->tenanted()->where('id', $id)->firstOrFail();
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
        // 2. Company relationship from channel.company
        // 3. Company relationship from channels.company (exceptional cases such as User model)
        if (method_exists(static::class, 'company')) {
            return $query->where('company_id', $activeCompany->id);
        } elseif (method_exists(static::class, 'channel')) {
            return $query->whereHas('channel', fn ($q) => $q->where('company_id', $activeCompany->id));
        } else {
            return $query->whereHas('channels', fn ($q) => $q->where('company_id', $activeCompany->id)->whereIn('id', tenancy()->getTenants()->pluck('id')));
        }
    }

    public function scopeTenantedUserCompanies($query, Collection $companies = null)
    {
        if (!$companies) $companies = tenancy()->getMyAllCompanies();

        if (method_exists(static::class, 'company')) {
            return $query->whereIn('company_id', $companies->pluck('id'));
        } elseif (method_exists(static::class, 'companies')) {
            return $query->whereHas('companies', fn ($q) => $q->whereIn('id', $companies->pluck('id')));
        } else {
            throw new \Exception('Unknown relationship to company(s) from model ' . static::class);
        }
    }
}
