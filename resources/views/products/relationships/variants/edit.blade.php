@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('products.tenants.index', $product->id) }}" class="btn btn-success" title="Back"><i class="fa fa-arrow-left"></i> Back</a>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary">
                            <form method="post" action="{{ route('products.tenants.update', [$product->id, $productTenant->id]) }}" enctype="multipart/form-data" class="form-loading">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="product_id" value="{{ $productTenant->product_id }}">

                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="required">Company</label>
                                        <input type="text" value="{{ $productTenant->tenant->company->name }}" class="form-control @error('company_id') is-invalid @enderror" readonly>
                                        @error('company_id')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Tenant</label>
                                        <input type="text" value="{{  $productTenant->tenant->name }}" class="form-control @error('tenant_id') is-invalid @enderror" readonly>
                                        @error('tenant_id')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Name</label>
                                        <input type="text" value="{{ $productTenant->product->name }}" class="form-control @error('name') is-invalid @enderror" readonly>
                                        @error('name')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">UOM</label>
                                        <input name="uom" type="number" value="{{ $productTenant->uom ? $productTenant->uom : $productTenant->product->uom }}" class="form-control @error('uom') is-invalid @enderror" placeholder="uom" required>
                                        @error('uom')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Price</label>
                                        <input name="price" type="number" value="{{ $productTenant->price ? $productTenant->price : $productTenant->product->price }}" class="form-control @error('price') is-invalid @enderror" placeholder="price" required>
                                        @error('price')
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
