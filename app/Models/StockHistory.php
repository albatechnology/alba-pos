<?php

namespace App\Models;

use App\Enums\StockTypeEnum;
use App\Interfaces\TenantedInterface;
use App\Traits\TenantedTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model implements TenantedInterface
{
    use HasFactory;
    use TenantedTrait;
    public $table = 'stock_histories';
    protected $guarded = [];
    protected $casts = [
        'type' => StockTypeEnum::class
    ];

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
