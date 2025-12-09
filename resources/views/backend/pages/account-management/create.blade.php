
@extends('backend.layouts.master')

@section('title')
Create {{$company->name}} Client Company Information - Admin Panel
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
                <h4 class="page-title pull-left d-none">Client Company Create</h4>
                <ul class="breadcrumbs pull-left m-2">
                    <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.company.index') }}">All Company</a></li>
                    <li><span>Create {{$company->name}} Account</span></li>
                </ul>
            </div>
        </div>
        <div class="col-sm-2">
            <p class="float-end">
                <a href="{{ route('company-account-management-index', $company->id) }}" class="btn btn-danger">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </p>
        </div>
        <div class="col-sm-1">
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
            <h3 class="pb-3">Create '{{$company->name}}' Client Account Information</h3>
            <div class="card">
                <div class="card-body">

                    <form action="{{ route('admin.account-management.store') }}" onsubmit="return onSubmitValidateForm();" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="company_id" value="{{$company->id}}">

                        <div class="row">
                            <div class="col-md-6 offset-3">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="name">Client Company Name<span class="text-error">*</span></label>
                                            <input type="text" data-required="yes" class="form-control" id="name" name="name" placeholder="Client Company Name" autofocus value="{{old('name')}}">
                                        </div>
                                        @error('name')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-2 ">
                                        <div class="form-group">
                                            <label class="mb-0" for="code">Client Company Code<span class="text-error">*</span></label>
                                            <input type="text" data-required="yes" class="form-control" id="code" name="code" placeholder="Client Company Code" value="{{old('code')}}">
                                        </div>
                                        @error('code')
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
                                @if ( fetchSinglePermission( $auth, 'account-management', 'add') )
                                    <button type="submit" class="btn btn-success pr-4 pl-4" id="submitForm">
                                        <i class="fa fa-save"></i> Save
                                    </button>
                                @endif
                                <a href="{{ route('company-account-management-index', $company->id) }}" class="btn btn-danger pr-4 pl-4">
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
