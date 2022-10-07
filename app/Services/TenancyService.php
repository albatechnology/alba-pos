<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Tenant;
use App\Models\User;
use Exception;
use Illuminate\Support\Collection;

class TenancyService
{
    /**
     * check user login
     * @return User|mixed
     */
    public function checkUserLogin()
    {
        $user = $this->getUser();
        if (!$user) abort(500, "No login session detected");
        return $user;
    }

    /**
     * get user active Tenant
     * @return Tenant|null
     */
    public function getActiveTenant(): ?Tenant
    {
        return session('active-tenant');
    }

    /**
     * set user active tenant
     * also set tenant_id on table users
     *
     * @param Tenant $tenant
     * @return Tenant|null
     */
    public function setActiveTenant(Tenant $tenant)
    {
        $tenants = $this->getTenants();

        $allowedTenantsIds = $tenants->isEmpty() ? collect([]) : $tenants->pluck('id');

        if (!$allowedTenantsIds->contains($tenant->id)) return new Exception('No tenants allowed');

        $user = $this->getUser();
        $user->update([
            'company_id' => $tenant->company->id,
            'tenant_id' => $tenant->id,
        ]);

        return session(['active-tenant' => $tenant]);
    }

    /**
     * get user active company
     * @return Company|null
     */
    public function getActiveCompany(): ?Company
    {
        return session('active-company');
    }

    /**
     * set user active company
     * also set company_id on table users
     *
     * @param Company $company
     * @return Company|null
     */
    public function setActiveCompany(Company $company)
    {
        $companies = $this->getMyAllCompanies();

        $allowedCompaniesIds = $companies->isEmpty() ? collect([]) : $companies->pluck('id');

        if (!$allowedCompaniesIds->contains($company->id)) return new Exception('No Companies allowed');

        $user = $this->getUser();
        $user->update(['company_id' => $company->id]);

        return session(['active-company' => $company]);
    }

    /**
     * get user companies
     * @param User|null $user
     * @return mixed|null
     */
    public function getMyAllCompanies(User $user = null): Collection
    {
        if (!$user) $user = $this->checkUserLogin();
        if ($user->is_super_admin) return Company::all();

        return $user->companies;
    }

    /**
     * get user tenants
     * @param User|null $user
     * @return mixed|null
     */
    public function getTenants(User $user = null): Collection
    {
        if (!$user) $user = $this->checkUserLogin();

        if ($activeCompany = $this->getActiveCompany()) return Tenant::where('company_id', $activeCompany->id)->get();

        if ($user->is_super_admin) return Tenant::all();
        // if ($user->is_admin) return Tenant::whereIn('company_id', $this->getMyAllCompanies($user)->pluck('id'))->get();
        return $user->tenants;
    }

    /**
     * Check whether the currently active tenant is the same
     * as the given tenant parameter
     * @param  Tenant  $tenant
     * @return bool
     */
    public function activeTenantIs(Tenant $tenant): bool
    {
        if (!$activeTenant = $this->getActiveTenant()) return false;
        return $activeTenant->id === $tenant->id;
    }

    /**
     * Check whether the currently active company is the same
     * as the given company parameter
     * @param  Company  $company
     * @return bool
     */
    public function activeCompanyIs(Company $company): bool
    {
        if (!$activeCompany = $this->getActiveCompany()) return false;
        return $activeCompany->id === $company->id;
    }

    public function getUser(): ?User
    {
        $user = auth()->user() ?? auth('sanctum')->user();
        return $user;
    }

    /**
     * set active tenant for this session
     *
     * @param \Illuminate\Http\Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    public function setActiveTenantFromRequest(\Illuminate\Http\Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'company_id' => 'nullable|exists:companies,id',
            'tenant_id' => 'nullable|exists:tenants,id'
        ]);

        if ($validator->fails()) {
            session()->forget('active-company');
            session()->forget('active-tenant');
        } else {
            $validated = $validator->validated();
            if (session('active-company') && !$validated['company_id'] && $validated['tenant_id']) {
                session()->forget('active-company');
                session()->forget('active-tenant');
                return;
            }

            if ($validated['tenant_id']) {
                $tenant = Tenant::findOrFail($validated['tenant_id']);
                $this->setActiveTenant($tenant);
            } else {
                session()->forget('active-tenant');
            }

            if ($validated['company_id']) {
                $company = Company::findOrFail($validated['company_id']);
                if (!$this->activeCompanyIs($company)) {
                    session()->forget('active-tenant');
                    $this->setActiveCompany($company);
                }
            } else {
                isset($tenant) ? $this->setActiveCompany($tenant->company) : session()->forget('active-company');
            }
        }
    }
}
