@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('users.index') }}" class="btn btn-success" title="Back"><i class="fa fa-arrow-left"></i> Back</a>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary">
                            <form method="post" action="{{ route('users.store') }}" enctype="multipart/form-data" class="form-loading">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="required">Name</label>
                                        <input name="name" type="text" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="Name" required>
                                        @error('name')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Email</label>
                                        <input name="email" type="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="Email" required>
                                        @error('email')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Password</label>
                                        <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required>
                                        @error('password')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Photo</label>
                                        <input name="image" type="file"class="form-control @error('image') is-invalid @enderror">
                                        @error('image')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Companies</label>
                                        <div class="mb-1">
                                            <button type="button" class="btn btn-success btn-xs btnSelectAll">Select All</button>
                                            <button type="button" class="btn btn-success btn-xs btnDeselectAll">Deselect All</button>
                                        </div>
                                        <select name="company_ids[]" id="company_ids" class="form-control select2 @error('company_ids') is-invalid @enderror" multiple required>
                                            @foreach ($companies as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                        @error('company_ids')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Tenants</label>
                                        <div class="mb-1">
                                            <button type="button" class="btn btn-success btn-xs btnSelectAll">Select All</button>
                                            <button type="button" class="btn btn-success btn-xs btnDeselectAll">Deselect All</button>
                                        </div>
                                        <select name="tenant_ids[]" id="tenant_ids" class="form-control select2 @error('tenant_ids') is-invalid @enderror" multiple disabled>
                                        </select>
                                        @error('tenant_ids')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Role</label>
                                        <select name="role_id" id="role_id" class="form-control select2 @error('role_id') is-invalid @enderror" required>
                                            @foreach ($roles as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                        @error('role_id')
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
            $('.btnSelectAll').on('click', function() {
                $(this).parent().next().children().prop("selected", true).trigger("change");
            });
            $('.btnDeselectAll').on('click', function() {
                $(this).parent().next().children().prop("selected", false).trigger("change");
            });

            $('#company_ids').on('change', function(){
                var options = '';
                if($(this).val().length > 0){
                    $.get('{{ url("tenants/get-tenants") }}?company_id='+$(this).val(), function(res){
                        res.forEach(data => {
                            options +='<option value="'+data.id+'">'+data.name+'</option>';
                        });
                        $('#tenant_ids').attr('disabled', false).html(options).val('').change();
                    })
                } else {
                    $('#tenant_ids').attr('disabled', true).html(options).val('').change();
                }
            })
        });
    </script>
@endpush
