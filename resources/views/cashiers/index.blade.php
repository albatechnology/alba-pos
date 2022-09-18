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
                        <div id="container-cart">
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
                                    <label>Additional Discount</label>
                                    <input type="text" name="additional_discount" class="form-control" type="number"
                                        min="0">
                                </div>
                                <div class="form-group">
                                    <label class="required">Pay</label>
                                    <input type="text" name="amount_paid" class="form-control" type="number"
                                        min="0" required>
                                </div>
                                <div class="form-group">
                                    <label class="required">Payment Type</label>
                                    <select name="payment_type_id" class="form-control" required>
                                        @foreach ($paymentTypes as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">Proceed Payment</button>
                            </form>

                            <div class="modal fade" id="modalPayment" data-backdrop="static" data-keyboard="false"
                                tabindex="-1" aria-labelledby="modalPaymentLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable">
                                    <div class="modal-content">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('js')
    <script src="https://shaack.com/projekte/bootstrap-input-spinner/src/bootstrap-input-spinner.js"></script>
    <script>
        function refreshCart() {
            $("#container-cart").load("{{ route('cashier.cart') }}", function(html) {
                $(this).html(
                    `<div class="text-center mt-5">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>`
                );

                $(this).html(html);
            });
        }

        function removeCartDetail(cartDetailId, productId) {
            $.post("{{ url('cashier/delete-cart-detail') }}/" + cartDetailId, function(res) {
                refreshCart();
                Livewire.emit('toggleSelectedProductIds', productId);
            })
        }

        function plusMinus(productId, qty) {
            $.post("{{ url('cashier/plus-minus') }}/" + productId + '/' + qty, function(res) {
                refreshCart();
            })
        }

        window.addEventListener('refreshCart', event => {
            refreshCart();
        });

        $(document).ready(function() {
            refreshCart();

            $('#formProceedPayment').on('submit', function(e) {
                e.preventDefault();
                var data = $(this).serializeArray();

                $.post("{{ route('cashier.proceedPayment') }}", data, function(res) {
                    $('#modalPayment .modal-content').html(res)
                    $('#modalPayment').modal('show');
                });
            })
        })
    </script>
@endpush
