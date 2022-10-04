@extends('layouts.app')
@push('css')
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
@endpush
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <a href="{{ url('/') }}" class="btn btn-success" title="Back"><i class="fa fa-arrow-left"></i>
                            Back</a>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Product Report</h3>
                            </div>
                            <div class="card-body">
                                <div class="input-group pb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-clock"></i></span>
                                    </div>
                                    <input type="text" class="form-control float-right"
                                        value="{{ $startDate . ' - ' . $endDate }}" id="reservation">
                                </div>
                                <div class="table-responsive">
                                    <table id="dttbls" class="table table-bordered table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $totalQuantity = 0;
                                                $grandTotal = 0;
                                            @endphp
                                            @foreach ($productReport as $products)
                                                @php
                                                    $totalQuantity += $products->order_details_sum_quantity;
                                                    $subTotal = $products->order_details_sum_quantity * $products->price;
                                                    $grandTotal += $subTotal;
                                                @endphp
                                                <tr>
                                                    <td>{{ $products->name }}</td>
                                                    <td>{{ rupiah($products->price) }}</td>
                                                    <td>{{ $products->order_details_sum_quantity }}</td>
                                                    <td>{{ rupiah($subTotal) }}</td>

                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="2">Subtotal</td>
                                                <td>{{ $totalQuantity }}</td>
                                                <td>{{ rupiah($grandTotal) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
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
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>

    <script>
        $('#reservation').daterangepicker({
            locale: {
                format: 'YYYY/MM/DD'
            }
        })

        $('#reservation').on('apply.daterangepicker', function(ev, picker) {
            var startDate = picker.startDate.format('YYYY-MM-DD');
            var endDate = picker.endDate.format('YYYY-MM-DD');
            console.log(picker.startDate.format('YYYY-MM-DD'));
            console.log(picker.endDate.format('YYYY-MM-DD'));
            window.location.replace('{{ url('/product-report') }}?start_date=' + startDate + '&end_date=' + endDate);
        });

        $("#reservation").keydown(function(event) {
            return false;
        });
    </script>
@endpush
