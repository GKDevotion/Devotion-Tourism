
@extends('backend.layouts.master')

@section('title')
State Create - Admin Panel
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
                {{-- <h4 class="page-title pull-left d-none">State Create</h4> --}}
                <ul class="breadcrumbs pull-left m-2">
                    <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.state.index') }}">All State</a></li>
                    <li><span>Create State</span></li>
                </ul>
            </div>
        </div>
        <div class="col-md-3">
            <p class="float-end">
                @if (Auth::guard('admin')->user()->can('state.create'))
                    <button type="button" class="btn btn-success pr-4 pl-4" onclick="$('#submitForm').click();">
                        <i class="fa fa-save"></i> Save
                    </button>
                @endif
                <a href="{{ route('admin.state.index') }}" class="btn btn-danger">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </p>
        </div>
        @include('backend.layouts.partials.header-menu')
    </div>
</div>
<!-- page title area end -->

<div class="main-content-inner">
    <div class="row">
        <!-- data table start -->
        <div class="col-12 mt-3">
            <h3 class="pb-3">Create State</h3>
            <div class="card">
                <div class="card-body">

                    <form action="{{ route('admin.state.store') }}" onsubmit="return onSubmitValidateForm();" method="POST" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-md-8 offset-2">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="continent_id">Continent<span class="text-error">*</span></label>
                                            <select class="form-control continent_id" data-required="yes" id="continent_id" name="continent_id" autofocus>
                                                @foreach( $continentArr as $cr )
                                                    <option value="{{$cr->id}}">{{$cr->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('continent_id')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="country_id">Country<span class="text-error">*</span></label>
                                            <select class="form-control country_id" data-required="yes" id="country_id" name="country_id">
                                                @foreach( $countryArr as $cr )
                                                    <option class="continent-id continent_id_{{$cr->continent_id}} d-none" value="{{$cr->id}}">{{$cr->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('country_id')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="name">Name<span class="text-error">*</span></label>
                                            <input type="text" class="form-control" data-required="yes" id="name" name="name" placeholder="Name">
                                        </div>
                                        @error('name')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="fips_code">FIPS Code</label>
                                            <input type="text" class="form-control" id="fips_code" name="fips_code" placeholder="FIPS Code">
                                        </div>
                                        @error('fips_code')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="iso2">ISO 2</label>
                                            <input type="text" class="form-control" id="iso2" name="iso2" placeholder="ISO 2">
                                        </div>
                                        @error('iso2')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="latitude">Latitude</label>
                                            <input type="text" class="form-control" id="latitude" name="latitude" placeholder="Latitude">
                                        </div>
                                        @error('latitude')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="longitude">Longitude</label>
                                            <input type="text" class="form-control" id="longitude" name="longitude" placeholder="Longitude">
                                        </div>
                                        @error('longitude')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-2">
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
                                <button type="submit" class="btn btn-success pr-4 pl-4" id="submitForm">
                                    <i class="fa fa-save"></i> Save
                                </button>
                                <a href="{{ route('admin.state.index') }}" class="btn btn-danger pr-4 pl-4">
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

@section('scripts')
<script>
    $(window).ready(function() {
        $('#continent_id').on("change", function(){
            $(".continent-id").addClass('d-none')
            $(".continent_id_"+$(this).val()).removeClass('d-none')
        });
    })
</script>
@endsection
