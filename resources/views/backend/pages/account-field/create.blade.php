
@extends('backend.layouts.master')

@section('title')
Account Field Create - Admin Panel
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
                {{-- <h4 class="page-title pull-left d-none">Account Field Create</h4> --}}
                <ul class="breadcrumbs pull-left m-2">
                    <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.account-field.index') }}">All Fields</a></li>
                    <li><span>Create Account Field</span></li>
                </ul>
            </div>
        </div>
        <div class="col-sm-2">
            <p class="float-end">
                @if ( fetchSinglePermission( $auth, 'account-field', 'view') )
                    <a href="{{ route('admin.account-field.index') }}" class="btn btn-danger">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                @else
                    <a onclick="unAuthoriseAccess('Sorry !! You are Unauthorized to view Accounting data !')" class="btn btn-danger">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                @endif
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
        <div class="col-12 mt-2">
            <div class="row">
                <div class="col-8">
                    <h3 class="mt-2 mb-2">Create Account Field</h3>
                </div>
                <div class="col-4 text-end mb-2">
                    {{-- @if ( fetchSinglePermission( $auth, 'account-field', 'add') )
                        <button type="button" class="btn btn-success pr-4 pl-4" onclick="$('#submitForm').click();">
                            <i class="fa fa-save"></i> Save
                        </button>
                    @endif --}}
                </div>
            </div>
            <div class="card">
                <div class="card-body">

                    <form action="{{ route('admin.account-field.store') }}" onsubmit="return onSubmitValidateForm();" method="POST" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 offset-3">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="name">Name<span class="text-error">*</span></label>
                                            <input type="text" data-required="yes" class="form-control" id="name" name="name" placeholder="Field Name" autofocus>
                                        </div>
                                        @error('name')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="type">Type<span class="text-error">*</span></label>
                                            <select class="form-control" id="type" name="type">
                                                <option value="textbox">Text Box</option>
                                                <option value="dropdown">Dropdown</option>
                                                <option value="document">Document</option>
                                                <option value="date">Date</option>
                                                <option value="textarea">Text Area</option>
                                                <option value="number">Number</option>
                                                <option value="float">Float</option>
                                                <option value="selection">Selection (Yes/No)</option>
                                            </select>
                                        </div>
                                        @error('type')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="required">Required</label>
                                            <select class="form-control" id="required" name="required">
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                        </div>
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
                                @if ( fetchSinglePermission( $auth, 'account-field', 'add') )
                                    <button type="submit" class="btn btn-success pr-4 pl-4" id="submitForm">
                                        <i class="fa fa-save"></i> Save
                                    </button>
                                @endif
                                <a href="{{ route('admin.account-field.index') }}" class="btn btn-danger pr-4 pl-4">
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
