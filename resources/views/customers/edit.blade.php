@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('customers.index') }}" class="btn btn-success" title="Back"><i
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
                            <form method="post" action="{{ route('customers.update', $customer->id) }}"
                                class="form-loading">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="required">Company</label>
                                        <select name="company_id" id="company_id"
                                            class="form-control select2 @error('company_id') is-invalid @enderror" required>
                                            @foreach ($companies as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ $customer->company_id == $id ? 'selected' : '' }}>{{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('company_id')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Tenant</label>
                                        <select name="tenant_id" id="tenant_id"
                                            class="form-control select2 @error('tenant_id') is-invalid @enderror">
                                            @foreach ($tenants as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ $customer->tenant_id == $id ? 'selected' : '' }}>{{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('tenant_id')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Customer Group</label>
                                        <select multiple name="customer_group_ids[]" id="customer_group_ids"
                                            class="form-control select2 @error('customer_group_ids') is-invalid @enderror">
                                            @foreach ($customerGroups as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ in_array($id, $customerCustomerGroups) ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('customer_group_ids')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Name</label>
                                        <input name="name" type="text" value="{{ $customer->name }}"
                                            class="form-control @error('name') is-invalid @enderror" placeholder="Name"
                                            required>
                                        @error('name')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input name="email" type="email" value="{{ $customer->email }}"
                                            class="form-control @error('email') is-invalid @enderror" placeholder="Email">
                                        @error('email')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Phone</label>
                                        <input name="phone" type="number" value="{{ $customer->phone }}"
                                            class="form-control @error('phone') is-invalid @enderror" placeholder="phone"
                                            required>
                                        @error('phone')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Address</label>
                                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" placeholder="Address"
                                            rows="5">{{ $customer->address }}</textarea>
                                        @error('address')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" placeholder="Description"
                                            rows="5">{{ $customer->description }}</textarea>
                                        @error('description')
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

            var options = '';
            var optionCustomerGroups = '';
            // var companyId = '{{ $customer->company_id }}';
            var tenantId = '{{ $customer->tenant_id }}';
            // var customerGroups = '{{ $customer->customerGroups }}'
            var customerGroupIds = {{ Js::from($customerCustomerGroups) }};
            // console.log('customerGroupIdscustomerGroupIds', customerGroupIds)
            // console.log('customerGroupIdscustomerGroupIds', customerGroupIds.includes(3))
            // Array.from(customerGroups).forEach(customerGroup => {
            //     customerGroupIds.push(customerGroup.id);
            // });

            $('#company_id').on('change', function() {
                $('#customer_group_ids').attr('disabled', true).html('');
                if ($(this).val().length > 0) {
                    options = '<option value="">- Select Tenant -</option>';
                    $.get('{{ url('tenants/get-tenants') }}?company_id=' + $(this).val(), function(res) {
                        res.forEach(data => {
                            var selected = tenantId == data.id ? 'selected' : '';
                            options += '<option value="' + data.id + '" ' + selected + '>' +
                                data.name +
                                '</option>';
                        });
                        $('#tenant_id').attr('disabled', false).html(options);
                    })
                } else {
                    $('#tenant_id').attr('disabled', true).html('');
                }
            })
            // $('#company_id').val(companyId).change();

            $('#tenant_id').on('change', function() {
                if ($(this).val().length > 0) {
                    optionCustomerGroups = '<option value="">- Select Customer Group -</option>';
                    $.get('{{ url('customer-groups/get-customer-groups') }}?tenant_id=' + $(this).val(),
                        function(res) {
                            res.forEach(data => {
                                // var selected = customerGroupIds == data.id ? 'selected' : '';
                                var selected = customerGroupIds.includes(data.id)
                                optionCustomerGroups += '<option value="' + data.id + '"' +
                                    selected + '>' +
                                    data.name + '</option>';
                            });
                            $('#customer_group_ids').attr('disabled', false).html(optionCustomerGroups);
                        })
                } else {
                    $('#customer_group_ids').attr('disabled', true).html('');
                }
            })
            // $('#tenant_id').val(tenantId).change();
        });
    </script>
@endpush
