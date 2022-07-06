<?php

namespace App\Models;

use App\Interfaces\TenantedInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        if($user->is_super_admin) return $query;

        // return $query->where
    }

    public function scopeFindTenanted($query, int $id)
    {
    }
}
