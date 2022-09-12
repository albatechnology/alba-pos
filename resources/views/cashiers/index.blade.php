@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                {{-- <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Cashier</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Cashier v1</li>
            </ol>
          </div>
        </div> --}}
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-9 col-12">
                        @livewire('cashier.product', ['productCategories' => $productCategories, 'cart' => $cart])
                    </div>
                    <div class="col-lg-3 col-12 bg-white rounded shadow">
                        @livewire('cashier.checkout', ['cart' => $cart])
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
