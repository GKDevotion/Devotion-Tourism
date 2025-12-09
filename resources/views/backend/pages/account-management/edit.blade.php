
@extends('backend.layouts.master')

@section('title')
Create {{$data->company->name}} Client Company Information - Admin Panel
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
                <h4 class="page-title pull-left d-none">Location Edit - {{ $data->name }}</h4>
                <ul class="breadcrumbs pull-left m-2">
                <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.locations.index') }}">All Location</a></li>
                    <li><span>Update {{$data->company->name}} Account</span></li>
                </ul>
            </div>
        </div>
        <div class="col-sm-2 text-end">
            <p class="float-end">
                @if ( false && fetchSinglePermission( $auth, 'account-management', 'add') )
                    <a class="btn btn-success text-white" href="{{ route('company-account-management-create', $data->company_id) }}">
                        <i class="fa fa-plus"></i> New Account
                    </a>
                @endif
                <a href="{{ route('company-account-management-index', $data->company_id) }}" class="btn btn-danger">
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
            <h3 class="pb-3">Update '{{$data->company->name}}' Client Account Information</h3>
            <div class="card">
                <div class="card-body">

                    <form action="{{ route('admin.account-management.update', $data->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <input type="hidden" name="company_id" value="{{$data->company_id}}">
                        <input type="hidden" name="id" value="{{$data->id}}">

                        <div class="row">
                            <div class="col-md-6 offset-3">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="name">Client Company Name<span class="text-error">*</span></label>
                                            <input type="text" data-required="yes" class="form-control" id="name" name="name" placeholder="Client Company Name" autofocus value="{{old('name', $data->name)}}">
                                        </div>
                                        @error('name')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-2 ">
                                        <div class="form-group">
                                            <label class="mb-0" for="code">Client Company Code<span class="text-error">*</span></label>
                                            <input type="text" data-required="yes" class="form-control" id="code" name="code" placeholder="Client Company Code" value="{{old('code', $data->code)}}">
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
                                @if ( fetchSinglePermission( $auth, 'account-management', 'edit') )
                                    <button type="submit" class="btn btn-success pr-4 pl-4" id="submitForm">
                                        <i class="fa fa-save"></i> Update
                                    </button>
                                @endif
                                <a href="{{ route('company-account-management-index', $data->company_id) }}" class="btn btn-danger pr-4 pl-4">
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
