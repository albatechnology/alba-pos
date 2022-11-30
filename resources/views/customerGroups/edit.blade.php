@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('customer-groups.index') }}" class="btn btn-success" title="Back"><i class="fa fa-arrow-left"></i> Back</a>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary">
                            <form method="post" action="{{ route('customer-groups.update', $customerGroup->id) }}" class="form-loading">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="required">Company</label>
                                        <select name="company_id" id="company_id" class="form-control select2 @error('company_id') is-invalid @enderror" required>
                                            @foreach ($companies as $id => $name)
                                                <option value="{{$id}}" {{ $customerGroup->company_id == $id ? 'selected' : '' }}>{{$name}}</option>
                                            @endforeach
                                        </select>
                                        @error('company_id')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Tenant</label>
                                        <select name="tenant_id" id="tenant_id" class="form-control select2 @error('tenant_id') is-invalid @enderror" disabled>
                                        </select>
                                        @error('tenant_id')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Name</label>
                                        <input name="name" type="text" value="{{ $customerGroup->name }}" class="form-control @error('name') is-invalid @enderror" placeholder="Name" required>
                                        @error('name')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" placeholder="Description" rows="5">{{ $customerGroup->description }}</textarea>
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
        $(document).ready(function(){
            var companyId = '{{ $customerGroup->company_id }}';
            var tenantId = '{{ $customerGroup->tenant_id }}';
            $('#company_id').on('change', function(){
                var options = '';
                if($(this).val().length > 0){
                    options +='<option value="">- Select Tenant -</option>';
                    $.get('{{ url("tenants/get-tenants") }}?company_id='+$(this).val(), function(res){
                        res.forEach(data => {
                            var selected = tenantId == data.id ? 'selected' : '';
                            options +='<option value="'+data.id+'" '+selected+'>'+data.name+'</option>';
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
