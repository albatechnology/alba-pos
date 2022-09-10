@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('products.index') }}" class="btn btn-success" title="Back"><i class="fa fa-arrow-left"></i> Back</a>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary">
                            <form method="post" action="{{ route('products.store') }}" class="form-loading">
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
                                        <label class="required">Price</label>
                                        <input name="price" type="number" value="{{ old('price') }}" class="form-control @error('price') is-invalid @enderror" placeholder="price" required>
                                        @error('price')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">UOM</label>
                                        <input name="uom" type="number" value="{{ old('uom') }}" class="form-control @error('uom') is-invalid @enderror" placeholder="uom" required>
                                        @error('uom')
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
                                            <option value="{{ $id }}" {{ in_array($id, old('company_ids', [])) ? 'selected' : '' }}>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                        @error('company_ids')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Product Categories</label>
                                        <div class="mb-1">
                                            <button type="button" class="btn btn-success btn-xs btnSelectAll">Select All</button>
                                            <button type="button" class="btn btn-success btn-xs btnDeselectAll">Deselect All</button>
                                        </div>
                                        <select name="product_category_ids[]" id="product_category_ids" class="form-control select2 @error('product_category_ids') is-invalid @enderror" multiple>
                                            @foreach ($productCategories as $id => $name)
                                            <option value="{{ $id }}" {{ in_array($id, old('product_category_ids', [])) ? 'selected' : '' }}>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                        @error('product_category_ids')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Product Brand</label>
                                        <select name="product_brand_id" id="product_brand_id" class="form-control select2 @error('product_brand_id') is-invalid @enderror">
                                            @foreach ($productBrands as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                        @error('product_brand_id')
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
