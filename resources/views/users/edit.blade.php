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
                            <form method="post" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data" class="form-loading">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="required">Name</label>
                                        <input name="name" type="text" value="{{ $user->name }}" class="form-control @error('name') is-invalid @enderror" placeholder="Name" required>
                                        @error('name')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Email</label>
                                        <input name="email" type="email" value="{{ $user->email }}" class="form-control @error('email') is-invalid @enderror" placeholder="Email" required>
                                        @error('email')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Change Password</label>
                                        <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password">
                                        @error('password')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Photo</label>
                                        <input name="image" type="file" class="form-control @error('image') is-invalid @enderror">
                                        <img src="{{ $user->photo }}" alt="">
                                        @error('image')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group @error('tenant_ids') has-error @enderror">
                                        <label class="required">Tenants</label>
                                        <div class="mb-1">
                                            <button type="button" class="btn btn-success btn-xs" id="btnSelectAll">Select All</button>
                                            <button type="button" class="btn btn-success btn-xs" id="btnDeselectAll">Deselect All</button>
                                        </div>
                                        <select name="tenant_ids[]" class="form-control select2 @error('tenant_ids') is-invalid @enderror" multiple required>
                                            @foreach ($tenants as $tenant)
                                            <option value="{{ $tenant->id }}" {{ in_array($tenant->id, $userTenants) ? 'selected' : '' }}>{{ $tenant->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('tenant_ids')
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
            $('#btnSelectAll').on('click', function() {
                $(this).parent().next().children().prop("selected", true).trigger("change");
            });
            $('#btnDeselectAll').on('click', function() {
                $(this).parent().next().children().prop("selected", false).trigger("change");
            });
        });
    </script>
@endpush
