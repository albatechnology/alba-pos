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
                            <form method="post" action="{{ route('stocks.update', $stock->id) }}" class="form-loading">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <label>Company       :  </label> {{ $stock->company->name }}<br>
                                    <label>Tenant        :  </label> {{ $stock->tenant->name }}<br>
                                    <label>Product       :  </label> {{ $stock->product->name }}<br>
                                    <label>Current stock :  </label> {{ $stock->stock }}<br>
                                    <div class="form-group">
                                        <label class="required">Option</label>
                                        <div>
                                            <div class="form-check form-check-inline">
                                                <input name="option" type="radio" value="increase" class="form-check-input radio-inline @error('option') is-invalid @enderror">
                                                <label class="form-check-label" for="check_add">Increase</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input name="option" type="radio" value="decrease" class="form-check-input radio-inline @error('option') is-invalid @enderror">
                                                <label class="form-check-label" for="check_subtract">Decrease</label>
                                            </div>
                                        </div>
                                        @error('option')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Amount</label>
                                        <input name="amount" type="number" value="{{ old('amount') }}" class="form-control @error('amount') is-invalid @enderror" placeholder="amount" required>
                                        @error('amount')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Update</button>
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
    <script>
        $(document).ready(function() {
            var companyId = '{{ $stock->company_id }}';
            var tenantId = '{{ $stock->tenant_id }}';
            $('#company_id').on('change', function() {
                var options = '';
                if ($(this).val().length > 0) {
                    options += '<option value="">- Select Tenant -</option>';
                    $.get('{{ url('tenants/get-tenants') }}?company_id=' + $(this).val(), function(res) {
                        res.forEach(data => {
                            var selected = tenantId == data.id ? 'selected' : '';
                            options += '<option value="' + data.id + '" ' + selected + '>' +
                                data.name + '</option>';
                        });
                        $('#tenant_id').attr('disabled', false).html(options);
                    })
                } else {
                    $('#tenant_id').attr('disabled', true).html(options);
                }
            })
            $('#company_id').val(companyId).change();
        });
    </script>
@endpush
