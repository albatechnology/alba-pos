@extends('layouts.app')
@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('suppliers.index') }}" class="btn btn-success" title="Back"><i
                                class="fa fa-arrow-left"></i> Back</a>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary">
                            <form method="post" action="{{ route('suppliers.store') }}" class="form-loading">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="required">Company</label>
                                        <select name="company_id" id="company_id"
                                            class="form-control select2 @error('company_ids') is-invalid @enderror"
                                            required>
                                            @foreach ($companies as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                        @error('company_id')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Code</label>
                                        <input name="code" type="text" value="{{ old('code') }}"
                                            class="form-control @error('code') is-invalid @enderror" placeholder="code"
                                            required>
                                        @error('code')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Name</label>
                                        <input name="name" type="text" value="{{ old('name') }}"
                                            class="form-control @error('name') is-invalid @enderror" placeholder="Name"
                                            required>
                                        @error('name')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Email</label>
                                        <input name="email" type="email" value="{{ old('email') }}"
                                            class="form-control @error('email') is-invalid @enderror" placeholder="email"
                                            required>
                                        @error('email')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Phone</label>
                                        <input name="phone" type="text" value="{{ old('phone') }}"
                                            class="form-control @error('phone') is-invalid @enderror" placeholder="phone"
                                            required>
                                        @error('phone')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Address</label>
                                        <input name="address" type="text" value="{{ old('address') }}"
                                            class="form-control @error('address') is-invalid @enderror"
                                            placeholder="address" required>
                                        @error('address')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Province</label>
                                        <select id="province_id" name="province_id"
                                            class="form-control select2-data-array @error('province_id') is-invalid @enderror">
                                        </select>
                                        @error('province_id')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>City / Regencies</label>
                                        <select id="city_id" name="city_id"
                                            class="form-control select2-data-array @error('city_id') is-invalid @enderror">
                                        </select>
                                        @error('city_id')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>District</label>
                                        <select id="district_id" name="district_id"
                                            class="form-control select2-data-array @error('district_id') is-invalid @enderror">
                                        </select>
                                        @error('district_id')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Village</label>
                                        <select id="village_id" name="village_id"
                                            class="form-control select2-data-array @error('village_id') is-invalid @enderror">
                                        </select>
                                        @error('village_id')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="">Description</label>
                                        <textarea name="description" type="text" value="{{ old('description') }}"
                                            class="form-control @error('description') is-invalid @enderror" placeholder="Description"></textarea>
                                        @error('description')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="required">Account Number</label>
                                        <input name="account_number" type="text" value="{{ old('account_number') }}"
                                            class="form-control @error('account_number') is-invalid @enderror"
                                            placeholder="account_number" required>
                                        @error('account_number')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Account Name</label>
                                        <input name="account_name" type="text" value="{{ old('account_name') }}"
                                            class="form-control @error('account_name') is-invalid @enderror"
                                            placeholder="account_name" required>
                                        @error('account_name')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Bank Name</label>
                                        <input name="bank_name" type="text" value="{{ old('bank_name') }}"
                                            class="form-control @error('bank_name') is-invalid @enderror"
                                            placeholder="bank_name" required>
                                        @error('bank_name')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#btnSelectAll').on('click', function() {
                $(this).parent().next().children().prop("selected", true).trigger("change");
            });
            $('#btnDeselectAll').on('click', function() {
                $(this).parent().next().children().prop("selected", false).trigger("change");
            });
        });
        {{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}
        var urlProvince = "{{ asset('regions/province.json') }}";
        var urlCity = "{{ url('get-regions/city') }}/";
        var urlDistrict = "{{ url('get-regions/district') }}/";
        var urlVillage = "{{ url('get-regions/village') }}/";

        function clearOptions(id) {
            console.log("on clearOptions :" + id);

            //$('#' + id).val(null);
            $('#' + id).empty().trigger('change');
        }

        console.log('Load province...');
        $.getJSON(urlProvince, function(res) {

            res = $.map(res, function(obj) {
                obj.text = obj.nama
                return obj;
            });

            data = [{
                id: "",
                nama: "- Choose Province -",
                text: "- Choose Province -"
            }].concat(res);

            //implemen data ke select province
            $("#province_id").select2({
                dropdownAutoWidth: true,
                width: '100%',
                data: data
            })
        });

        var selectProv = $('#province_id');
        $(selectProv).change(function() {
            var value = $(selectProv).val();
            clearOptions('city_id');

            if (value) {
                console.log("on change selectProv");

                var text = $('#province_id :selected').text();
                console.log("value = " + value + " / " + "text = " + text);

                console.log('Load city di ' + text + '...')
                $.getJSON(urlCity + value + ".json", function(res) {

                    res = $.map(res, function(obj) {
                        obj.text = obj.nama
                        return obj;
                    });

                    data = [{
                        id: "",
                        nama: "- Choose City -",
                        text: "- Choose City -"
                    }].concat(res);

                    //implemen data ke select province
                    $("#city_id").select2({
                        dropdownAutoWidth: true,
                        width: '100%',
                        data: data
                    })             })
            }
        });

        var selectCity = $('#city_id');
        $(selectCity).change(function() {
            var value = $(selectCity).val();
            clearOptions('district_id');

            if (value) {
                console.log("on change selectCity");

                var text = $('#city_id :selected').text();
                console.log("value = " + value + " / " + "text = " + text);

                console.log('Load Disctrict di ' + text + '...')
                $.getJSON(urlDistrict + value + ".json", function(res) {

                    res = $.map(res, function(obj) {
                        obj.text = obj.nama
                        return obj;
                    });

                    data = [{
                        id: "",
                        nama: "- Choose District -",
                        text: "- Choose District -"
                    }].concat(res);

                    //implemen data ke select province
                    $("#district_id").select2({
                        dropdownAutoWidth: true,
                        width: '100%',
                        data: data
                    })
                })
            }
        });

        var selectDis = $('#district_id');
        $(selectDis).change(function() {
            var value = $(selectDis).val();
            clearOptions('village_id');

            if (value) {
                console.log("on change selectDis");

                var text = $('#district_id :selected').text();
                console.log("value = " + value + " / " + "text = " + text);

                console.log('Load Village di ' + text + '...')
                $.getJSON(urlVillage + value + ".json", function(res) {

                    res = $.map(res, function(obj) {
                        obj.text = obj.nama
                        return obj;
                    });

                    data = [{
                        id: "",
                        nama: "- Choose Village -",
                        text: "- Choose Village -"
                    }].concat(res);

                    //implemen data ke select province
                    $("#village_id").select2({
                        dropdownAutoWidth: true,
                        width: '100%',
                        data: data
                    })                })
            }
        });

        var selectVill = $('#village_id');
        $(selectVill).change(function() {
            var value = $(selectVill).val();

            if (value) {
                console.log("on change selectVill");

                var text = $('#village_id :selected').text();
                console.log("value = " + value + " / " + "text = " + text);
            }
        });
    </script>
@endpush
