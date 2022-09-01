@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('customers.index') }}" class="btn btn-success" title="Back"><i class="fa fa-arrow-left"></i> Back</a>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary">
                            <form method="post" action="{{ route('customers.store') }}" class="form-loading">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="required">Company</label>
                                        <select name="company_id" id="company_id" class="form-control select2 @error('company_id') is-invalid @enderror" required>
                                            @foreach ($companies as $id => $name)
                                                <option value="{{$id}}">{{$name}}</option>
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
                                        <input name="name" type="text" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="Name" required>
                                        @error('name')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input name="email" type="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="Email">
                                        @error('email')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Phone</label>
                                        <input name="phone" type="number" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror" placeholder="phone" required>
                                        @error('phone')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Address</label>
                                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" placeholder="Address" rows="5">{{ old('address') }}</textarea>
                                        @error('address')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" placeholder="Description" rows="5">{{ old('description') }}</textarea>
                                        @error('description')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Save</button>
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
            $('#company_id').on('change', function(){
                var options = '';
                if($(this).val().length > 0){
                    options +='<option value="">- Select Tenant -</option>';
                    $.get('{{ url("tenants/get-tenants") }}?company_id='+$(this).val(), function(res){
                        res.forEach(data => {
                            options +='<option value="'+data.id+'">'+data.name+'</option>';
                        });
                        $('#tenant_id').attr('disabled', false).html(options);
                    })
                } else {
                    $('#tenant_id').attr('disabled', true).html(options);
                }
            })
        });
    </script>
@endpush
