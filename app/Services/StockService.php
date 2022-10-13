<?php

namespace App\Services;

use App\Models\Stock;
use Exception;
use Illuminate\Support\Facades\DB;

class StockService
{
    /**
     * get user cart by active tenant
     *
     * @return Cart|null
     */
    public static function revertStock($stockHistories)
    {
        foreach($stockHistories as $stockHistory){
            $stockHistory->revertStock();
        }
        return;
    }

}
