@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('orders.create') }}" class="btn btn-success" title="Create"><i class="fa fa-plus"></i>
                            Add Data</a>
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
                                <h3 class="card-title">Orders List</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <div class="d-inline-block">
                                        <table id="dttbls" class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th width="10"></th>
                                                    <th>ID</th>
                                                    <th>Invoice Number</th>
                                                    <th>Customer</th>
                                                    <th>User</th>
                                                    <th>Tenant</th>
                                                    <th>Company</th>
                                                    <th>Payment Status</th>
                                                    <th>Total Price</th>
                                                    <th>Total Discount</th>
                                                    <th>Amount Paid</th>
                                                    <th>Note</th>
                                                    <th>Created At</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="nowrap"></tbody>
                                        </table>
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
@section('js')
    @includeIf('layouts.includes.datatableScript')
@endsection
@push('js')
    <script>
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
        @can('orders_delete')
            let deleteButton = {
                text: 'Delete selected',
                url: "{{ route('orders.massDestroy') }}",
                className: 'btn-danger',
                action: function(e, dt, node, config) {
                    var ids = $.map(dt.rows({
                        selected: true
                    }).data(), function(entry) {
                        return entry.id
                    });
                    if (ids.length === 0) {
                        alert('No data selected')
                        return
                    }

                    if (confirm('Delete selected data?')) {
                        console.log('config', config.url);
                        console.log('ids', ids);
                        $.ajax({
                                method: 'POST',
                                url: config.url,
                                data: {
                                    ids: ids,
                                    _method: 'DELETE'
                                }
                            })
                            .done(function() {
                                location.reload()
                            })
                    }
                }
            }
            dtButtons.push(deleteButton)
        @endcan

        let table = $('#dttbls').DataTable({
            scrollX: true,
            scrollY: '70vh',
            buttons: dtButtons,
            processing: true,
            serverSide: true,
            searching: true,
            responsive: true,
            ajax: '{{ route('orders.index') }}',
            columns: [{
                data: 'placeholder',
                name: 'placeholder'
            }, {
                data: 'id',
                name: 'id',
            }, {
                data: 'invoice_number',
                name: 'invoice_number'
            }, {
                data: 'customer_name',
                name: 'customer.name'
            }, {
                data: 'user_name',
                name: 'user.name'
            }, {
                data: 'tenant_name',
                name: 'tenant.name'
            }, {
                data: 'company_name',
                name: 'company.name'
            }, {
                data: 'payment_status',
                name: 'payment_status'
            }, {
                data: 'total_price',
                name: 'total_price'
            }, {
                data: 'total_discount',
                name: 'total_discount'
            }, {
                data: 'amount_paid',
                name: 'amount_paid'
            }, {
                data: 'note',
                name: 'note'
            }, {
                data: 'created_at',
                name: 'created_at'
            }, {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false
            }],
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

        function deleteData(id) {
            if (confirm('Delete data?')) {
                $.post(`{{ url('orders') }}/` + id, {
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
