<?php

namespace App\Models;

use App\Interfaces\TenantedInterface;
use App\Traits\TenantedTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model implements TenantedInterface
{
    use HasFactory;
    use TenantedTrait;

    public $table = 'bank_accounts';
    protected $guarded = [];

    public function bankAccountable()
    {
        return $this->morphTo();
    }
}
