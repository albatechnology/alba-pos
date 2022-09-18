<div class="row">
    <div class="col-12">
        <h4>Product Categories</h4>
        <h1>{{ $selectedProductCategoryId }}</h1>
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
        <div class="row row-cols-1 row-cols-md-4 mt-2">
            @foreach ($products as $product)
                <div class="col mb-4">
                    <div wire:click="setSelectedProductIds({{ $product->id }})" class="card h-100 {{ in_array($product->id, $selectedProductIds) ? 'bg-success' : '' }}">
                        {{-- <img src="..." class="card-img-top" alt="..."> --}}
                        <div class="card-body">
                            <h5 class="card-title font-weight-bold">{{ $product->name }}</h5>
                            <br>
                            <h5>Rp. {{ number_format($product->price) }}</h5>
                            <h5>ID: {{ $product->id }}</h5>
                            <br>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
