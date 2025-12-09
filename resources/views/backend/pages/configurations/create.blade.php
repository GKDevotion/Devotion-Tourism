
@extends('backend.layouts.master')

@section('title')
Configuration Create - Admin Panel
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
                {{-- <h4 class="page-title pull-left d-none">Configuration Create</h4> --}}
                <ul class="breadcrumbs pull-left m-2">
                    <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.configurations.index') }}">All Configuration</a></li>
                    <li><span>Create Configuration</span></li>
                </ul>
            </div>
        </div>
        <div class="col-md-2">
            <p class="float-end">
                <button type="button" class="btn btn-success pr-4 pl-4" onclick="$('#submitForm').click();">
                    <i class="fa fa-save"></i> Save
                </button>
                <a href="{{ route('admin.configurations.index') }}" class="btn btn-danger">
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
            <h3 class="pb-3">Create Configuration</h3>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.configurations.store') }}" onsubmit="return onSubmitValidateForm();" method="POST" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <div class="form-group">
                                    <label class="mb-0" for="display_name">Configuration Name<span class="text-error">*</span></label>
                                    <input type="text" data-required="yes" class="form-control" id="display_name" name="display_name" placeholder="Configuration Name">
                                </div>
                                @error('display_name')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-2">
                                <div class="form-group">
                                    <label class="mb-0" for="key">Configuration Key<span class="text-error">*</span></label>
                                    <input type="text" data-required="yes" class="form-control" id="key" name="key" placeholder="Configuration Key">
                                </div>
                                @error('key')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-2">
                                <div class="form-group">
                                    <label class="mb-0" for="value">Configuration Value<span class="text-error">*</span></label>
                                    <input type="text" data-required="yes" class="form-control" id="value" name="value" placeholder="Configuration Value">
                                </div>
                                @error('value')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-success pr-4 pl-4" id="submitForm">
                                    <i class="fa fa-save"></i> Save
                                </button>
                                <a href="{{ route('admin.configurations.index') }}" class="btn btn-danger pr-4 pl-4">
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
