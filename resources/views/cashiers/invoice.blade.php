<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"  integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <style>
        body{
            font-size: 10px;
        }
        hr{
            color: #000000 !important;
        }
    </style>
</head>

<body>
    <!-- <button onclick="generatePDF()">Print</button> -->
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h4>{{ $order->company->name }}</h4>
                <p class="my-1">{{ $order->tenant->name }}</p>
                <p class="my-1">Jl. Jend. Sudirman Kav. 52-53 Lot 22, Sudirman Jl.
                    Scbd No.5, RT.5/RW.1, Senayan, Kota Jakarta Selatan, DKI Jakarta</p>
                <p class="my-1">Phone: 0856663211</p>
                <hr class="my-1">
            </div>
            <div class="col-12">
                <table>
                    <tr>
                        <td>{{ $order->created_at }}</td>
                    </tr>
                    <tr>
                        <td>{{ $order->invoice_number }}</td>
                    </tr>
                    <tr>
                        <td>Cashier : {{ $order->user->name }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-12">
                <table class="w-100">
                    <tr>
                        <td colspan="6"><hr class="m-0"></td>
                    </tr>
                    <tr>
                        <th class="text-start">Items</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Price</th>
                        <th class="text-end">Subtotal</th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                        <td colspan="6"><hr class="m-0"></td>
                    </tr>

                    <!-- Isi Menu -->
                    @foreach ($order->orderDetails as $orders)
                        <tr>
                            <td class="text-left">
                                <p>{{ $orders->product->name }}</p>
                            </td>
                            <td class="text-center">
                                <p>{{ $orders->quantity }}</p>
                            </td>
                            <td class="text-center">
                                <p>{{ number_format($orders->unit_price) }}</p>
                            </td>
                            <td class="text-end">
                                <p>{{ number_format($orders->original_price) }}</p>
                            </td>
                        </tr>
                    @endforeach

                    <!-- Sub total -->
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="text-start fw-bold">Sub Total</td>
                        <td class="text-end">{{ number_format($order->original_price) }}</td>
                    </tr>

                    {{-- Discount --}}
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="text-start fw-bold">Discount</td>
                        <td class="text-end">{{ number_format($order->total_discount) }}</td>
                    </tr>

                    {{-- Additional Discount --}}
                    @if ($order->additional_discount != 0)
                        <tr>
                            <td></td>
                            <td></td>
                            <td class="text-start fw-bold">Add. Discount</td>
                            <td class="text-end">{{ number_format($order->additional_discount) }}</td>
                        </tr>
                    @endif

                    <!-- Grand Total -->
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="text-start fw-bold">Grand Total</td>
                        <td class="text-end">{{ number_format($order->total_price) }}</td>
                    </tr>

                    <!-- Type Payment -->
                    {{-- <tr class="border-top border-dark mt-2"> --}}
                    <tr>
                        <td></td>
                        <td></td>
                        <td colspan="2"><hr class="my-1 text-dark"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="text-start fw-bold">Tunai</td>
                        <td class="text-end">{{ number_format($order->amount_paid) }}</td>
                    </tr>

                    <!-- Change / Kembalian -->
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="text-start fw-bold">Change</td>
                        <td class="text-end">{{ number_format($order->amount_paid - $order->total_price) }}</td>
                    </tr>
                </table>
                <div class="text-center">
                    <hr class="my-2">
                    <p class="fw-bold mb-0">Terima kasih telah berbelanja</p>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.print();

        // setTimeout(function (){
        //     window.onafterprint = window.close;
        // }, 4000);

        // window.onafterprint = window.close;
    </script>
</body>

</html>
