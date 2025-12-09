
@extends('backend.layouts.master')

@section('title')
Country Create - Admin Panel
@endsection

@section('styles')
<style>
    .form-check-label {
        text-transform: capitalize;
    }
</style>
@endsection


@section('admin-content')

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-2">
            @include('backend.layouts.partials.side-bar-logo')
        </div>
        <div class="col-sm-7">
            <div class="breadcrumbs-area clearfix">
                {{-- <h4 class="page-title pull-left d-none">Country Create</h4> --}}
                <ul class="breadcrumbs pull-left m-2">
                    <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.country.index') }}">All Country</a></li>
                    <li><span>Create Country</span></li>
                </ul>
            </div>
        </div>
        <div class="col-md-2">
            <p class="float-end">
                @if (Auth::guard('admin')->user()->can('country.create'))
                    <button type="button" class="btn btn-success pr-4 pl-4" onclick="$('#submitForm').click();">
                        <i class="fa fa-save"></i> Save
                    </button>
                @endif
                <a href="{{ route('admin.country.index') }}" class="btn btn-danger">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </p>
        </div>
        <div class="col-md-1">
            <span class="text-theme">
                <i class="fa fa-user"></i>
                {{auth()->guard('admin')->user()->username}}
            </span>
        </div>
        @include('backend.layouts.partials.header-menu')
    </div>
</div>
<!-- page title area end -->

<div class="main-content-inner">
    <div class="row">
        <!-- data table start -->
        <div class="col-12 mt-3">
            <h3 class="pb-3">Create Country</h3>
            <div class="card">
                <div class="card-body">

                    <form action="{{ route('admin.country.store') }}" onsubmit="return onSubmitValidateForm();" method="POST" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-md-8 offset-2">
                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="continent_id">Continent<span class="text-error">*</span></label>
                                            <select class="form-control" data-required="yes" id="continent_id" name="continent_id" autofocus>
                                                <option value="" >Select Continent</option>
                                                @foreach( $continentArr as $data )
                                                    <option value="{{$data->id}}">{{$data->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('continent_id')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="name">Name<span class="text-error">*</span></label>
                                            <input type="text" data-required="yes" data-required="yes" class="form-control" id="name" name="name" placeholder="Name">
                                        </div>
                                        @error('name')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="numeric_code">Numeric Code<span class="text-error">*</span></label>
                                            <input type="text" data-required="yes" class="form-control" id="numeric_code" name="numeric_code" placeholder="Numeric Code">
                                        </div>
                                        @error('numeric_code')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="iso3">ISO 3<span class="text-error">*</span></label>
                                            <input type="text" data-required="yes" class="form-control" id="iso3" name="iso3" placeholder="ISO 3">
                                        </div>
                                        @error('iso3')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="iso2">ISO 2<span class="text-error">*</span></label>
                                            <input type="text" data-required="yes" class="form-control" id="iso2" name="iso2" placeholder="ISO 2">
                                        </div>
                                        @error('iso2')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="phone_code">Phone Code<span class="text-error">*</span></label>
                                            <input type="text" data-required="yes" class="form-control" id="phone_code" name="phone_code" placeholder="Phone Code">
                                        </div>
                                        @error('phone_code')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="currency">Currency<span class="text-error">*</span></label>
                                            <input type="text" data-required="yes" class="form-control" id="currency" name="currency" placeholder="Currency">
                                        </div>
                                        @error('currency')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="currency_name">Currency Name<span class="text-error">*</span></label>
                                            <input type="text" data-required="yes" class="form-control" id="currency_name" name="currency_name" placeholder="Currency Name">
                                        </div>
                                        @error('currency_name')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="currency_symbol">Currency Symbol<span class="text-error">*</span></label>
                                            <input type="text" data-required="yes" class="form-control" id="currency_symbol" name="currency_symbol" placeholder="Currency Symbol">
                                        </div>
                                        @error('currency_symbol')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="tld">TLD<span class="text-error">*</span></label>
                                            <input type="text" data-required="yes" class="form-control" id="tld" name="tld" placeholder="TLD">
                                        </div>
                                        @error('tld')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="latitude">Latitude<span class="text-error">*</span></label>
                                            <input type="text"  class="form-control" id="latitude" name="latitude" placeholder="Latitude">
                                        </div>
                                        @error('latitude')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="longitude">Longitude<span class="text-error">*</span></label>
                                            <input type="text" data-required="yes" class="form-control" id="longitude" name="longitude" placeholder="Longitude">
                                        </div>
                                        @error('longitude')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="capital">Capital</label>
                                            <input type="text" class="form-control" id="capital" name="capital" placeholder="Capital">
                                        </div>
                                        @error('capital')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="status">Status</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="1">Active</option>
                                                <option value="0">De Active</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-success pr-4 pl-4">
                                    <i class="fa fa-save"></i> Save
                                </button>
                                <a href="{{ route('admin.country.index') }}" class="btn btn-danger pr-4 pl-4">
                                    <i class="fa fa-arrow-left"></i> Back
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- data table end -->

    </div>
</div>
@endsection
