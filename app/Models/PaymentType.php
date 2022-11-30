<?php

namespace App\Models;

use App\Interfaces\TenantedInterface;
use App\Traits\CompanyTenantedTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentType extends Model implements TenantedInterface
{
    use SoftDeletes, CompanyTenantedTrait;
    public $table = 'payment_types';
    protected $guarded = [];

    protected static function booted()
    {
        static::saving(function ($model) {
            $paymentCategory = PaymentCategory::findOrFail($model->payment_category_id);
            $model->company_id = $paymentCategory->company_id;
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function paymentCategory()
    {
        return $this->belongsTo(PaymentCategory::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
