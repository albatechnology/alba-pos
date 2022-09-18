<?php

namespace App\Pipes\Order;

use App\Models\Order;
use Closure;

class GenerateInvoiceNumber
{
    public function handle(Order $order, Closure $next)
    {
        // $order->invoice_number = CompanyData::getInvoiceNumber($order->company_id, $order->created_at ?? now());

        // $invoice_id = DB::transaction(function () use ($company_id) {
        //     // Get current invoice id to use. We use lock for update
        //     // to prevent other thread to read this row until we update it
        //     $inv_id = DB::table('company_data')
        //         ->where('company_id', $company_id)
        //         ->lockForUpdate()
        //         ->first('next_invoice_id')
        //         ->next_invoice_id;

        //     // increment the invoice id
        //     DB::table('company_data')
        //         ->where('company_id', $company_id)
        //         ->increment('next_invoice_id');

        //     return $inv_id;
        // });

        // $time = $time ?? now();

        $order->invoice_number = sprintf('INV%s', date('Ymdhis'));
        $order->save();

        return $next($order);
    }
}
