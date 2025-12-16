@extends('backend.layouts.master')

@section('title')
    Banner Create - Admin Panel
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
                    <h4 class="page-title pull-left d-none">Banner Create</h4>
                    <ul class="breadcrumbs pull-left m-2">
                        <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                        <li><a href="{{ route('admin.banner.index') }}">All Banner</a></li>
                        <li><span>Create Currency</span></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-3">
                <p class="float-end">
                    @if (Auth::guard('admin')->user()->can('banner.create'))
                        <button type="button" class="btn btn-success pr-4 pl-4" onclick="$('#submitForm').click();">
                            <i class="fa fa-save"></i> Save
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
                <h3 class="pb-3">Create Banner</h3>
                <div class="card">
                    <div class="card-body">
                        {{-- @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif  --}}
                        <form action="{{ route('admin.banner.store') }}" onsubmit="return onSubmitValidateForm();"
                            enctype="multipart/form-data" method="POST" autocomplete="off">
                            @csrf
                            <div class="row">
                                <div class="col-md-10 offset-1">
                                    <div class="row">

                                        <div class="row">

                                            
                                            <div class="col-md-4 mb-2">
                                                <label class="mb-0" for="image">Image</label>
                                                <input type="file" class="dropify" id="image" name="image"
                                                    accept="image/png,image/jpeg,image/webp" />
                                                @error('image')
                                                    <div class="error text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>


                                            <div class="col-md-4 mb-2">
                                                <div class="form-group">
                                                    <label class="mb-0" for="name">Title<span
                                                            class="text-error">*</span></label>
                                                    <input type="text" class="form-control" data-required="yes"
                                                        id="name" name="name" placeholder="name">
                                                </div>
                                                @error('name')
                                                    <div class="error text-error">{{ $message }}</div>
                                                @enderror
                                            </div>


                                            <div class="col-md-4 mb-2">
                                                <div class="form-group">
                                                    <label class="mb-0" for="sub_title">Sub Title</label>
                                                    <input type="text" class="form-control" id="sub_title"
                                                        name="sub_title" placeholder="Sub Title Name">
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
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@section('scripts')
    <script>
        // $(window).ready(function() {
        //     $('#continent_id').on("change", function(){
        //         $(".continent-id").addClass('d-none')
        //         $(".continent_id_"+$(this).val()).removeClass('d-none')
        //     });

        //     $('#country_id').on("change", function(){
        //         $(".country-id").addClass('d-none')
        //         $(".country_id_"+$(this).val()).removeClass('d-none')
        //     });
        // })


        $('.dropify').dropify({
            messages: {
                default: 'Drag and drop file here or click',
                replace: 'Drag and drop or click to replace',
                remove: 'Remove',
                error: 'Oops, something went wrong.'
            }
        });

        var drEvent = $('#image').dropify();
    </script>
@endsection
