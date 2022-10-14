@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('bank-accounts.index') }}" class="btn btn-success" title="Back"><i class="fa fa-arrow-left"></i> Back</a>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary">
                            <form method="post" action="{{ route('bank-accounts.store') }}" class="form-loading">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="required">Account Number</label>
                                        <input name="account_number" type="text" value="{{ old('account_number') }}" class="form-control @error('account_number') is-invalid @enderror" placeholder="account_number" required>
                                        @error('account_number')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Account Name</label>
                                        <input name="account_name" type="text" value="{{ old('account_name') }}" class="form-control @error('account_name') is-invalid @enderror" placeholder="account_name" required>
                                        @error('account_name')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Bank Name</label>
                                        <input name="bank_name" type="text" value="{{ old('bank_name') }}" class="form-control @error('bank_name') is-invalid @enderror" placeholder="bank_name" required>
                                        @error('bank_name')
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

    </script>
@endpush
