@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('suppliers.index') }}" class="btn btn-success" title="Back"><i
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
                                                <th class="w-25">Title</th>
                                                <th>Values</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>ID</td>
                                                <td>{{ $supplier->id }}</td>
                                            </tr>
                                            <tr>
                                                <td>Company Name</td>
                                                <td>{{ $supplier->company->name }}</td>
                                            </tr>
                                            <tr>
                                                <td>Name</td>
                                                <td>{{ $supplier->name }}</td>
                                            </tr>
                                            <tr>
                                                <td>Email</td>
                                                <td>{{ $supplier->email}}</td>
                                            </tr>
                                            <tr>
                                                <td>Phone</td>
                                                <td>{{ $supplier->phone }}</td>
                                            </tr>
                                            <tr>
                                                <td>Address</td>
                                                <td>{{ $supplier->address }}</td>
                                            </tr>
                                            <tr>
                                                <td>Province</td>
                                                <td>{{ $supplier->province }}</td>
                                            </tr>
                                            <tr>
                                                <td>City</td>
                                                <td>{{ $supplier->city }}</td>
                                            </tr>
                                            <tr>
                                                <td>Description</td>
                                                <td>{{ $supplier->Description }}</td>
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
   <script>
    </script>
@endpush
