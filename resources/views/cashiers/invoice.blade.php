<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('invoice/styleStruk.css') }}" rel="stylesheet">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js'></script>
    <link href="{{ asset('invoice/script.js') }}" rel="stylesheet">
    <title>Receipt</title>
</head>

<body>

    <!-- <button onclick="generatePDF()">Print</button> -->
    <div id="invoice-POS">
        <div id="top">
            <div class="header">
                <div class="info-header">
                    <h2>{{ $order->company->name }}</h2>
                </div>
                <p style="text-align: center; margin-left: 20px;">{{ $order->tenant->name }}</p>
                <p style="text-align: center; margin-left: 20px;">Jl. Jend. Sudirman Kav. 52-53 Lot 22, Sudirman Jl.
                    Scbd No.5, RT.5/RW.1, Senayan, Kota Jakarta Selatan, DKI Jakarta</p>
                <p style="text-align: center; margin-left: 20px;">Phone: 0856663211</p>
            </div>
            <div style="border-top: black 1px solid;" />
        </div>

        <div id="mid">
            <div class="info">
                <table>
                    {{-- <tr>
                        <td class="invoice-section" style="padding-top: 5px;">GrabFood#GF-123</td>
                    </tr> --}}
                    <tr>
                        <td class="invoice-section">{{ $order->created_at }}</td>
                    </tr>
                    <tr>
                        <td class="invoice-section">{{ $order->invoice_number }}</td>
                    </tr>
                    <tr>
                        <td style="padding-top: 5px; padding-bottom: 5px;">Cashier</td>
                        <td style="padding-top: 5px; padding-bottom: 5px;">:</td>
                        <td style="padding-top: 5px; padding-bottom: 5px;">{{ $order->user->name }}</td>
                    </tr>
                </table>
            </div>
            <div style="border-bottom: black 1px solid;" />
        </div>

        <div id="table">
            <table>
                <tr class="tabletitle table-header">
                    <td class="item">
                        <h2 class="items-text item-product">Items</h2>
                    </td>
                    <td class="Hours">
                        <h2>Qty</h2>
                    </td>
                    <td class="Rate">
                        <h2>Price</h2>
                    </td>
                    <td class="Rate">
                        <h2 class="itemprice">Subtotal</h2>
                    </td>
                </tr>

                <!-- Dine In -->
                <tr class="service">
                    <td class="tableitem">
                        <p class="itemtext item-product" style="font-weight: bold;">Dine in</p>
                    </td>
                    <td class="tableitem">
                    </td>
                    <td class="tableitem">
                    </td>
                    <td class="tableitem">
                    </td>
                </tr>

                <!-- Isi Menu -->
                @foreach ($order->orderDetails as $orders)
                    <tr class="service">
                        <td class="tableitem">
                            <p class="itemtext item-product">{{ $orders->product->name }}</p>
                            {{-- <dl>
                                <dt>Extra</dt>
                                <dt>- Spicy</dt>
                            </dl> --}}
                        </td>
                        <td class="tableitem">
                            <p class="itemtext item-qty">{{ $orders->quantity }}</p>
                            {{-- <dl>
                                <dt>&nbsp;</dt>
                                <dd>&nbsp;</dd>
                            </dl> --}}
                        </td>
                        <td class="tableitem">
                            <p class="itemtext item-price">{{ rupiah($orders->unit_price) }}</p>
                            {{-- <dl>
                                <dt>&nbsp;</dt>
                                <dd>&nbsp;</dd>
                            </dl> --}}
                        </td>
                        <td class="tableitem">
                            <p class="itemtext item-subtotal">{{ rupiah($orders->original_price) }}</p>
                            {{-- <dl>
                                <dt>&nbsp;</dt>
                                <dd>&nbsp;</dd>
                            </dl> --}}
                        </td>
                    </tr>
                @endforeach

                <!-- Takeaway -->
                {{-- <tr class="service">
                    <td class="tableitem">
                        <p class="itemtext itemtext-mt" style="font-weight: bold;">Takeaway</p>
                    </td>
                    <td class="tableitem">

                    </td>
                    <td class="tableitem">

                    </td>
                    <td class="tableitem">

                    </td>
                </tr> --}}

                <!-- Isi Menu -->

                {{-- <tr class="service">
                    <td class="tableitem">
                        <p class="itemtext">Bubur Ayam</p>
                    </td>
                    <td class="tableitem">
                        <p class="itemtext">2</p>
                    </td>
                    <td class="tableitem">
                        <p class="itemtext">24.000</p>
                    </td>
                    <td class="tableitem">
                        <p class="itemtext">24.000</p>
                    </td>
                </tr>

                <tr class="service service-end">
                    <td class="tableitem">
                        <p class="itemtext">Baso Gembus</p>
                    </td>
                    <td class="tableitem">
                        <p class="itemtext">2</p>
                    </td>
                    <td class="tableitem">
                        <p class="itemtext">20.000</p>
                    </td>
                    <td class="tableitem">
                        <p class="itemtext">20.000</p>
                    </td>
                </tr> --}}


                <!-- Sub total -->
                <tr class="tabletitle">
                    <td></td>
                    <td></td>
                    <td class="payment table-check">
                        <p style="font-size: 16px; margin: 0; margin-top: 5px; margin-left: 30px">Sub Total</p>
                    </td>
                    <td class=" table-check">
                        <p class="itemprice" style="font-size: 16px; margin: 0; margin-top: 5px; text-align:right">
                            {{ rupiah($order->original_price) }}</p>
                    </td>
                </tr>

                {{-- Discount --}}
                <tr class="tabletitle">
                    <td></td>
                    <td></td>
                    <td class="payment table-check">
                        <p style="font-size: 16px; margin: 0; margin-left: 30px">Discount</p>
                    </td>
                    <td class=" table-check">
                        <p class="itemprice" style="font-size: 16px; margin: 0; text-align:right">
                            - {{ rupiah($order->total_discount) }}</p>
                    </td>
                </tr>

                {{-- Additional Discount --}}
                @if ( $order->additional_discount!=0 )
                    <tr class="tabletitle">
                        <td></td>
                        <td></td>
                        <td class="payment table-check">
                            <p style="font-size: 16px; margin: 0; margin-left: 30px">Add Discount</p>
                        </td>
                        <td class=" table-check">
                            <p class="itemprice" style="font-size: 16px; margin: 0; text-align:right">
                                - {{ rupiah($order->additional_discount) }}</p>
                        </td>
                    </tr>
                @endif


                <!-- Tax -->
                {{-- <tr class="tabletitle ">
                    <td></td>
                    <td></td>
                    <td class="payment ">
                        <p class="payment-test" style="font-size: 16px; margin:0; margin-left: 30px;">Tax</p>
                    </td>
                    <td class=" ">
                        <p class="itemprice" style="font-size: 16px; margin: 0; text-align:right">
                            {{ rupiah($order->total_tax) }}</p>
                    </td>
                </tr> --}}


                <!-- Grand Total -->
                <tr class="tabletitle">
                    <td></td>
                    <td></td>
                    <td class="payment">
                        <h2 style="font-size: 16px; margin: 0; margin-bottom: 5px; margin-left: 30px">Grand Total</h2>
                    </td>
                    <td class="">
                        <h2 class="itemprice" style="font-size: 16px; margin: 0; margin-bottom: 5px; margin-right: 8px; text-align:right">
                            {{ rupiah($order->total_price) }}</h2>
                    </td>
                </tr>

                <!-- Type Payment -->
                <tr class="tabletitle ">
                    <td></td>
                    <td></td>
                    <td class="payment table-payment">
                        <h2 style="font-size: 16px; margin: 0; margin-top: 5px; margin-left: 30px">Tunai</h2>
                    </td>
                    <td class=" table-payment">
                        <h2 class="itemprice" style="font-size: 16px; margin: 0; margin-top: 5px; margin-right: 8px; text-align:right">
                            {{ rupiah($order->amount_paid) }}</h2>
                    </td>
                </tr>

                <!-- Change / Kembalian -->
                <tr class="tabletitle">
                    <td></td>
                    <td></td>
                    <td class="payment">
                        <h2 style="font-size: 16px; margin: 0; margin-bottom: 5px; margin-left: 30px">Change</h2>
                    </td>
                    <td class="">
                        <h2 class="itemprice" style="font-size: 16px; margin: 0; margin-bottom: 5px; margin-right: 8px; text-align:right">
                            {{ rupiah($order->amount_paid - $order->total_price) }}</h2>
                    </td>
                </tr>
            </table>
        </div>

        <div style="border-top: black 1px solid;" />

        <div id="legalcopy">
            {{-- <div class="icon-instagram">
                <img src='data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD//gAfQ29tcHJlc3NlZCBieSBqcGVnLXJlY29tcHJlc3P/2wCEAAUFBQUFBQUGBgUICAcICAsKCQkKCxEMDQwNDBEaEBMQEBMQGhcbFhUWGxcpIBwcICkvJyUnLzkzMzlHREddXX0BBQUFBQUFBQYGBQgIBwgICwoJCQoLEQwNDA0MERoQExAQExAaFxsWFRYbFykgHBwgKS8nJScvOTMzOUdER11dff/CABEIBAIEAgMBIgACEQEDEQH/xAAaAAEBAQEBAQEAAAAAAAAAAAAACQgHBgUE/9oACAEBAAAAANlgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAD8vLwAAAADrQ/Ly8AAH2/fjzXih1D9QAAPAfEAAAAAe19KAclmCAAAAAWYHJZggAA7VS4Y7xWKfdaAABNHioAAAADamxADkswQAAAACzA5LMEAAHaqXDHeKxT7rQAAJo8VAAAAAbU2IAclmCAAAAAWYHJZggAA7VS4Y7xWKfdaAABNHioAAAADamxADkswQAAAACzA5LMEAAHaqXDHeKxT7rQAAJo8VAAAAAbU2IAclmCO1dqAAADMHihZgeazAOK8VGn/ajFY9rp8cV4qKfdaAeazAO1daE0eKjagAAAeKzANqbEAOSzBG1NiAAABNHioswAY7xWKfdaEZwAFPutAOSzBG1NiCaPFRZgAAAOSzBG1NiAHJZgjamxAAAAmjxUWYAMd4rFPutCM4ACn3WgHJZgjamxBNHioswAAAHJZgjamxADkswRtTYgAAATR4qLMAGO8Vin3WhGcABT7rQDkswRtTYgmjxUWYAAADkswRtTYgByWYI2psQAAAJo8VFmADHeKxT7rQjOAAp91oByWYI2psQTR4qLMAAAByWYI2psQA5LMEbU2IMP8AMQANP6PE0eKizA55hQdq7UOofqHJQDMGYB0/7YpcPy8vHtfSiaPFRZgc8woAApcOSzBG1NiAHJZgjamxBNHioAG1NiCaPFRZgclmCNqbEAABjvFYBZgABNHioswOSzBAAWYHJZgjamxADkswRtTYgmjxUADamxBNHioswOSzBG1NiAAAx3isAswAAmjxUWYHJZggALMDkswRtTYgByWYI2psQTR4qABtTYgmjxUWYHJZgjamxAAAY7xWAWYAATR4qLMDkswQAFmByWYI2psQA5LMEbU2IJo8VAA2psQTR4qLMDkswRtTYgAAMd4rALMAAJo8VFmByWYIACzA5LMEbU2IAclmCNqbEE0eKgAbU2IJo8VFmByWYI2psQTR4qAAAAdqpcATR4qLMDkswQAFmByWYI2psQA5LMEbU2IJo8VAA2psQTR4qLMDkswRtTYgmjxUAAAA7VS4AmjxUWYHJZggALMDkswRtTYgByWYI2psQTR4qABtTYgmjxUWYHJZgjamxBNHioAAAB2qlwBNHioswOSzBAAWYHJZgjamxADkswRtTYgmjxUADamxBNHioswOSzBG1NiCaPFQAAADtVLgCaPFRZgclmCAAswOSzBG1NiAHJZgjamxBNHioAG1NiCaPFRZgclmCNqbEGIOYAPicwHtfagHFR1Ddw9r6UTR4qLMDkswQAFmByWYI2psQA5LMEbU2IJo8VAA2psQTR4qLMDkswRtTYgAHJZgjamxACM4BtTYgmjxUWYHJZggALMDkswRtTYgByWYI2psQTR4qABtTYgmjxUWYHJZgjamxAAOSzBG1NiAEZwDamxBNHioswOSzBAAWYHJZgjamxADkswRtTYgmjxUADamxBNHioswOSzBG1NiAAclmCNqbEAIzgG1NiCaPFRZgclmCAAswOSzBG1NiAHJZgjamxBNHioAG1NiCaPFRZgclmCNqbEAA5LMEbU2IARnANqbEE0eKizA5LMEABZgclmCNqbEAOSzBG1NiDOHigAO1daE0eKizA81mAdq60M4eKGxB5rMABp/0ox2Adq60Jo8VFmB5rMAADYg5LMEbU2IAclmCNqbEAAACaPFRZgABNHioswAY7xWKfdaAABNHioswAAAHJZgjamxADkswRtTYgAAATR4qLMAAJo8VFmADHeKxT7rQAAJo8VFmAAAA5LMEbU2IAclmCNqbEAAACaPFRZgABNHioswAY7xWKfdaAABNHioswAAAHJZgjamxADkswRtTYgAAATR4qLMAAJo8VFmADHeKxT7rQAAJo8VFmAAAA5LMEbU2IAclmCPa+1AAADmHxBZgc8woNP6PE0eKizA55hQdq7UMweKFLhzzCg0/o8TR4qO1AAAB8TmA2psQA5LMEAAAAAswOSzBG1NiCaPFRZgclmC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AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAB//8QAFAEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAxAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAB//8QAJxAAAAUEAwEBAQEBAQEBAAAAAAYHCBggMDdHBRZAUAQXFSdgkDj/2gAIAQEAAQgA/wDgQYjFw5T4f9nNc1IhHRIhHRIhHRIhHRIhHRIhHRIhHRIhHRIhHRIhHRIhHRIh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'
                    class="icon" />
            </div> --}}
            <div class="text-center">
                <div style="margin-top: 10px; margin-bottom: 10px;">
                    Terima kasih telah berbelanja
                </div>
            </div>
        </div>

        <div>
            &nbsp;
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
