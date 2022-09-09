@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('customers.index') }}" class="btn btn-success" title="Back"><i class="fa fa-arrow-left"></i> Back</a>
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
                                <label>ID: </label> {{$customer->id}} <br>
                                <label>Name: </label> {{$customer->name}} <br>
                                <label>Email: </label> {{$customer->email}} <br>
                                <label>Phone: </label> {{$customer->phone}} <br>
                                <label>Address: </label> {{$customer->address}} <br>
                                <label>Description: </label> {{$customer->description}} <br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('js')
    <script>
    </script>
@endpush
