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
@endpush
@extends('layouts.app')
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
                    <div class="col-lg-9 col-12">
                        @livewire('cashier.product', ['productCategories' => $productCategories, 'cart' => $cart])
                    </div>
                    <div class="col-lg-3 col-12 bg-white rounded shadow">
                        <div class="sidebar-item">
                            <div class="make-me-sticky" style="overflow-y: scroll; height: 100vh">
                                <div id="container-cart" class="mt-2">
                                    <div class="text-center mt-5">
                                        <div class="spinner-border" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                                <div id="container-input-payment">
                                    <form id="formProceedPayment">
                                        @csrf
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
                                            <label>Additional Discount</label>
                                            <input type="number" name="additional_discount" id="additional_discount"
                                                class="form-control" min="0">
                                        </div>
                                        <div class="form-group">
                                            <label class="required">Payment Type</label>
                                            <select name="payment_type_id" class="form-control" required>
                                                @foreach ($paymentTypes as $id => $name)
                                                    <option value="{{ $id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="required">Pay</label>
                                            <input type="number" name="amount_paid" id="amount_paid" class="form-control"
                                                min="0" required>
                                        </div>
                                        <button type="submit" id="btnProceedPayment" class="btn btn-primary btn-block mb-4"
                                            disabled>Proceed
                                            Payment</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="modal fade" id="modalPayment" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="modalPaymentLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="https://shaack.com/projekte/bootstrap-input-spinner/src/bootstrap-input-spinner.js"></script>
    <script>
        var totalPrice = 0;
        var additionalDiscount = 0;
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

        function removeCartDetail(cartDetailId, productId) {
            $.post("{{ url('cashier/delete-cart-detail') }}/" + cartDetailId, function(res) {
                refreshCart();
                Livewire.emit('toggleSelectedProductIds', productId);
            })
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

        function calculatePayment() {
            var additionalDiscount = parseInt($('#additional_discount').val()) || 0;
            var amountPaid = parseInt($('#amount_paid').val()) || 0;

            console.log('calculatePayment totalPrice', totalPrice)
            console.log('additional_discount', additionalDiscount);
            console.log('amount_paid', amountPaid);

            if ((amountPaid + additionalDiscount) >= totalPrice) {
                $('#btnProceedPayment').attr('disabled', false);
            } else {
                $('#btnProceedPayment').attr('disabled', true);
            }
        }

        window.addEventListener('refreshCart', event => {
            refreshCart();
        });

        $(document).ready(function() {
            refreshCart();

            $('#amount_paid, #additional_discount').on('keyup', function() {
                calculatePayment();
            })

            $('#formProceedPayment').on('submit', function(e) {
                e.preventDefault();

                var additionalDiscount = parseInt($('#additional_discount').val()) || 0;
                var amountPaid = parseInt($('#amount_paid').val()) || 0;

                console.log('additionalDiscount', additionalDiscount);
                console.log('amountPaid', amountPaid);
                var data = $(this).serializeArray();
                $.post("{{ route('cashier.proceedPayment') }}", data, function(res) {
                    $('#modalPayment .modal-content').html(res)
                    $('#modalPayment').modal('show');
                });
            });

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
        })
    </script>
@endpush
