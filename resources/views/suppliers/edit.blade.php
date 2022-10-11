@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('suppliers.index') }}" class="btn btn-success" title="Back"><i class="fa fa-arrow-left"></i> Back</a>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary">
                            <form method="post" action="{{ route('suppliers.update', $supplier->id) }}" enctype="multipart/form-data" class="form-loading">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="company_id" value="{{ $supplier->company_id }}">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="required">Company</label>
                                        <input type="text" value="{{ $supplier->company->name }}" class="form-control @error('company_id') is-invalid @enderror" readonly>
                                        @error('company_id')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Code</label>
                                        <input name="code" type="text" value="{{ $supplier->code }}" class="form-control @error('code') is-invalid @enderror" placeholder="code" required>
                                        @error('code')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Name</label>
                                        <input name="name" type="text" value="{{ $supplier->name }}" class="form-control @error('name') is-invalid @enderror" placeholder="Name" required>
                                        @error('name')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Email</label>
                                        <input name="email" type="email" value="{{ $supplier->email }}" class="form-control @error('email') is-invalid @enderror" placeholder="email" required>
                                        @error('email')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Phone</label>
                                        <input name="phone" type="number" value="{{ $supplier->phone }}" class="form-control @error('phone') is-invalid @enderror" placeholder="phone" required>
                                        @error('phone')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Address</label>
                                        <input name="address" type="text" value="{{ $supplier->address }}" class="form-control @error('address') is-invalid @enderror" placeholder="address" required>
                                        @error('address')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Province</label>
                                        <input name="province" type="text" value="{{ $supplier->province }}" class="form-control @error('province') is-invalid @enderror" placeholder="province" required>
                                        @error('province')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">City</label>
                                        <input name="city" type="text" value="{{ $supplier->city }}" class="form-control @error('city') is-invalid @enderror" placeholder="city" required>
                                        @error('city')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="">Description</label>
                                        <textarea name="description" type="text"
                                            class="form-control @error('description') is-invalid @enderror" placeholder="Description">{{ $supplier->description }}</textarea>
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
            $('.btnSelectAll').on('click', function() {
                $(this).parent().next().children().prop("selected", true).trigger("change");
            });
            $('.btnDeselectAll').on('click', function() {
                $(this).parent().next().children().prop("selected", false).trigger("change");
            });
        });
    </script>
@endpush
