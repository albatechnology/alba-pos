<?php

namespace App\Models;

use App\Interfaces\TenantedInterface;
use App\Traits\TenantedTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model implements TenantedInterface
{
    use HasFactory;
    use TenantedTrait;

    protected $guarded = [];
    public $table = 'discounts';

    public function company(){
        return $this->belongsTo(Company::class);
    }
}
