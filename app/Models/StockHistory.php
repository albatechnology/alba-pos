<?php

namespace App\Models;

use App\Enums\StockTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    use HasFactory;

    public $table = 'stock_histories';
    protected $guarded = [];
    protected $casts = [
        'type' => StockTypeEnum::class
    ];

    public function stock()
    {
        return $this->belongsTo(Company::class, 'stock_id');
    }

    public function user()
    {
        return $this->belongsTo(Tenant::class, 'user_id');
    }
}
