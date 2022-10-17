@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route($type.'s.index') }}" class="btn btn-success mr-3" title="Back"><i
                            class="fa fa-arrow-left"></i> Back</a>
                        @can('bank-accounts_create')
                        <a href="{{ route('suppliers.bank-accounts.create', $id)}}" class="btn btn-success" title="Create"><i class="fa fa-plus"></i> Add Data</a>
                        @endcan
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
                                <h3 class="card-title">Bank Accounts List</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="dttbls" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th width="10"></th>
                                                <th>ID</th>
                                                <th>Account Number</th>
                                                <th>Account Name</th>
                                                <th>Bank Name</th>
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

@push('js')
    <script>
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
        @can('bank-accounts_delete')
            let deleteButton = {
                text: 'Delete selected',
                url: "{{ route('bank-accounts.massDestroy') }}",
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
            scrollY: '50vh',
            buttons: dtButtons,
            processing: true,
            serverSide: true,
            searching: true,
            responsive: true,
            ajax: '{{ route('suppliers.bank-accounts.index', $id)}}',
            columns: [{
                    data: 'placeholder',
                    name: 'placeholder'
                },
                {
                    data: 'id',
                    name: 'id',
                },
                {
                    data: 'account_name',
                    name: 'account_name'
                },
                {
                    data: 'account_number',
                    name: 'account_number'
                },
                {
                    data: 'bank_name',
                    name: 'bank_name'
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
                $.post(`{{ url('bank-accounts') }}/` + id, {
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
