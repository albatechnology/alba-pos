@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('discounts.index') }}" class="btn btn-success" title="Back"><i
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
                                                <td>ID</td>
                                                <td>{{ $discount->id }}</td>
                                            </tr>
                                            <tr>
                                                <td>Company</td>
                                                <td>{{ $discount->company->name }}</td>
                                            </tr>
                                            <tr>
                                                <td>Name</td>
                                                <td>{{ $discount->name }}</td>
                                            </tr>
                                            <tr>
                                                <td>Description</td>
                                                <td>{{ $discount->description }}</td>
                                            </tr>
                                            <tr>
                                                <td>Type</td>
                                                <td>{{ $discount->type==0?'Nominal':'Percentage' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Value</td>
                                                <td>{{ $discount->value }}</td>
                                            </tr>
                                            <tr>
                                                <td>Is Active</td>
                                                <td>{{ $discount->is_active==0?'Inactive':'Active' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Start Date</td>
                                                <td>{{ $discount->start_date }}</td>
                                            </tr>
                                            <tr>
                                                <td>End Date</td>
                                                <td>{{ $discount->end_date }}</td>
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
