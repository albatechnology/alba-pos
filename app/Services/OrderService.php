<?php

namespace App\Services;

use App\Models\Order;
use App\Pipes\Order\ApplyAdditionalDiscount;
use App\Pipes\Order\ApplyDiscount;
use App\Pipes\Order\FillOrderAtributes;
use App\Pipes\Order\MakeOrderDetails;
use App\Pipes\Order\ProcessAmountPaid;
use App\Pipes\Order\SaveCustomer;
use App\Pipes\Order\SaveOrder;
use App\Pipes\Order\SetPaymentType;
use Illuminate\Pipeline\Pipeline;

class OrderService
{
    public static function previewOrder(Order $order)
    {
        return app(Pipeline::class)
            ->send($order)
            ->through([
                FillOrderAtributes::class,
                MakeOrderDetails::class,
                ApplyDiscount::class,
                ApplyAdditionalDiscount::class,
                ProcessAmountPaid::class,
                SetPaymentType::class,
                // CalculateShipment::class,
                // CheckExpectedOrderPrice::class,
                // SaveOrder::class,
                // UpdateDiscountUse::class,
            ])->thenReturn();
    }

    public static function processOrder(Order $order)
    {
        return app(Pipeline::class)
            ->send($order)
            ->through([
                FillOrderAtributes::class,
                MakeOrderDetails::class,
                ApplyDiscount::class,
                ApplyAdditionalDiscount::class,
                ProcessAmountPaid::class,
                SetPaymentType::class,
                // CalculateShipment::class,
                // CheckExpectedOrderPrice::class,
                SaveCustomer::class,
                SaveOrder::class,
                // CreatePayment::class,
                // UpdateDiscountUse::class,
            ])->thenReturn();
    }
}
