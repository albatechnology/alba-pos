<?php

namespace App\Models;

use App\Enums\StockTypeEnum;
use App\Interfaces\TenantedInterface;
use App\Traits\TenantedTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class StockHistory extends Model implements TenantedInterface
{
    use HasFactory;
    use TenantedTrait;
    public $table = 'stock_histories';
    protected $guarded = [];
    protected $casts = [
        'type' => StockTypeEnum::class
    ];

    public function revertStock()
    {
        $this->stock->update([
            'stock' =>  $this->old_amount
        ]);
        $this->delete();

        return;
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
