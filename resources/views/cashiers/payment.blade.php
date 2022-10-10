@extends('layouts.app')
@push('css')
    <style>
        .sidebar-item {
            position: relative;
            top: 0;
            width: 100%;
            height: 100%;
        }

        .make-me-sticky {
            position: -webkit-sticky;
            position: sticky;
            top: 0;
            padding: 0 15px;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('plugins/minimal-numeric-keypad/jquery.keypad.css') }}">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
@endpush
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                {{-- <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Cashier</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Cashier v1</li>
            </ol>
          </div>
        </div> --}}
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            {{-- <div class="card-header">
                                <h3 class="card-title">Cart</h3>
                            </div> --}}
                            <div class="card-body" id="container-cart">
                                <div class="text-center">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                                {{-- <ul class="list-unstyled">
                                    @foreach ($cart->cartDetails as $detail)
                                        <li class="media">
                                            <img src="{{ $detail->product->getFirstMediaUrl('products', 'thumb') }}"
                                                class="mr-3" alt="">
                                            <div class="media-body">
                                                <h5 class="mt-0 mb-1">{{ $detail->product->name }}</h5>
                                                <p>{{ rupiah($detail->total_price) }}</p>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                                <div>
                                    <h4>Sub Total : {{ rupiah($cart->total_price) }}</h4>
                                    <h4>Discount : {{ rupiah($cart->total_price) }}</h4>
                                    <h4>Total : {{ rupiah($cart->total_price) }}</h4>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <form id="formProceedPayment">
                                @csrf
                                <input type="hidden" name="is_order" value="1">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Discount</label>
                                        <select name="discount_id" id="discount_id" class="form-control">
                                            @foreach ($discounts as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ $cart?->discount_id == $id ? 'selected' : '' }}>
                                                    {{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Payment</label>
                                        <br>
                                        @foreach ($paymentTypes as $pt)
                                            <label for="{{ $pt->id }}">{{ $pt->name }}</label>
                                            <input class="select-payment" type="radio" name="payment_type_id"
                                                id="{{ $pt->id }}" value="{{ $pt->id }}"
                                                data-is_exact_change="{{ $pt->paymentCategory->is_exact_change }}">
                                        @endforeach
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Pay</label>
                                        <input name="amount_paid" value="0" id="amount_paid"
                                            class="form-control keypad" min="0" required readonly>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div id="keypad" class=""></div>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="customer_name" placeholder="Customer Name"
                                                class="form-control mb-2 @error('name') is-invalid @enderror" required>
                                            <input type="number" name="customer_phone" placeholder="Customer Phone"
                                                class="form-control mb-2 @error('phone') is-invalid @enderror">
                                            <input type="email" name="customer_email" placeholder="Customer Email"
                                                class="form-control mb-2 @error('email') is-invalid @enderror">
                                            <textarea name="customer_address" placeholder="Customer Address"
                                                class="form-control mb-2 @error('customer_address') is-invalid @enderror" rows="4"></textarea>
                                            <br>
                                            <div class="form-group">
                                                <div class="custom-control custom-radio">
                                                    <input class="custom-control-input" type="radio" id="print_invoice"
                                                        name="print_invoice" value="1" checked>
                                                    <label for="print_invoice" class="custom-control-label">Print
                                                        Invoice</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input class="custom-control-input" type="radio" id="download_invoice"
                                                        name="print_invoice" value="0">
                                                    <label for="download_invoice" class="custom-control-label">Download PDF
                                                        Invoice</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" id="btnProceedPayment" class="btn btn-danger"
                                        disabled>Order</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('js')
    <script src="https://shaack.com/projekte/bootstrap-input-spinner/src/bootstrap-input-spinner.js"></script>
    <script src="{{ asset('plugins/minimal-numeric-keypad/jquery.keypad.js') }}"></script>
    <script src="{{ asset('plugins/jquery.priceformat.min.js') }}"></script>
    <script>
        var totalPrice = 0;
        // var additionalDiscount = 0;
        var amountPaid = 0;

        function refreshCart() {
            $("#container-cart").load("{{ route('cashier.cart') }}", function(html) {
                $(this).html(
                    `<div class="text-center mt-5">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>`
                );

                $(this).css({
                    'opacity': 1,
                    'pointer-events': 'initial'
                }).html(html);
            });
        }

        function plusMinus(productId, qty) {
            $('#container-cart').css({
                'opacity': 0.4,
                'pointer-events': 'none'
            })
            $.post("{{ url('cashier/plus-minus') }}/" + productId + '/' + qty, function(res) {
                refreshCart();
            })
        }

        function removeCartDetail(cartDetailId, productId) {
            $('#container-cart').css({
                'opacity': 0.4,
                'pointer-events': 'none'
            })
            $.post("{{ url('cashier/delete-cart-detail') }}/" + cartDetailId, function(res) {
                refreshCart();
                // Livewire.emit('toggleSelectedProductIds', productId);
            })
        }

        function calculatePayment() {
            // var additionalDiscount = parseInt($('#additional_discount').val()) || 0;
            var amountPaid = parseInt($('#amount_paid').val()) || 0;

            console.log('calculatePayment totalPrice', totalPrice)
            // console.log('additional_discount', additionalDiscount);
            console.log('amount_paid', amountPaid);

            if (amountPaid >= totalPrice) {
                $('#btnProceedPayment').attr('disabled', false);
            } else {
                $('#btnProceedPayment').attr('disabled', true);
            }
        }

        $(document).ready(function() {
            $('body').addClass('sidebar-collapse');
            $('#keypad').keypad();
            $(".select-payment").checkboxradio();

            refreshCart();

            // $('#amount_paid').priceFormat({
            //     thousandsSeparator: '.',
            //     centsLimit: 0
            // });

            $('#amount_paid').number(true);

            $('#amount_paid, #additional_discount').on('change', function() {
                calculatePayment();
            })

            $('#discount_id').on('change', function() {
                $('#container-cart').css({
                    'opacity': 0.4,
                    'pointer-events': 'none'
                })
                $.post("{{ url('cashier/setDiscount') }}/" + $(this).val(), function(res) {
                    if (typeof res !== 'undefined') {
                        refreshCart();
                    } else {
                        $('#container-cart').css({
                            'opacity': 1,
                            'pointer-events': 'initial'
                        });
                    }
                })
            });

            $('#formProceedPayment').on('submit', function(e) {
                e.preventDefault();
                var dataOrder = $(this).serializeArray();
                $.post("{{ route('cashier.proceedPayment') }}", dataOrder, function(res) {
                    if (res.success == false) {
                        window.location.replace("{{ url('cashier') }}");
                    } else {
                        if ($('input[name="print_invoice"]:checked').val() == 1) {
                            window.open("{{ url('cashier/invoice') }}/" + res.order_id);
                        } else {
                            window.open("{{ url('orders/invoice') }}/" + res.order_id);
                        }
                        window.location.replace("{{ url('cashier') }}");
                    }
                })
                // $.post("{{ route('cashier.proceedPayment') }}", data, function(res) {
                //     $('#modalPayment .modal-content').html(res)
                //     $('#modalPayment').modal('show');
                // });
            });

            $(".select-payment").change(function() {
                if ($(this).data('is_exact_change') == 1) {
                    $('#amount_paid').val(0)
                } else {
                    $('#amount_paid').val(totalPrice ?? 0)
                }
                calculatePayment();
            });
        });
    </script>
@endpush
