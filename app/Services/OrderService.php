<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Pipeline\Pipeline;

class OrderService
{
    public static function previewOrder(Order $order)
    {
        return app(Pipeline::class)
            ->send($order)
            ->through([
                \App\Pipes\Order\FillOrderAtributes::class,
                \App\Pipes\Order\MakeOrderDetails::class,
                // \App\Pipes\Order\ApplyDiscount::class,
                \App\Pipes\Order\ApplyAdditionalDiscount::class,
                // \App\Pipes\Order\CalculateShipment::class,
                // \App\Pipes\Order\CheckExpectedOrderPrice::class,
                // \App\Pipes\Order\SaveOrder::class,
                // \App\Pipes\Order\UpdateDiscountUse::class,
            ])->thenReturn();
    }

    public static function processOrder(Order $order)
    {
        return app(Pipeline::class)
            ->send($order)
            ->through([
                \App\Pipes\Order\FillOrderAtributes::class,
                \App\Pipes\Order\MakeOrderDetails::class,
                // \App\Pipes\Order\ApplyDiscount::class,
                \App\Pipes\Order\ApplyAdditionalDiscount::class,
                // \App\Pipes\Order\CalculateShipment::class,
                // \App\Pipes\Order\CheckExpectedOrderPrice::class,
                // \App\Pipes\Order\SaveOrder::class,
                // \App\Pipes\Order\UpdateDiscountUse::class,
            ])->thenReturn();
    }
}
