@extends('layouts.app')
@push('css')
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
@endpush
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('discounts.index') }}" class="btn btn-success" title="Back"><i
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
                            <form method="post" action="{{ route('discounts.store') }}" enctype="multipart/form-data"
                                class="form-loading">
                                @csrf
                                <div class="card-body">
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
                                        <label class="">Description</label>
                                        <textarea  name="description" type="text" value="{{ old('description') }}"
                                            class="form-control @error('description') is-invalid @enderror"
                                            placeholder="Description"></textarea>
                                        @error('description')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Type</label>
                                        <select class="form-control" name="type"
                                            class="form-control select2 @error('type') is-invalid @enderror" required>
                                            <option selected>Type</option>
                                            <option value="0">Nominal</option>
                                            <option value="1">Percentage</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Value</label>
                                        <input name="value" type="number" value="{{ old('value') }}"
                                            class="form-control @error('value') is-invalid @enderror" placeholder="Value"
                                            required>
                                        @error('value')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group form-check form-check-inline">
                                        <input name="is_active" type="checkbox" value="1"
                                            class="form-check-input @error('is_active') is-invalid @enderror"
                                            placeholder="Is_active">
                                        @error('is_active')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                        <label class="required form-check-label font-weight-bold">Is Active</label>
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Start Date & End Date</label>
                                        <div class="input-group pb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="far fa-clock"></i></span>
                                            </div>
                                            <input name=date onfocus="blur()" type="text" class="form-control float-right" id="reservationtime">
                                        </div>
                                        @error('date')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Companies</label>
                                        <div class="mb-1">
                                            <button type="button" class="btn btn-success btn-xs btnSelectAll">Select
                                                All</button>
                                            <button type="button" class="btn btn-success btn-xs btnDeselectAll">Deselect
                                                All</button>
                                        </div>
                                        <select name="company_ids[]" id="company_ids"
                                            class="form-control select2 @error('company_ids') is-invalid @enderror" multiple
                                            required>
                                            @foreach ($companies as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ in_array($id, old('company_ids', [])) ? 'selected' : '' }}>
                                                    {{ $name }}</option>
                                            @endforeach
                                        </select>
                                        @error('company_ids')
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
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>

    <script>
        $('#reservation').daterangepicker({
            locale: {
                format: 'YYYY/MM/DD'
            }
        })

        $('#reservation').on('apply.daterangepicker', function(ev, picker) {
            var startDate = picker.startDate.format('YYYY-MM-DD');
            var endDate = picker.endDate.format('YYYY-MM-DD');
            console.log(picker.startDate.format('YYYY-MM-DD'));
            console.log(picker.endDate.format('YYYY-MM-DD'));
        });

        $('#reservationtime').daterangepicker({
            timePicker: true,
            timePicker24Hour: true,
            timePickerIncrement: 10,
            locale: {
                format: 'YYYY-MM-DD hh:mm:ss'
            }
        })
        $(document).ready(function() {
            $('.btnSelectAll').on('click', function() {
                $(this).parent().next().children().prop("selected", true).trigger("change");
            });
            $('.btnDeselectAll').on('click', function() {
                $(this).parent().next().children().prop("selected", false).trigger("change");
            });
        });
    </script>
@endpush
