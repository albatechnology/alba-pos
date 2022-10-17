@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route($type.'s.bank-accounts.index', $id) }}" class="btn btn-success" title="Back"><i
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
                                                <td>{{ $bankAccount->id }}</td>
                                            </tr>
                                            <tr>
                                                <td>Holder Name</td>
                                                <td>{{ $bankAccount->bankAccountable->name }}</td>
                                            </tr>
                                            <tr>
                                                <td>Holder Type</td>
                                                <td>{{ str_replace("App\Models\\", "", $bankAccount->bank_accountable_type) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Account Number</td>
                                                <td>{{ $bankAccount->account_number }}</td>
                                            </tr>
                                            <tr>
                                                <td>Account Name</td>
                                                <td>{{ $bankAccount->account_name }}</td>
                                            </tr>
                                            <tr>
                                                <td>Bank Name</td>
                                                <td>{{ $bankAccount->bank_name }}</td>
                                            </tr>
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

@endpush
