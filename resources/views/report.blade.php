{{-- @extends('layouts.app')
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
                                    <input onfocus="blur();" type="text" class="form-control float-right"
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
    </script>
@endpush --}}

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
                            <div class="card-header row">
                                <h3 class="card-title col-sm">Product Report</h3>
                                <h3 class="card-title col-sm">Company: {{$company?->name ?? "All Companies"}} </h3>
                                <h3 class="card-title col-sm">Tenant: {{$tenant?->name ?? "All Tenants"}}</h3>
                            </div>
                            <div class="card-body">
                                <div class="input-group pb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-clock"></i></span>
                                    </div>
                                    <input onfocus="blur();" type="text" class="form-control float-right"
                                        value="{{ $startDate . ' - ' . $endDate }}" id="reservation">
                                </div>
                                <div class="table-responsive">
                                    <table id="dttbls" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th width='10'></th>
                                                <th>Name</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan='3'></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
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
            window.location.replace('{{ url('/product-report') }}?start_date=' + startDate + '&end_date=' +
            endDate);
        });

        // let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

        let table = $('#dttbls').DataTable({
            scrollY: '50vh',
            // buttons: dtButtons,
            processing: true,
            serverSide: true,
            searching: true,
            responsive: true,
            ajax: '{{ route('productReport') }}?start_date={{ $startDate }}&end_date={{ $endDate }}',
            columns: [{
                    data: 'placeholder',
                    name: 'placeholder'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'price',
                    name: 'price'
                },
                {
                    data: 'order_details_sum_quantity',
                    name: 'order_details_sum_quantity'
                },
                {
                    data: 'total',
                    name: 'total'
                }
            ],
            orderCellsTop: true,
            order: [
                [1, 'desc']
            ],
            pageLength: 25,
            "footerCallback": function(row, data, start, end, display) {
                var api = this.api(),
                    data;

                // converting to interger to find total
                var intVal = function(i) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i === 'number' ?
                        i : 0;
                };

                // computing column Total of the complete result
                var totalQuantity = api
                    .column(3)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                var totalPrice = api
                    .column(4)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                // Update footer by showing the total with the reference of the column index
                $(api.column(0).footer()).html('Grand Total');
                $(api.column(3).footer()).html(totalQuantity);
                $(api.column(4).footer()).html(totalPrice);
            },
        });
        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });

        let visibleColumnsIndexes = null;
        $('.datatable thead').on('input', '.search', function() {
            let strict = $(this).attr('strict') || false
            let value = strict && this.value ? "^" + this.value + "$" : this.value

            let index = $(this).parent().index()
            if (visibleColumnsIndexes !== null) {
                index = visibleColumnsIndexes[index]
            }

            table
                .column(index)
                .search(value, strict)
                .draw()
        });
        table.on('column-visibility.dt', function(e, settings, column, state) {
            visibleColumnsIndexes = []
            table.columns(":visible").every(function(colIdx) {
                visibleColumnsIndexes.push(colIdx);
            });
        });

        function deleteData(id) {
            if (confirm('Delete data?')) {
                $.post(`{{ url('products') }}/` + id, {
                    _method: 'delete'
                }, function(res) {
                    if (res.success) {
                        table.ajax.reload();
                        toastr.success(res.message);
                    } else {
                        toastr.error(res.message);
                    }
                }, 'json');
            }
        }
    </script>
@endpush
