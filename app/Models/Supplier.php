<?php

namespace App\Models;

use App\Interfaces\TenantedInterface;
use App\Traits\CompanyTenantedTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model implements TenantedInterface
{
    use HasFactory;
    use CompanyTenantedTrait;
    public $table = 'suppliers';
    protected $guarded = [];

    public function company(){

        return $this->belongsTo(Company::class);
    }
}
