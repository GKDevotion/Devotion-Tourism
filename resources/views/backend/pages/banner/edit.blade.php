@extends('backend.layouts.master')

@section('title')
    Banner Edit - Admin Panel
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
            <div class="col-md-7">
                <div class="breadcrumbs-area clearfix">
                    <h4 class="page-title pull-left d-none">Banner Edit - {{ $data->name }}</h4>
                    <ul class="breadcrumbs pull-left m-2">
                        <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                        <li><a href="{{ route('admin.banner.index') }}">All Banner</a></li>
                        <li><span>Edit Banner</span></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-3">
                <p class="float-end">
                    @if (Auth::guard('admin')->user()->can('banner.edit'))
                        <button type="button" class="btn btn-success pr-4 pl-4" onclick="$('#submitForm').click();">
                            <i class="fa fa-save"></i> Update
                        </button>
                    @endif
                    <a href="{{ route('admin.banner.index') }}" class="btn btn-danger">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </p>
            </div>
            <div class="col-md-2 clearfix">
                @include('backend.layouts.partials.logout')
            </div>
        </div>
    </div>
    <!-- page title area end -->

    <div class="main-content-inner">
        <div class="row">
            <!-- data table start -->
            <div class="col-12 mt-3">
                <h3 class="pb-3">Update Banner</h3>
                <div class="card">
                    <div class="card-body">

                        <form action="{{ route('admin.banner.update', $data->id) }}" enctype="multipart/form-data"
                            onsubmit="return onSubmitValidateForm();" method="POST">
                            @method('PUT')
                            @csrf
                            <div class="row">
                                <div class="col-md-10 offset-1">
                                    <div class="row">
                                        <div class="row">


                                            <div class="col-md-4 mb-2">
                                                <label class="mb-0" for="image">Image</label>

                                                <input type="file" class="dropify" id="image" name="image"
                                                    accept="image/png,image/jpeg,image/webp"
                                                    data-default-file="{{ isset($data->image) ? asset('storage/app/banner/' . $data->image) : '' }}" />

                                                @error('image')
                                                    <div class="error text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>



                                            <div class="col-md-4 mb-2">
                                                <div class="form-group">
                                                    <label class="mb-0" for="name">Title</label>
                                                    <input type="text" class="form-control" id="name" name="name"
                                                        placeholder="name" value="{{ $data->name }}">
                                                </div>
                                                @error('name')
                                                    <div class="error text-error">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-4 mb-2">
                                                <div class="form-group">
                                                    <label class="mb-0" for="sub_title">Sub Title</label>
                                                    <input type="text" class="form-control" id="sub_title" name="sub_title"
                                                        placeholder="sub_title" value="{{ $data->sub_title }}">
                                                </div>
                                                @error('sub_title')
                                                    <div class="error text-error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4 mb-2">
                                                <div class="form-group">
                                                    <label class="mb-0" for="status">Status</label>
                                                    <select class="form-control" id="status" name="status">
                                                        <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>
                                                            Active</option>
                                                        <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>
                                                            De Active</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-success pr-4 pl-4" id="submitForm">
                                            <i class="fa fa-save"></i> Update
                                        </button>
                                        <a href="{{ route('admin.banner.index') }}" class="btn btn-danger pr-4 pl-4">
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
        // $(window).ready(function() {
        //     $('#continent_id').on("change", function() {
        //         $(".continent-id").addClass('d-none')
        //         $(".continent_id_" + $(this).val()).removeClass('d-none')
        //     });

        //     $('#country_id').on("change", function() {
        //         $(".country-id").addClass('d-none')
        //         $(".country_id_" + $(this).val()).removeClass('d-none')
        //     });
        // })

        $('.dropify').dropify();

        // Optional Remove Confirmation
        var drEvent = $('.dropify').dropify();

        drEvent.on('dropify.beforeClear', function(event, element) {
            return confirm("Do you really want to remove \"" + element.file.name + "\" ?");
        });
    </script>
@endsection
