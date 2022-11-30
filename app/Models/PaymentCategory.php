<?php

namespace App\Models;

use App\Interfaces\TenantedInterface;
use App\Traits\CompanyTenantedTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentCategory extends Model implements TenantedInterface
{
    use SoftDeletes, CompanyTenantedTrait;
    public $table = 'payment_categories';
    protected $guarded = [];

    protected $casts = [
        'is_exact_change' => 'boolean'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function paymentTypes()
    {
        return $this->belongsTo(PaymentType::class);
    }
}
