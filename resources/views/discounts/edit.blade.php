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
                            <form method="post" action="{{ route('discounts.update', $discounts->id) }}"
                                enctype="multipart/form-data" class="form-loading">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="company_id" value="{{ $discounts->company_id }}">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="required">Company</label>
                                        <input type="text" value="{{ $discounts->company->name }}"
                                            class="form-control @error('company_id') is-invalid @enderror" readonly>
                                        @error('company_id')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Name</label>
                                        <input name="name" type="text" value="{{ $discounts->name }}"
                                            class="form-control @error('name') is-invalid @enderror" placeholder="Name"
                                            required>
                                        @error('name')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="">Description</label>
                                        <textarea name="description" type="text"
                                            class="form-control @error('description') is-invalid @enderror" placeholder="Description">{{ $discounts->description }}</textarea>
                                        @error('description')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Type</label>
                                        <select class="form-control" name="type"
                                            class="form-control select2 @error('type') is-invalid @enderror" required>
                                            <option selected value="{{ $discounts->type }}">
                                                {{ $discounts->type == 0 ? 'Nominal' : 'Percentage' }}</option>
                                            <option value="0">Nominal</option>
                                            <option value="1">Percentage</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Value</label>
                                        <input name="value" type="number" value="{{ $discounts->value }}"
                                            class="form-control @error('value') is-invalid @enderror" placeholder="Value"
                                            required>
                                        @error('value')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group form-check form-check-inline">
                                        <input name="is_active" type="checkbox"
                                            {{ $discounts->type == 1 ? 'checked="checked"' : '' }} value="1"
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
                                            <input name=date onfocus="blur()" type="text"
                                                class="form-control float-right" id="reservationtime"
                                                value="{{ $discounts->start_date . ' - ' . $discounts->end_date }}">
                                        </div>
                                        @error('date')
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
