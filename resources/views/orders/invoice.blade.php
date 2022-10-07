<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <style>
        /* @import url("https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap"); */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;1,900&display=swap');
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #5D6975;
            text-decoration: underline;
        }

        body {
            font-family: 'Poppins', sans-serif;
            position: relative;
            /* width: 21cm; */
            /* height: 29.7cm; */
            margin: 0 auto;
            color: #001028;
            background: #FFFFFF;
            font-size: 12px;
        }

        header {
            padding: 10px 0;
            margin-bottom: 30px;
        }

        #logo {
            text-align: center;
            margin-bottom: 10px;
        }

        #logo img {
            width: 90px;
        }

        h1 {
            border-top: 1px solid #5D6975;
            border-bottom: 1px solid #5D6975;
            color: #5D6975;
            font-size: 2.4em;
            line-height: 1.4em;
            font-weight: normal;
            text-align: center;
            margin: 0 0 20px 0;
            background: url(dimension.png);
        }

        #project {
            float: left;
        }

        #project span {
            color: #5D6975;
            text-align: right;
            width: 100px;
            margin-right: 10px;
            display: inline-block;
            font-size: 0.8em;
        }

        #company {
            float: right;
            text-align: right;
        }

        #project div,
        #company div {
            white-space: nowrap;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 20px;
        }

        table tr:nth-child(2n-1) td {
            background: #F5F5F5;
        }

        table th,
        table td {
            text-align: center;
        }

        table th {
            padding: 5px 20px;
            color: #5D6975;
            border-bottom: 1px solid #C1CED9;
            white-space: nowrap;
            font-weight: normal;
        }

        table .service,
        table .service,
        table .service,
        table .desc {
            text-align: left;
        }

        table td {
            padding: 20px;
            text-align: right;
        }

        table td.service,
        table td.desc {
            vertical-align: top;
        }

        table td.unit,
        table td.qty,
        table td.total {
            font-size: 1.2em;
            text-align: left;
        }

        table td.grand {
            border-top: 1px solid #5D6975;
        }

        #notices .notice {
            color: #5D6975;
            font-size: 1.2em;
        }

        footer {
            color: #5D6975;
            width: 100%;
            height: 30px;
            position: absolute;
            bottom: 0;
            border-top: 1px solid #C1CED9;
            padding: 8px 0;
            text-align: center;
        }
    </style>
</head>

<body>
    <header class="clearfix">
        <div id="logo">
            <img src="images/logo-pempek.jpg">
        </div>
        <h1>INVOICE</h1>
        <div id="company" class="clearfix">
            <div>{{ $data['order']->customer?->name ?? 'No Customer' }}</div>
            <div>{{ $data['order']->customer?->email ?? '' }}</div>
            <div>{{ $data['order']->customer?->phone ?? '' }}</div>
            <div>{{ $data['order']->customer?->address ?? '' }}</div>
        </div>
        <div id="project">
            <div><span>INVOICE NUMBER</span> {{ $data['order']->invoice_number }}</div>
            <div><span>INVOICE DATE</span> {{ $data['order']->created_at }}</div>
            <div><span>PAYMENT TYPE</span> {{ $data['order']->payment->paymentType->name }}</div>
        </div>
    </header>
    <br>
    <br>
    <br>
    <main>
        <table>
            <thead>
                <tr>
                    <th class="service">NO</th>
                    <th class="desc">PRODUCT</th>
                    <th class="service">QUANTITY</th>
                    <th class="service">PRICE</th>
                    <th class="service">TOTAL PRICE</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                    $subTotal = 0;
                @endphp
                @foreach ($data['order']->orderDetails as $item)
                    <tr>
                        <td class="service">{{ $no++ }}</td>
                        <td class="desc">{{ $item->product?->name }}</td>
                        <td class="unit">{{ $item->quantity }}</td>
                        <td class="qty">{{ rupiah($item->unit_price) }}</td>
                        <td class="total">{{ rupiah($item->original_price) }}</td>
                    </tr>
                    @php $subTotal += $item->original_price @endphp
                @endforeach
                <tr>
                    <td colspan="4">SUBTOTAL</td>
                    <td class="total">{{ rupiah($subTotal) }}</td>
                </tr>
                <tr>
                    <td colspan="4">DISCOUNT</td>
                    <td class="total">- {{ rupiah($data['order']->total_discount) }}</td>
                </tr>
                <tr>
                    <td colspan="4">ADDITIONAL DISCOUNT</td>
                    <td class="total">- {{ rupiah($data['order']->additional_discount) }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="grand total">TOTAL</td>
                    <td class="grand total">{{ rupiah($subTotal - $data['order']->additional_discount - $data['order']->total_discount) }}</td>
                </tr>
            </tbody>
        </table>
        {{-- <div id="notices">
            <div>NOTICE:</div>
            <div class="notice">A finance charge of 1.5% will be made on unpaid balances after 30 days.</div>
        </div> --}}
    </main>
</body>

</html>
