
@extends('backend.layouts.master')

@section('title')
Website Edit - Admin Panel
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropify/0.2.2/css/dropify.min.css" />
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
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                {{-- <h4 class="page-title pull-left d-none">Configuration Edit - {{ $data->name }}</h4> --}}
                <ul class="breadcrumbs pull-left m-2">
                <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.website.index') }}">All Website</a></li>
                    <li><span>Edit Configuration</span></li>
                </ul>
            </div>
        </div>
        <div class="col-md-3">
            <p class="float-end">
                <button type="button" class="btn btn-success pr-4 pl-4" onclick="$('#submitForm').click();">
                    <i class="fa fa-save"></i> Update
                </button>

                <a href="{{ route('admin.website.index') }}" class="btn btn-danger">
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
            <h3 class="pb-3">Update Website</h3>
            <div class="card">
                <div class="card-body">

                    <!-- @include('backend.layouts.partials.messages') -->

                    <form action="{{ route('admin.website.update', $dataArr->id) }}" onsubmit="return onSubmitValidateForm();" method="POST">
                        @method('PUT')
                        @csrf

                          <div class="row">
                            <div class="col-md-6">
                                <div class="card card-primary">
                                    <div class="card-header">
                                            <h3 class="card-title">Website Content</h3>
                                    </div>

                                    <div class="card-body">
                                        
                                        <div class="form-group">
                                            <label for="name">Website Name</label>
                                            <input type="text" class="form-control" id="name" name="name" placeholder="{{ __('Website Name') }}" value="{{$dataArr->name}}">
                                            @if($errors->has('name'))
                                                <div class="error">{{ $errors->first('name') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="favicon">Favicon</label>
                                            <input type="file" class="dropify" id="favicon" name="favicon" data-default-file="{{url('../storage/app/'.$dataArr->favicon)}}">
                                            @if($errors->has('favicon'))
                                                <div class="error">{{ $errors->first('favicon') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="header_logo">Header Logo</label>
                                            <input type="file" class="dropify" id="header_logo" name="header_logo" data-default-file="{{url('../storage/app/'.$dataArr->header_logo)}}">
                                            @if($errors->has('header_logo'))
                                                <div class="error">{{ $errors->first('header_logo') }}</div>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card card-primary">
                                    <div class="card-header">
                                            <h3 class="card-title">Google Meta data</h3>
                                    </div>

                                    <div class="card-body">
                                        
                                        <div class="form-group">
                                            <label for="google_analytics_code">Google Analytic Code</label>
                                            <input type="text" class="form-control" id="google_analytics_code" name="google_analytics_code" placeholder="{{ __('Google Analytic Code') }}" value="{{$dataArr->google_analytics_code}}">
                                            @if($errors->has('google_analytics_code'))
                                                <div class="error">{{ $errors->first('google_analytics_code') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="google_tag_manager_code">Google Tag Manager Code</label>
                                            <input type="text" class="form-control" id="google_tag_manager_code" name="google_tag_manager_code" placeholder="{{ __('Google Tag Manager Code') }}" value="{{$dataArr->google_tag_manager_code}}">
                                            @if($errors->has('google_tag_manager_code'))
                                                <div class="error">{{ $errors->first('google_tag_manager_code') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="is_run_advertisement">Run Advertisement</label>
                                            <select class="form-control" name="is_run_advertisement" id="is_run_advertisement">
                                                <option value="0" {{ ( $dataArr->is_run_advertisement == 0 ) ? 'selected' : '' }}>No</option>
                                                <option value="1" {{ ( $dataArr->is_run_advertisement == 1 ) ? 'selected' : '' }}>Yes</option>
                                            </select>
                                        </div>

										<div class="form-group">
                                            <label for="google_client_ca_pub_code">Google Client CA PUB Code</label>
                                            <input type="text" class="form-control" id="google_client_ca_pub_code" name="google_client_ca_pub_code" placeholder="{{ __('Google Client CA PUB Code') }}" value="{{$dataArr->google_client_ca_pub_code}}">
                                            @if($errors->has('google_client_ca_pub_code'))
                                                <div class="error">{{ $errors->first('google_client_ca_pub_code') }}</div>
                                            @endif
                                        </div>
										
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select class="form-control" name="status" id="status">
                                                <option value="0"  {{ ( $dataArr->status == 0 ) ? 'selected' : '' }}>De-Active</option>
                                                <option value="1"  {{ ( $dataArr->status == 1 ) ? 'selected' : '' }}>Active</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="footer_logo">Footer Logo</label>
                                            <input type="file" class="dropify" id="footer_logo" name="footer_logo" data-default-file="{{url('../storage/app/'.$dataArr->footer_logo)}}">
                                            @if($errors->has('footer_logo'))
                                                <div class="error">{{ $errors->first('footer_logo') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                             <div class="row mt-4">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-success pr-4 pl-4" id="submitForm">
                                    <i class="fa fa-save"></i> Update
                                </button>
                                <a href="{{ route('admin.website.index') }}" class="btn btn-danger pr-4 pl-4">
                                    <i class="fa fa-arrow-left"></i> Back
                                </a>
                            </div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropify/0.2.2/js/dropify.min.js"></script>
<script>
    $('.dropify').dropify();
</script>
@endsection