@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('stocks.index') }}" class="btn btn-success" title="Back"><i
                                class="fa fa-arrow-left"></i> Back</a>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Values</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>ID</td>
                                                <td>{{ $stock->id }}</td>
                                            </tr>
                                            <tr>
                                                <td>Product Name</td>
                                                <td>{{ $stock->product->name }}</td>
                                            </tr>
                                            <tr>
                                                <td>Stock</td>
                                                <td>{{ $stock->stock }}</td>
                                            </tr>
                                            <tr>
                                                <td>Tenant</td>
                                                <td>{{ $stock->tenant->name }}</td>
                                            </tr>
                                            <tr>
                                                <td>Company</td>
                                                <td>{{ $stock->company->name }}</td>
                                            </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="dttbls" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th width="10"></th>
                                                <th>ID</th>
                                                <th>Stock</th>
                                                <th>Type</th>
                                                <th>Changes</th>
                                                <th>Old Amount</th>
                                                <th>New Amount</th>
                                                <th>Source</th>
                                                <th>Created At</th>
                                                <th>Updated At</th>
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
@section('js')
    @includeIf('layouts.includes.datatableScript')
@endsection
@push('js')
    <script>
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
        @can('stocks-histories_delete')
            let deleteButton = {
                text: 'Delete selected',
                url: "{{ route('stocks-histories.massDestroy') }}",
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
            buttons: dtButtons,
            processing: true,
            serverSide: true,
            searching: true,
            responsive: true,
            // ajax: '{{ route('stocks-histories.index') }}',
            ajax: '{{ route('stocks-histories.index') }}?stock_id={{ $stock->id}}',
            columns: [{
                    data: 'placeholder',
                    name: 'placeholder'
                },
                {
                    data: 'id',
                    name: 'id',
                },
                {
                    data: 'stock_id',
                    name: 'stock.id'
                },
                {
                    data: 'type',
                    name: 'type'
                },
                {
                    data: 'changes',
                    name: 'changes'
                },
                {
                    data: 'old_amount',
                    name: 'old.amount'
                },
                {
                    data: 'new_amount',
                    name: 'new.amount'
                },
                {
                    data: 'source',
                    name: 'source'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'updated_at',
                    name: 'updated_at'
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

        function deleteData(id) {
            if (confirm('Delete data?')) {
                $.post(`{{ url('stocks-histories') }}/` + id, {
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
