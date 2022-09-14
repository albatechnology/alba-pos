@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('products.index') }}" class="btn btn-success" title="Back"><i
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
                                                <td>{{ $product->id }}</td>
                                            </tr>
                                            <tr>
                                                <td>Name</td>
                                                <td>{{ $product->name }}</td>
                                            </tr>
                                            <tr>
                                                <td>Price</td>
                                                <td>{{ $product->price }}</td>
                                            </tr>
                                            <tr>
                                                <td>UOM</td>
                                                <td>{{ $product->uom }}</td>
                                            </tr>
                                            <tr>
                                                <td>Brands</td>
                                                <td>{{ $product->productBrand->name }}</td>
                                            </tr>
                                            <tr>
                                                <td>Categories</td>
                                                <td>
                                                    @foreach ($product->productCategories as $categories)
                                                        {{ $categories->name }} <br>
                                                    @endforeach
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Company</td>
                                                <td>{{ $product->company->name }}</td>
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
