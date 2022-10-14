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
                                        <select name="company_id" id="company_id" class="form-control select2 @error('company_ids') is-invalid @enderror" required>
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
                                            class="form-control @error('address') is-invalid @enderror" placeholder="address"
                                            required>
                                        @error('address')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Province</label>
                                        <select id="select2-provinsi" name="province" class="form-control select2-data-array @error('province') is-invalid @enderror" required>
                                        </select>
                                        @error('province')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">City / Regencies</label>
                                        <select id="select2-kabupaten" name="city" class="form-control select2-data-array @error('city') is-invalid @enderror" required>
                                        </select>
                                        @error('city')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">District</label>
                                        <select id="select2-kecamatan" name="district" class="form-control select2-data-array @error('district') is-invalid @enderror" required>
                                        </select>
                                        @error('district')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Village</label>
                                        <select id="select2-kelurahan" name="village" class="form-control select2-data-array @error('village') is-invalid @enderror" required>
                                        </select>
                                        @error('village')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="">Description</label>
                                        <textarea  name="description" type="text" value="{{ old('description') }}"
                                            class="form-control @error('description') is-invalid @enderror"
                                            placeholder="Description"></textarea>
                                        @error('description')
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
        var urlProvinsi = "{{ asset('regions/provinsi.json')}}";
        var urlKabupaten = "{{ url('get-regions/kabupaten')}}/";
        var urlKecamatan = "{{ url('get-regions/kecamatan')}}/";
        var urlKelurahan = "{{ url('get-regions/kelurahan')}}/";

        function clearOptions(id) {
            console.log("on clearOptions :" + id);

            //$('#' + id).val(null);
            $('#' + id).empty().trigger('change');
        }

        console.log('Load Provinsi...');
        $.getJSON(urlProvinsi, function (res) {

            res = $.map(res, function (obj) {
                obj.text = obj.nama
                return obj;
            });

            data = [{
                id: "",
                nama: "- Pilih Provinsi -",
                text: "- Pilih Provinsi -"
            }].concat(res);

            //implemen data ke select provinsi
            $("#select2-provinsi").select2({
                dropdownAutoWidth: true,
                width: '100%',
                data: data
            })
        });

        var selectProv = $('#select2-provinsi');
        $(selectProv).change(function () {
            var value = $(selectProv).val();
            clearOptions('select2-kabupaten');

            if (value) {
                console.log("on change selectProv");

                var text = $('#select2-provinsi :selected').text();
                console.log("value = " + value + " / " + "text = " + text);

                console.log('Load Kabupaten di '+text+'...')
                $.getJSON(urlKabupaten + value + ".json", function(res) {

                    res = $.map(res, function (obj) {
                        obj.text = obj.nama
                        return obj;
                    });

                    data = [{
                        id: "",
                        nama: "- Pilih Kabupaten -",
                        text: "- Pilih Kabupaten -"
                    }].concat(res);

                    //implemen data ke select provinsi
                    $("#select2-kabupaten").select2({
                        dropdownAutoWidth: true,
                        width: '100%',
                        data: data
                    })
                })
            }
        });

        var selectKab = $('#select2-kabupaten');
        $(selectKab).change(function () {
            var value = $(selectKab).val();
            clearOptions('select2-kecamatan');

            if (value) {
                console.log("on change selectKab");

                var text = $('#select2-kabupaten :selected').text();
                console.log("value = " + value + " / " + "text = " + text);

                console.log('Load Kecamatan di '+text+'...')
                $.getJSON(urlKecamatan + value + ".json", function(res) {

                    res = $.map(res, function (obj) {
                        obj.text = obj.nama
                        return obj;
                    });

                    data = [{
                        id: "",
                        nama: "- Pilih Kecamatan -",
                        text: "- Pilih Kecamatan -"
                    }].concat(res);

                    //implemen data ke select provinsi
                    $("#select2-kecamatan").select2({
                        dropdownAutoWidth: true,
                        width: '100%',
                        data: data
                    })
                })
            }
        });

        var selectKec = $('#select2-kecamatan');
        $(selectKec).change(function () {
            var value = $(selectKec).val();
            clearOptions('select2-kelurahan');

            if (value) {
                console.log("on change selectKec");

                var text = $('#select2-kecamatan :selected').text();
                console.log("value = " + value + " / " + "text = " + text);

                console.log('Load Kelurahan di '+text+'...')
                $.getJSON(urlKelurahan + value + ".json", function(res) {

                    res = $.map(res, function (obj) {
                        obj.text = obj.nama
                        return obj;
                    });

                    data = [{
                        id: "",
                        nama: "- Pilih Kelurahan -",
                        text: "- Pilih Kelurahan -"
                    }].concat(res);

                    //implemen data ke select provinsi
                    $("#select2-kelurahan").select2({
                        dropdownAutoWidth: true,
                        width: '100%',
                        data: data
                    })
                })
            }
        });

        var selectKel = $('#select2-kelurahan');
        $(selectKel).change(function () {
            var value = $(selectKel).val();

            if (value) {
                console.log("on change selectKel");

                var text = $('#select2-kelurahan :selected').text();
                console.log("value = " + value + " / " + "text = " + text);
            }
        });
    </script>
@endpush
