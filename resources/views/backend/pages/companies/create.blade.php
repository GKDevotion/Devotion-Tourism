
@extends('backend.layouts.master')

@section('title')
Company Info - Admin Panel
@endsection

@section('styles')
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
                {{-- <h4 class="page-title pull-left d-none">Company Create</h4> --}}
                <ul class="breadcrumbs pull-left m-2">
                    <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.company.index') }}">All Companies</a></li>
                    <li><span>Create Company Information</span></li>
                </ul>
            </div>
        </div>
        <div class="col-md-2">
            <p class="float-end">
                @if ( false && fetchSinglePermission( $auth, 'admin.company', 'add') )
                    <button type="button" class="btn btn-success pr-4 pl-4" onclick="$('#submitForm').click();">
                        <i class="fa fa-save"></i> Save
                    </button>
                @endif
                <a href="{{ route('admin.company.index') }}" class="btn btn-danger">
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
        <div class="col-12 mt-2">
            <div class="row">
                <div class="col-8">
                    <h3 class="mt-2">Company Information</h3>
                </div>
                <div class="col-4 mb-2 text-end">
                    @if ( fetchSinglePermission( $auth, 'admin.company', 'add') )
                        <button type="button" class="btn btn-success pr-4 pl-4" onclick="$('#submitForm').click();">
                            <i class="fa fa-save"></i> Save
                        </button>
                    @endif
                </div>
            </div>
            <div class="card">
                <div class="card-body">

                    <form action="{{ route('admin.company.store') }}" onsubmit="return onSubmitValidateForm();" method="POST" autocomplete="off" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="col-md-12">
                                    <label class="mb-0" for="logo">Logo</label>
                                    <input type="file" class="company-logo" id="logo" name="logo" accept="image/jpg, image/jpeg, image/png">
                                    @if($errors->has('logo'))
                                        <div class="error">{{ $message }}</div>
                                    @endif
                                </div>
                                <div class="col-md-12">
                                    <label class="mb-0" for="favicon">Favicon</label>
                                    <input type="file" class="favicon" id="favicon" name="favicon" accept="image/jpg, image/jpeg, image/png">
                                    @if($errors->has('favicon'))
                                        <div class="error">{{ $message }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="name">Company Name<span class="text-error">*</span></label>
                                            <input type="text" data-required="yes" class="form-control" id="name" name="name" placeholder="Company Name" autofocus value="{{old('name')}}">
                                        </div>
                                        @error('name')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="website_link">Website Link<span class="text-error">*</span></label>
                                            <input type="text" data-required="yes" class="form-control website-link-validation" id="website_link" name="website_link" placeholder="https://www.***.***" value="{{old('website_link')}}" >
                                        </div>
                                        @error('website_link')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="parent_id">Parent Company</label>
                                            <select class="form-control" id="parent_id" name="parent_id">
                                                <option value="0">Select Parent Company</option>
                                                @foreach( $companies as $ar )
                                                    <option value="{{$ar->id}}" {{old('parent_id') == $ar->id ? 'selected' : ''}}>{{$ar->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('parent_id')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="industry_id">Industry<span class="text-error">*</span></label>
                                            <select class="form-control" data-required="yes" id="industry_id" name="industry_id">
                                                <option value="">Select Industry</option>
                                                @foreach( $industries as $ar )
                                                    <option value="{{$ar->id}}" {{old('industry_id') == $ar->id ? 'selected' : ''}}>{{$ar->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('industry_id')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="currency_id">Currency<span class="text-error">*</span></label>
                                            <select class="form-control select2" multiple data-required="yes" id="currency_id" name="currency_id[]">
                                                <option value="" disabled>Select Currency</option>
                                                @foreach( $currency as $ar )
                                                    <option value="{{$ar->id}}">{{$ar->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('currency_id')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="admin_id">User<span class="text-error">*</span></label>
                                            <select class="form-control select2" multiple data-required="yes" id="admin_id" name="admin_id[]">
                                                <option value="" disabled>Select User</option>
                                                @foreach( $adminArr as $ar )
                                                    <option value="{{$ar->id}}">{{$ar->username}} ({{$ar->acc_no}})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('admin_id')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="sort_name">Short Name<span class="text-error">*</span></label>
                                            <input type="text" class="form-control" data-required="yes" id="sort_name" name="sort_name" placeholder="Sort Name" value="{{old('sort_name')}}">
                                        </div>
                                        @error('sort_name')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="email_id">Email ID<span class="text-error">*</span></label>
                                            <input type="email" class="form-control" data-required="yes" id="email_id" name="email_id" placeholder="Email ID" value="{{old('email_id')}}">
                                        </div>
                                        @error('email_id')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="contact_number">Contact Number<span class="text-error d-none">*</span></label>
                                            <input class="form-control" id="contact_number" name="contact_number" placeholder="+971 000 0000" value="{{old('contact_number')}}">
                                        </div>
                                        @error('contact_number')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-2 d-none">
                                        <div class="form-group">
                                            <label class="mb-0" for="sort_order">Sort Order</label>
                                            <input type="number" class="form-control allow-only-number" id="sort_order" name="sort_order" placeholder="Sort Order" value="{{old('sort_order')}}">
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="status">Status</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="1" {{old('status') == 1 ? 'selected' : ''}}>Active</option>
                                                <option value="0" {{old('status') == 0 ? 'selected' : ''}}>De Active</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-md-12 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="address">Address</label>
                                            <textarea class="form-control" id="address" name="address" placeholder="Full Address">{{old('address')}}</textarea>
                                            @error('address')
                                                <div class="error text-error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12 text-center">
                                @if ( fetchSinglePermission( $auth, 'admin.company', 'add') )
                                    <button type="submit" class="btn btn-success pr-4 pl-4">
                                        <i class="fa fa-save"></i> Save
                                    </button>
                                @endif
                                <a href="{{ route('admin.company.index') }}" class="btn btn-danger pr-4 pl-4">
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).on( "ready", function() {
        const dropifyClass = ["company-logo", "favicon"];
        $( dropifyClass ).each(function( index, className ) {

            if( $('.'+className).length > 0){
                $('.'+className).dropify();
            }
        });

        $('.select2').select2();
    });
</script>
@endsection
