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
            <li class="nav-item">
                <a wire:click="changeProductCategory()" class="nav-link active" role="tab" aria-controls="allCategories" data-toggle="tab" aria-selected="true" href="#allCategories">All Categories</a>
            </li>
            @foreach ($productCategories as $category)
            <li class="nav-item">
                <a wire:click="changeProductCategory({{ $category->id }})" class="nav-link" role="tab" aria-controls="{{ $category->id }}" data-toggle="tab" aria-selected="false" href="#{{ $category->id }}">{{ $category->name }}</a>
            </li>
            @endforeach
        </ul>
    </div>

    <div class="tab-content">
        <div id="allCategories" class=" tabcontent col-12 tab-pane active">

            <div class="row row-cols-1 row-cols-xl-4 row-cols-lg-3 mt-2">
                @foreach ($products as $product)
                    <div class="col mb-4">
                        <div wire:click="setSelectedProductIds({{ $product->id }})"
                            class="card pb-0 {{ in_array($product->id, $selectedProductIds) ? 'bg-success' : '' }}">
                            <img src="{{ $product->getFirstMediaUrl('products','thumb') }}" class="card-img-top img-fluid">
                            {{-- <img src="https://asset.kompas.com/crops/-f5twHSFFkYYRxy3Cg9VytJ1i5M=/0x298:750x798/375x240/data/photo/2020/09/25/5f6da653c1860.jpg" class="card-img-top" alt="..."> --}}
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
