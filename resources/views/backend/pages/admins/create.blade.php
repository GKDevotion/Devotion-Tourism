
@extends('backend.layouts.master')

@section('title')
User Create - Admin Panel
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

<style>
    .form-check-label {
        text-transform: capitalize;
    }

    .select2-container--default .select2-selection--single {
        border: 1px solid rgba(170, 170, 170, .3); /* Green border */
        height: 40px;              /* Custom height */
        border-radius: var(--bs-border-radius);        /* Optional: rounded corners */
    }

    /* Adjust the vertical alignment of the selected text */
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 40px;
    }

    .select2-selection{
        height: 45px;
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
                {{-- <h4 class="page-title pull-left d-none">Admin Create</h4> --}}
                <ul class="breadcrumbs pull-left m-2">
                    <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.admin.index') }}">All Users</a></li>
                    <li><span>Create User</span></li>
                </ul>
            </div>
        </div>
        <div class="col-md-2">
            <p class="float-end">
                @if ( Auth::guard('admin')->user()->can('admin.create'))
                    <button type="button" class="btn btn-success pr-4 pl-4" onclick="$('#submitForm').click();">
                        <i class="fa fa-save"></i> Save
                    </button>
                @endif
                <a href="{{ route('admin.admin.index') }}" class="btn btn-danger">
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

<div class="main-content-inner pb-2">
    <div class="row">
        <!-- data table start -->
        <div class="col-12 mt-3">
            <h3 class="pb-3">Create User</h3>
            <div class="card">
                <div class="card-body">

                    <!-- @include('backend.layouts.partials.messages') -->

                    <form action="{{ route('admin.admin.store') }}" onsubmit="return onSubmitValidateForm();" method="POST" autocomplete="off">
                        @csrf
                        <span class="get-continent-list-url d-none">{{url('api/get-continent-list')}}</span>
                        <span class="get-country-list-url d-none">{{url('api/get-country-list')}}</span>
                        <span class="get-state-list-url d-none">{{url('api/get-state-list')}}</span>
                        <span class="get-city-list-url d-none">{{url('api/get-city-list')}}</span>

                        <div class="row">

                            <div class="col-md-4 col-sm-12 mt-2">
                                <label class="mb-0" for="first_name">First Name<span class="text-error">*</span></label>
                                <input type="text" data-required="yes" class="form-control" id="first_name" name="first_name" placeholder="Enter First Name" value="{{old( 'first_name' )}}">
                                @error('first_name')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 col-sm-12 mt-2">
                                <label class="mb-0" for="middle_name">Middle Name<span class="text-error">*</span></label>
                                <input type="text" data-required="yes" class="form-control" id="middle_name" name="middle_name" placeholder="Enter Middle Name"  value="{{old( 'middle_name' )}}">
                                @error('middle_name')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 col-sm-12 mt-2">
                                <label class="mb-0" for="last_name">Last Name<span class="text-error">*</span></label>
                                <input type="text" data-required="yes" class="form-control" id="last_name" name="last_name" placeholder="Enter Last Name"  value="{{old( 'last_name' )}}">
                                @error('last_name')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 col-sm-12 mt-2">
                                <label class="mb-0" for="username">Username<span class="text-error">*</span></label>
                                <input type="text" data-required="yes" class="form-control" id="username" name="username" placeholder="Enter Username"  value="{{old( 'user_name' )}}">
                                @error('username')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 col-sm-12 mt-2">
                                <label class="mb-0" for="email">Email ID<span class="text-error">*</span></label>
                                <input type="text" data-required="yes" class="form-control" id="email" name="email" placeholder="Enter Email Address"  value="{{old( 'email' )}}">
                                @error('email')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 col-sm-12 mt-2">
                                <label class="mb-0" for="mobile_number">Mobile Number<span class="text-error">*</span></label>
                                <input type="text" data-required="yes" class="form-control" id="mobile_number" name="mobile_number" placeholder="Enter Mobile Number"  value="{{old( 'mobile_number' )}}">
                                @error('mobile_number')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mt-2 col-sm-12">
                                <label class="mb-0" for="address">Address<span class="text-error">*</span></label>
                                <textarea data-required="yes" class="form-control" id="address" name="address" placeholder="Enter Address">{{old('address')}}</textarea>
                                @error('address')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 col-sm-12 mt-2">
                                <label class="mb-0" for="continent_id">Continent<span class="text-error">*</span></label>
                                <select name="continent_id" data-required="yes" id="continent_id" class="form-control get-country-list continent-id" data-id="country_id">
                                    <option value="">Select Continent</option>
                                    @foreach ($continentArr as $ar)
                                        <option value="{{ $ar->id }}">{{ $ar->name }}</option>
                                    @endforeach
                                </select>
                                @error('continent_id')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 col-sm-12 mt-2">
                                <label class="mb-0" for="country_id">Country<span class="text-error">*</span></label>
                                <select name="country_id" data-required="yes" id="country_id" class="form-control get-state-list country-id" data-id="state_id">
                                    <option value="">Select Country</option>
                                </select>
                                @error('country_id')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 col-sm-12 mt-2">
                                <label class="mb-0" for="state_id">State<span class="text-error">*</span></label>
                                <select name="state_id" data-required="yes" id="state_id" class="form-control get-city-list state-id" data-id="city_id">
                                    <option value="">Select State</option>
                                </select>
                                @error('state_id')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 col-sm-12 mt-2">
                                <label class="mb-0" for="city_id">City<span class="text-error">*</span></label>
                                <select name="city_id" data-required="yes" id="city_id" class="form-control city-id">
                                    <option value="">Select City</option>
                                </select>
                                @error('city_id')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 col-sm-12 mt-2">
                                <label class="mb-0" for="zipcode">Zipcode<span class="text-error">*</span></label>
                                <input type="text" data-required="yes" class="form-control" id="zipcode" name="zipcode" placeholder="Enter Zipcode"  value="{{old( 'zipcode' )}}">
                                @error('zipcode')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 col-sm-12 mt-2">
                                <label class="mb-0" for="company_id">Company<span class="text-error">*</span></label>
                                <select name="company_id" data-required="yes" id="company_id" class="form-control">
                                    <option value="" >Select Company</option>
                                    @foreach ($companyArr as $cmp)
                                        <option value="{{ $cmp->id }}">{{ $cmp->name }}</option>
                                    @endforeach
                                </select>
                                @error('company_id')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 col-sm-12 mt-2">
                                <div class="form-group">
                                    <label class="mb-0" for="companyids">Assign Company</label>
                                    <select class="form-control select2" multiple data-required="yes" id="companyids" name="companyids[]">
                                        <option value="" disabled>Select Company</option>
                                        @foreach( $companyArr as $ar )
                                            <option value="{{$ar->id}}">{{$ar->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('companyids')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 col-sm-12 mt-2">
                                <label class="mb-0" for="roles">Designation<span class="text-error">*</span></label>
                                <select name="admin_user_group_id" data-required="yes" id="admin_user_group_id" class="form-control select2">
                                    <option value="">Select Group</option>
                                    @foreach ($groups as $group)
                                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                                    @endforeach
                                </select>
                                @error('roles')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 col-sm-12 mt-2">
                                <label class="mb-0" for="password">Password
                                    <span class="text-error">*</span>
                                    <i class="ti-eye" style="cursor: pointer;" onmousedown="showPassword()" onmouseup="hidePassword()" onmouseleave="hidePassword()"></i>
                                </label>
                                <input type="password" data-required="yes" class="form-control" id="password" name="password" placeholder="Enter Password">
                                @error('password')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="row mt-4">
                            <div class="text-center col-md-12">
                                <button type="submit" class="btn btn-success pr-4 pl-4" id="submitForm">
                                    <i class="fa fa-save"></i> Save
                                </button>
                                <a href="{{ route('admin.admin.index') }}" class="btn btn-danger pr-4 pl-4">
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    })

    function showPassword() {
        document.getElementById("password").type = "text";
    }

    function hidePassword() {
        document.getElementById("password").type = "password";
    }
</script>
@endsection
