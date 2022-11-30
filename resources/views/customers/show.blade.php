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
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="dttbls" class="table table-bordered table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Values</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="w-50">ID</td>
                                                <td>{{ $customer->id }}</td>
                                            </tr>
                                            <tr>
                                                <td>Name</td>
                                                <td>{{ $customer->name }}</td>
                                            </tr>
                                            <tr>
                                                <td>Email</td>
                                                <td>{{ $customer->email }}</td>
                                            </tr>
                                            <tr>
                                                <td>Phone</td>
                                                <td>{{ $customer->phone }}</td>
                                            </tr>
                                            <tr>
                                                <td>Address</td>
                                                <td>{{ $customer->address }}</td>
                                            </tr>
                                            <tr>
                                                <td>Description</td>
                                                <td>{{ $customer->description }}</td>
                                            </tr>
                                            <tr>
                                                <td>Customer's Group</td>
                                                <td>
                                                    @foreach ($customer->customerGroups as $group)
                                                        {{ $group->name }} <br>
                                                    @endforeach
                                                </td>
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
    <script></script>
@endpush
