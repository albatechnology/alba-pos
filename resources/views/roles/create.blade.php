@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('roles.index') }}" class="btn btn-success" title="Back"><i
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
                            <form method="post" action="{{ route('roles.store') }}" class="form-loading">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label
                                            class="{{ auth()->user()->is_super_admin ? '' : 'required' }}">Company</label>
                                        <select name="company_id" id="company_id"
                                            class="form-control select2 @error('company_id') is-invalid @enderror"
                                            {{ auth()->user()->is_super_admin ? '' : 'required' }}>
                                            @foreach ($companies as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                        @error('company_id')
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
                                    {{-- <div class="form-group @error('permissions') has-error @enderror">
                                        <label>Permissions</label>
                                        <div class="mb-1">
                                            <button type="button" class="btn btn-success btn-xs" id="btnSelectAll">Select All</button>
                                            <button type="button" class="btn btn-success btn-xs" id="btnDeselectAll">Deselect All</button>
                                        </div>
                                        <select name="permissions[]" class="form-control select2" multiple>
                                            @foreach ($permissions as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                        @error('permissions')
                                        <span class="form-text m-b-none text-danger">{{ $message }}</span>
                                        @enderror
                                    </div> --}}

                                    <div class="form-group">
                                        <label class="required">Permissions</label>
                                        <div class="row">
                                            @foreach ($permissions as $permission1)
                                                <div class="col-md-4">
                                                    @if ($permission1->childs->count() > 0)
                                                        <ul style="list-style-type: none; padding: 0;">
                                                            <li>
                                                                <div class="custom-control custom-checkbox">
                                                                    <input name="permissions[]" class="custom-control-input"
                                                                        type="checkbox"
                                                                        id="permission-{{ $permission1->id }}"
                                                                        value="{{ $permission1->id }}">
                                                                    <label for="permission-{{ $permission1->id }}"
                                                                        class="custom-control-label">{{ str_replace("_", " ", $permission1->name) }}</label>
                                                                </div>
                                                                <ul style="list-style-type: none;">
                                                                    @foreach ($permission1->childs as $permission2)
                                                                        <li>
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input name="permissions[]"
                                                                                    class="custom-control-input"
                                                                                    type="checkbox"
                                                                                    id="permission-{{ $permission2->id }}"
                                                                                    value="{{ $permission2->id }}">
                                                                                <label
                                                                                    for="permission-{{ $permission2->id }}"
                                                                                    class="custom-control-label">{{ str_replace("_", " ", $permission2->name) }}</label>
                                                                            </div>

                                                                            @if ($permission2->childs->count() > 0)
                                                                                <ul style="list-style-type: none;">
                                                                                    @foreach ($permission2->childs as $permission3)
                                                                                        <li>
                                                                                            <div
                                                                                                class="custom-control custom-checkbox">
                                                                                                <input name="permissions[]"
                                                                                                    class="custom-control-input"
                                                                                                    type="checkbox"
                                                                                                    id="permission-{{ $permission3->id }}"
                                                                                                    value="{{ $permission3->id }}">
                                                                                                <label
                                                                                                    for="permission-{{ $permission3->id }}"
                                                                                                    class="custom-control-label">{{ str_replace("_", " ", $permission3->name) }}</label>
                                                                                            </div>
                                                                                        </li>
                                                                                    @endforeach
                                                                                </ul>
                                                                            @endif
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                    @else
                                                        <ul style="list-style-type: none; padding: 0;">
                                                            <li>
                                                                <div class="custom-control custom-checkbox">
                                                                    <input name="permissions[]" class="custom-control-input"
                                                                        type="checkbox"
                                                                        id="permission-{{ $permission1->id }}"
                                                                        value="{{ $permission1->id }}">
                                                                    <label for="permission-{{ $permission1->id }}"
                                                                        class="custom-control-label">{{ str_replace("_", " ", $permission1->name) }}</label>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                        @error('permissions')
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
    <script>
        $(document).ready(function() {
            $('#btnSelectAll').on('click', function() {
                $(this).parent().next().children().prop("selected", true).trigger("change");
            });
            $('#btnDeselectAll').on('click', function() {
                $(this).parent().next().children().prop("selected", false).trigger("change");
            });
        });
    </script>
@endpush
