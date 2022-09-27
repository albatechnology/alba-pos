@push('css')
    <style>


    </style>
@endpush
<div class="row">
    <div class="col-12">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link" role="tab" aria-controls="ayamgoreng" data-toggle="tab" aria-selected="true" href="#ayamgoreng" >Food</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" role="tab" aria-controls="minuman" data-toggle="tab" aria-selected="false" href="#minuman" >Drink</a>
            </li>
        </ul>
        {{-- <h4>Product Categories</h4>
        <button wire:click="changeProductCategory()" class="btn btn-app">
            <i class="fas fa-heart"></i> All Category
        </button>
        @foreach ($productCategories as $category)
            <button wire:click="changeProductCategory({{ $category->id }})" class="btn btn-app">
                <i class="fas fa-heart"></i> {{ $category->name }}
            </button>
        @endforeach
        <hr> --}}
    </div>

    <div class="tab-content">
        <div id="ayamgoreng" class=" tabcontent col-12 tab-pane active">
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
                            class="card pb-0 {{ in_array($product->id, $selectedProductIds) ? 'bg-success' : '' }}">
                            {{-- <img src="{{ $product->getFirstMediaUrl('products','thumb') }}" class="card-img-top" alt="..."> --}}
                            <img src="https://asset.kompas.com/crops/-f5twHSFFkYYRxy3Cg9VytJ1i5M=/0x298:750x798/375x240/data/photo/2020/09/25/5f6da653c1860.jpg" class="card-img-top" alt="...">
                            <div class="card-body p-2">
                                <h5 class="card-title font-weight-bold", style="font-size: 14px">{{ $product->name }}</h5>
                                <br>
                                <p style="font-size: 14px" class="p-0 m-0">Rp. {{ number_format($product->price) }}</p>
                                {{-- <h5>ID: {{ $product->id }}</h5> --}}
                                <p style="font-size: 14px" wire:ignore class="p-0 m-0">Stock: {{ $product->stock }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div id="minuman" class=" tabcontent col-12 tab-pane fade">
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
                            class="card pb-0 {{ in_array($product->id, $selectedProductIds) ? 'bg-success' : '' }}">
                            {{-- <img src="{{ $product->getFirstMediaUrl('products','thumb') }}" class="card-img-top" alt="..."> --}}
                            <img src="https://asset.kompas.com/crops/EZsn83PlQzi3QufGsbA1RH4tTiM=/0x0:780x520/750x500/data/photo/2020/11/19/5fb5de3b29211.jpg" class="card-img-top" alt="...">
                            <div class="card-body p-2">
                                <h5 class="card-title font-weight-bold", style="font-size: 14px">{{ $product->name }}</h5>
                                <br>
                                <p style="font-size: 14px" class="p-0 m-0">Rp. {{ number_format($product->price) }}</p>
                                {{-- <h5>ID: {{ $product->id }}</h5> --}}
                                <p style="font-size: 14px" wire:ignore class="p-0 m-0">Stock: {{ $product->stock }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
    function POS(evt, food) {
      var i, tabcontent, tablinks;
      tabcontent = document.getElementsByClassName("tabcontent");
      for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
      }
      tablinks = document.getElementsByClassName("tablinks");
      for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
      }
      document.getElementById(food).style.display = "block";
      evt.currentTarget.className += " active";
    }
</script>
