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

    public function scopeWhereId($query, $id)
    {
        return $query->where('bank_accountable_id', $id);
    }

    public function scopeWhereType($query, $type)
    {
        return $query->where('bank_accountable_type', $type);
    }

}
