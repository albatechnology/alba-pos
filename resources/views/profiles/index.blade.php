@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <a href="/" class="btn btn-success" title="Back"><i class="fa fa-arrow-left"></i> Back</a>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid mt-4 mb-4  justify-content-center">
                <div class="row">
                    <div class="col-12">
                        <div class="card p-4">
                            <div class=" image d-flex flex-column justify-content-center align-items-center">
                                @foreach ($profile as $item)
                                    <button class="btn btn-secondary"> <img src={{ $item->photo }} height="100"
                                            width="100" /></button>
                                    <span class="name mt-3">{{ $item->name }}</span>
                                    <span class="email">{{ $item->email }}</span>
                                @endforeach
                                {{-- <span class="idd">@eleanorpena</span>
                                <div class="d-flex flex-row justify-content-center align-items-center gap-2"> <span
                                        class="idd1">Oxc4c16a645_b21a</span> <span><i class="fa fa-copy"></i></span>
                                </div> --}}
                                {{-- <div class="d-flex flex-row justify-content-center align-items-center mt-3"> <span
                                        class="number">1069 <span class="follow">Followers</span></span> </div> --}}

                                <div class=" d-flex mt-2">
                                    <a href="{{ route('profiles.edit', ['profile' => auth()->user()->id]) }}">
                                        <button class="btn1 btn-dark">Edit Profile</button>
                                    </a>
                                </div>
                                {{-- <div class="text mt-3"> <span>Eleanor Pena is a creator of minimalistic x bold
                                        graphics and digital artwork.<br><br> Artist/ Creative Director by Day #NFT
                                        minting@ with FND night. </span> </div>
                                <div class="gap-3 mt-3 icons d-flex flex-row justify-content-center align-items-center">
                                    <span><i class="fa fa-twitter"></i></span> <span><i class="fa fa-facebook-f"></i></span>
                                    <span><i class="fa fa-instagram"></i></span> <span><i class="fa fa-linkedin"></i></span>
                                </div>
                                <div class=" px-2 rounded mt-4 date "> <span class="join">Joined May,2021</span>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection