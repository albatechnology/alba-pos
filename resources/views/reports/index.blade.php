@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <a href="/" class="btn btn-success" title="Back"><i class="fa fa-arrow-left"></i>
                            Back</a>
                        <input type='text' readonly id='date' class="datepicker" placeholder='Pick date'>
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
                                <h3 class="card-title">Daily Report</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="dttbls" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th width="10"></th>
                                                <th>Date</th>
                                                <th>Invoice Number</th>
                                                <th>Total Price</th>
                                                <th>Total Discount</th>
                                                <th>Amount Paid</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
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
    <script>
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

        $(document).ready(function() {
            new DateTime(document.getElementById('date'));
        });

        let table = $('#dttbls').DataTable({
            buttons: dtButtons,
            processing: true,
            serverSide: true,
            searching: true,
            responsive: true,
            ajax: '{{ route('orders.index') }}',
            columns: [{
                    data: 'placeholder',
                    name: 'placeholder'
                },

                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'invoice_number',
                    name: 'invoice_number'
                },
                {
                    data: 'total_price',
                    name: 'total_price'
                },
                {
                    data: 'total_discount',
                    name: 'total_discount'
                },
                {
                    data: 'amount_paid',
                    name: 'amount_paid'
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                }
            ],
            orderCellsTop: true,
            order: [
                [1, 'desc']
            ],
            pageLength: 25,
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

        // $.fn.dataTable.ext.search.push(
        //     function(settings, data, dataIndex) {
        //         var search = searchDate.val();
        //         var date = new Date(data[1]);

        //         return true;
        //     }
        // );

        // $(document).ready(function() {
        //     // Create date inputs
        //     searchDate = new DateTime($('#date'), {
        //         format: 'MMMM Do YYYY'
        //     });


        //     // DataTables initialisation
        //     var table = $('#dttbls').DataTable();

        //     // Refilter the table
        //     $('#date').on('change', function() {
        //         table.draw();
        //     });
        // });

        function deleteData(id) {
            if (confirm('Delete data?')) {
                $.post(`{{ url('reports') }}/` + id, {
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
