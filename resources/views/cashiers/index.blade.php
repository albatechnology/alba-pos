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
                                <a href="{{ route('cashier.payment') }}" class="btn btn-primary btn-block">Payment</a>
                                {{-- <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modalPay">Payment</button> --}}
                                {{-- <div id="container-input-payment">
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
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="modal fade" id="modalPay" tabindex="-1" aria-labelledby="modalPayLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content" style="height: 100vh">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPayLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Cart</h3>
                                    </div>
                                    <div class="card-body" id="cart-list">
                                        <ul class="list-unstyled">
                                            <li class="media">
                                                <img src="..." class="mr-3" alt="...">
                                                <div class="media-body">
                                                    <h5 class="mt-0 mb-1">Product Name</h5>
                                                    <p>Rp. 15.000</p>
                                                </div>
                                            </li>
                                            <li class="media">
                                                <img src="..." class="mr-3" alt="...">
                                                <div class="media-body">
                                                    <h5 class="mt-0 mb-1">Product Name</h5>
                                                    <p>Rp. 15.000</p>
                                                </div>
                                            </li>
                                            <li class="media">
                                                <img src="..." class="mr-3" alt="...">
                                                <div class="media-body">
                                                    <h5 class="mt-0 mb-1">Product Name</h5>
                                                    <p>Rp. 15.000</p>
                                                </div>
                                            </li>
                                        </ul>
                                        <div>
                                            <ul>
                                                <li>Sub Total : 10000</li>
                                                <li>Discount : 10000</li>
                                                <li>Total : 10000</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <h1>Lorem ipsum dolor sit amet consectetur adipisicing elit. Iste, doloribus pariatur.
                                    Reiciendis asperiores quisquam accusantium obcaecati? Aperiam, ab praesentium distinctio
                                    facilis iure vitae vel, ex sit expedita veniam, repellendus tenetur.</h1>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="modal fade" id="modalPayment" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="modalPaymentLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
            </div>
        </div>
    </div> --}}
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
            $('body').addClass('sidebar-collapse');

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

            // new
            $('#modalPay').on('shown.bs.modal', function(e) {
                $.get("{{ url('cashier/cart-list') }}", function(res){
                    console.log('res',res)

                    var html = '';
                    res.cart.cart_details.forEach(detail => {
                        html += '<li class="media">';
                            // html += '<img src="..." class="mr-3" alt="...">';
                            html += '<div class="media-body">';
                                html += '<h5 class="mt-0 mb-1">'+detail.product.name+'e</h5>';
                                html += '<p>'+detail.total_price+'</p>';
                            html += '</div>';
                        html += '</li>';
                    });

                    html += '<div>';
                        html += '<ul>';
                            html += '<li>Sub Total : '+res.sub_total_price+'</li>';
                            html += '<li>Discount : '+res.cart.total_discount+'</li>';
                            html += '<li>Total : '+res.total_price+'</li>';
                        html += '</ul>';
                    html += '</div>';

                    $('#cart-list').html(html);
                });
            });
        })
    </script>
@endpush
