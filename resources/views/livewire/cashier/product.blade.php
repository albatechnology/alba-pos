@push('css')
    <style>


    </style>
@endpush
<div class="">
    <div class="col-12">
        <div class="row">
            <div class="col-md-6 col-sm-12">

            </div>
            <div class="col-md-6 col-sm-12">
                <input wire:model="search" type="text" class="form-control" placeholder="Search Product Name">
            </div>
        </div>
    </div>
    <div class="col-12">
        <ul wire:ignore class="nav nav-tabs" role="tablist">
            @foreach ($productCategories as $category)
            <li class="nav-item">
                <a wire:click="changeProductCategory({{ $category->id }})" class="nav-link" role="tab" aria-controls="{{ $category->id }}" data-toggle="tab" aria-selected="false" href="#{{ $category->id }}">{{ $category->name }}</a>
            </li>
            @endforeach
            <li class="nav-item">
                <a wire:click="changeProductCategory()" class="nav-link active" role="tab" aria-controls="allCategories" data-toggle="tab" aria-selected="true" href="#allCategories">All Categories</a>
            </li>
        </ul>
    </div>

    <div class="tab-content">
        <div id="allCategories" class=" tabcontent col-12 tab-pane active">

            <div class="row row-cols-1 row-cols-xl-4 row-cols-lg-4 mt-2">
                @foreach ($products as $product)
                    <div class="col mb-4">
                        <div wire:click="setSelectedProductIds({{ $product->product_id }})"
                            class="card pb-0 {{ in_array($product->product_id, $selectedProductIds) ? 'bg-success' : '' }}">
                            <img src="{{ $product->product->getFirstMediaUrl('products','thumb') }}" class="card-img-top img-fluid">
                            <div class="card-body p-2">
                                <h5 class="card-title font-weight-bold" style="font-size: 14px">{{ $product->product->name }}</h5>
                                <br>
                                <h5 class="card-title" style="font-size: 14px">{{ implode(', ', $product->product->productCategories->pluck('name')->all()) }}</h5>
                                <br>
                                <p style="font-size: 14px" class="p-0 m-0">Rp. {{ number_format($product->price) }}</p>
                                <p style="font-size: 14px" class="p-0 m-0">Stock: {{ $product->stock[0]->stock }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
