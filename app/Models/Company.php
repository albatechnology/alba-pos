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
        $user = user();
        if ($user->is_super_admin) return $query;

        // return $query->where
    }

    public function scopeFindTenanted($query, int $id)
    {
    }

    public function scopeTenantedMyCompanies($query)
    {
        return $query->whereIn('id', tenancy()->getCompanies()->pluck('id'));
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
