<?php

namespace App\Pipes\Order;

use App\Enums\PaymentStatus;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;
use Closure;
use Illuminate\Support\Facades\DB;

class SaveOrder
{
    public function handle(Order $order, Closure $next)
    {
        // dump('SaveOrder');
        // dd($order);
        $order = DB::transaction(function () use ($order) {
            $orderDetails = $order->order_details;

            $paymentTypeId = $order->payment_type_id ?? null;
            $amountPaid = $order->amount_paid ?? null;
            // $orderShipment = $order->order_shipment;

            unset($order->payment_type_id);
            unset($order->raw_source);
            unset($order->request);
            unset($order->order_details);
            // unset($order->order_shipment);

            $order->save();
            $order->orderDetails()->saveMany($orderDetails);
            // // $order->orderShipment()->save($orderShipment);

            if ($paymentTypeId && !is_null($amountPaid)) {
                $payment = $this->createPayment($order, $paymentTypeId);
            }

            Cart::where('user_id', $order->user_id)->where('tenant_id', $order->tenant_id)->delete();

            return $order;
        });

        return $next($order);
    }

    public function createPayment(Order $order, $paymentTypeId)
    {
        // dump($paymentTypeId);
        // dd($order);
        $user = user();
        return Payment::create([
            'order_id' => $order->id,
            'company_id' => $user->company_id,
            'tenant_id' => $user->tenant_id,
            'payment_type_id' => $paymentTypeId,
            'added_by_id' => $user->id,
            'approved_by_id' => $user->id,
            'status' => PaymentStatus::APPROVED,
            'value' => $order->amount_paid,
        ]);
    }
}
