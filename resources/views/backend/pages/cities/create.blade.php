
@extends('backend.layouts.master')

@section('title')
City Create - Admin Panel
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
        <div class="col-md-7">
            <div class="breadcrumbs-area clearfix">
                {{-- <h4 class="page-title pull-left d-none">City Create</h4> --}}
                <ul class="breadcrumbs pull-left m-2">
                    <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.city.index') }}">All City</a></li>
                    <li><span>Create City</span></li>
                </ul>
            </div>
        </div>
        <div class="col-md-2">
            <p class="float-end">
                @if (Auth::guard('admin')->user()->can('city.create'))
                    <button type="button" class="btn btn-success pr-4 pl-4" onclick="$('#submitForm').click();">
                        <i class="fa fa-save"></i> Save
                    </button>
                @endif
                <a href="{{ route('admin.city.index') }}" class="btn btn-danger">
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
            <h3 class="pb-3">Create Cities</h3>
            <div class="card">
                <div class="card-body">

                    <form action="{{ route('admin.city.store') }}" onsubmit="return onSubmitValidateForm();" method="POST" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-md-10 offset-1">
                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="continent_id">Continent<span class="text-error">*</span></label>
                                            <select class="form-control continent_id" data-required="yes" id="continent_id" name="continent_id" autofocus>
                                                <option value="" >Select Continent</option>
                                                @foreach( $continentArr as $cr )
                                                    <option value="{{$cr->id}}">{{$cr->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('continent_id')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="country_id">Country<span class="text-error">*</span></label>
                                            <select class="form-control country_id" data-required="yes" id="country_id" name="country_id">
                                                <option value="" >Select Country</option>
                                                @foreach( $countryArr as $cr )
                                                    <option class="continent-id continent_id_{{$cr->continent_id}} d-none" value="{{$cr->id}}">{{$cr->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('country_id')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="state_id">State<span class="text-error">*</span></label>
                                            <select class="form-control state_id" data-required="yes" id="state_id" name="state_id">
                                                <option value="" >Select State</option>
                                                @foreach( $stateArr as $sr )
                                                    <option class="state-id continent_id_{{$sr->continent_id}} state_id_{{$sr->country_id}} d-none" value="{{$sr->id}}">{{$sr->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('state_id')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="name">Name<span class="text-error">*</span></label>
                                            <input type="text" class="form-control" data-required="yes" id="name" name="name" placeholder="Name">
                                        </div>
                                        @error('name')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="latitude">Latitude</label>
                                            <input type="text" class="form-control" id="latitude" name="latitude" placeholder="Latitude">
                                        </div>
                                        @error('latitude')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="longitude">Longitude</label>
                                            <input type="text" class="form-control" id="longitude" name="longitude" placeholder="Longitude">
                                        </div>
                                        @error('longitude')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
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
                                <button type="submit" class="btn btn-success pr-4 pl-4" id="submitForm">
                                    <i class="fa fa-save"></i> Save
                                </button>
                                <a href="{{ route('admin.city.index') }}" class="btn btn-danger pr-4 pl-4">
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

        $('#country_id').on("change", function(){
            $(".country-id").addClass('d-none')
            $(".country_id_"+$(this).val()).removeClass('d-none')
        });
    })
</script>
@endsection
