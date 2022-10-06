<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PDF</title>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap");

        @media print {
            footer {
                break-before: page;
            }

            tr {
                page-break-inside: avoid !important;
                -webkit-column-break-inside: avoid;
                break-inside: avoid;
                -webkit-region-break-inside: avoid;
            }
        }

        tr {
            page-break-inside: avoid !important;
            page-break-after: auto !important;
        }

        html {
            background-color: white;
        }

        body {
            font-size: 12px;
            min-height: 100%;
            font-family: Open Sans, Arial, Helvetica, sans-serif;
            margin: 2, 5%;
            padding: 20px 60px;
        }

        footer {
            font-size: 12px;
            font-family: Open Sans, Arial, Helvetica, sans-serif;
        }

        h1 {
            text-align: center;
        }

        hr {
            height: 2px;
            border-width: 0;
            color: #313132;
            background-color: #313132;
            margin-top: 24px;
        }

        .italic {
            font-style: italic;
        }

        .topContainer {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            margin-top: 12px;
            margin-bottom: 24px;
        }

        .logo {
            width: 10%;
            height: 10%;
            margin-left: auto;
            margin-right: auto;
            display: block;
            margin-bottom: 20px;
        }

        .quotationTitle {
            font-size: 26px;
            font-weight: bold;
            text-align: center;
        }

        .metadata {
            display: flex;
            flex-direction: row;
            margin-top: 4px;
        }

        .metadata-title {
            width: 120px;
            font-weight: bold;
            flex-shrink: 0;
        }

        .table {
            width: 100%;
            margin-top: 32px;
            border-spacing: 0;

        }

        th {
            padding: 8px;
            /* border-top: 1px solid #313132; */
            border-bottom: 1px solid #313132;
            text-align: left;
            background-color: black;
            color: white;
        }

        td {
            padding: 8px;
            text-align: left;
            vertical-align: middle;
            border-bottom: 1px solid #ddd
        }

        table tr:first-child th:first-child {
            /* border-left: 1px solid #313132; */
        }

        table tr:first-child th:last-child {
            /* border-right: 1px solid #313132; */
        }

        .info-row-left {
            /* border-left: 1px solid #313132; */
            text-align: right;
        }

        .info-row-right {
            /* border-right: 1px solid #313132; */
            text-align: right;
        }

        .items-table tr td:first-child {
            /* border-left: 1px solid #313132; */
        }

        .items-table tr td:last-child {
            /* border-right: 1px solid #313132; */
        }

        .items-table tr td:nth-child(n + 4) {
            text-align: left;
        }

        .items-table tr:last-child td {
            /* border-bottom: 1px solid #313132; */
        }

        .alt-table {
            width: 100%;
            margin-top: 32px;
            border-spacing: 0;
        }

        .alt-table td {
            background-color: #ef633f;
            color: white;
            padding: 8px;
            border-top: 1px solid #313132;
            border-bottom: 1px solid #313132;
        }

        .alt-table tr:first-child td:first-child {
            border-left: 1px solid #313132;
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }

        .alt-table tr:first-child td:last-child {
            border-right: 1px solid #313132;
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
        }

        .footer-section {
            display: flex;
            flex: 1;
            flex-direction: row;
            justify-content: space-between;
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <table style="width: 100%; border: 0; margin-top: 12px; margin-bottom: 24px;">
        <tr>
            <td style="text-align: left; padding: 0;">
                <img src="{{ asset('images/logo-pempek.jpg') }}" class="logo" alt="Logo" />
            </td>
        </tr>
        <tr>
            <td style="text-align: right; padding: 0; width: 30%; vertical-align: top;">
                <table style="width: 100%; border: 0;">
                    <tr>
                        <div style="text-align: center; font-weight: bold; font-size: 26px;">
                            INVOICE
                        </div>
                    </tr>
                    <tr>
                        <td style="width:50% border: 0; padding: 0;">
                            <table>
                                <tr>
                                    <td style="text-align: left; border: 0; padding: 0;">
                                        {{ $data['order']->customer?->name ?? 'No Customer' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: left; border: 0; padding: 0;">
                                        {{ $data['order']->company->name }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align: left; border: 0; padding: 0;">
                                        {{ $data['order']->tenant->name }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="width:50%; border: 0; padding: 0;">
                            <table style="width: 100%; display: flex; justify-content: flex-end">
                                <tbody>
                                    <tr>
                                        <td style="text-align: left; border: 0; padding: 0; font-weight: bold;">
                                            Invoice Number</td>
                                        <td style="text-align: left; border: 0; padding: 0; padding-left: 10px">
                                            : {{ $data['order']->invoice_number }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left; border: 0; padding: 0; font-weight: bold;">
                                            Invoice Date</td>
                                        <td style="text-align: left; border: 0; padding: 0; padding-left: 10px">
                                            : {{ $data['order']->created_at }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left; border: 0; padding: 0; font-weight: bold;">
                                            Payment Type</td>
                                        <td style="text-align: left; border: 0; padding: 0;  padding-left: 10px">
                                            : {{ $data['order']->payment->paymentType->name }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div>

        <table class="table items-table">
            <tr>
                <th>No.</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total Price</th>
            </tr>
            @php
                $no = 1;
                $subTotal = 0;
            @endphp
            @foreach ($data['order']->orderDetails as $item)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $item->product?->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ rupiah($item->unit_price) }}</td>
                    <td>{{ rupiah($item->original_price) }}</td>
                </tr>
                @php $subTotal += $item->original_price @endphp
            @endforeach
        </table>
    </div>
    <div>
        <table class="table items-table">
            <tr>
                <td colspan="6" style="font-weight: bold">Sub Total</td>
                <td class="info-row-right">
                    {{ rupiah($subTotal) }}
                </td>
            </tr>
            <tr>
                <td colspan="6" style="font-weight: bold">Discount</td>
                <td class="info-row-right">
                    {{ rupiah($data['order']->total_discount) }}
                </td>
            </tr>
            <tr>
                <td colspan="6" style="font-weight: bold">Additional Discount</td>
                <td class="info-row-right">
                    {{ rupiah($data['order']->additional_discount) }}
                </td>
            </tr>
            <tr style="margin-bottom: 10px;">
                <td colspan="6" style="border-bottom: 3px solid #000000; font-weight: bold">Total</td>
                <td class="info-row-right" style="border-bottom: 3px solid #000000;">
                    {{ rupiah($subTotal - $data['order']->additional_discount - $data['order']->total_discount) }}
                </td>
            </tr>
        </table>

    </div>
    </table>
</body>

</html>
