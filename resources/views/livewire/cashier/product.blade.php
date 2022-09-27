@push('css')
    <style>


    </style>
@endpush
<div class="row">
    <div class="col-12">
        <h4>Product Categories</h4>
        <button wire:click="changeProductCategory()" class="btn btn-app">
            <i class="fas fa-heart"></i> All Category
        </button>
        @foreach ($productCategories as $category)
            <button wire:click="changeProductCategory({{ $category->id }})" class="btn btn-app">
                <i class="fas fa-heart"></i> {{ $category->name }}
            </button>
        @endforeach
        <hr>
    </div>
    <div class="col-12">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <h4>Product</h4>
            </div>
            <div class="col-md-6 col-sm-12">
                <input wire:model="search" type="text" class="form-control" placeholder="Search Product Name">
            </div>
        </div>
        <div class="row row-cols-1 row-cols-xl-4 row-cols-lg-3 mt-2">
            @foreach ($products as $product)
                <div class="col mb-4">
                    <div wire:click="setSelectedProductIds({{ $product->id }})"
                        class="card h-100 {{ in_array($product->id, $selectedProductIds) ? 'bg-success' : '' }}">
                        {{-- <img src="{{ $product->getFirstMediaUrl('products','thumb') }}" class="card-img-top" alt="..."> --}}
                        <img src="https://asset.kompas.com/crops/-f5twHSFFkYYRxy3Cg9VytJ1i5M=/0x298:750x798/375x240/data/photo/2020/09/25/5f6da653c1860.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title font-weight-bold", style="font-size: 16px">{{ $product->name }}</h5>
                            <br>
                            <h5>Rp. {{ number_format($product->price) }}</h5>
                            <h5>ID: {{ $product->id }}</h5>
                            <h5 wire:ignore>Stock: {{ $product->stock }}</h5>
                            <br>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
