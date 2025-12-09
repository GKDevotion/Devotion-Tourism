
@extends('backend.layouts.master')

@section('title')
Department Edit - Admin Panel
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
                {{-- <h4 class="page-title pull-left d-none">Department Edit - {{ $data->name }}</h4> --}}
                <ul class="breadcrumbs pull-left m-2">
                <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.department.index') }}">All Departments</a></li>
                    <li><span>Edit Department</span></li>
                </ul>
            </div>
        </div>
        <div class="col-md-3">
            <p class="float-end">
                @if (Auth::guard('admin')->user()->can('department.edit'))
                    <button type="button" class="btn btn-success pr-4 pl-4" onclick="$('#submitForm').click();">
                        <i class="fa fa-save"></i> Update
                    </button>
                @endif
                <a href="{{ route('admin.department.index') }}" class="btn btn-danger">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </p>
        </div>
        @include('backend.layouts.partials.header-menu')
    </div>
</div>
<!-- page title area end -->

<div class="main-content-inner">
    <div class="row">
        <!-- data table start -->
        <div class="col-12 mt-3">
            <h3 class="pb-3">Update Department</h3>
            <div class="card">
                <div class="card-body">

                    <form action="{{ route('admin.department.update', $data->id) }}" onsubmit="return onSubmitValidateForm();" method="POST">
                        @method('PUT')
                        @csrf
                        <input type="hidden" name="company_name" id="company_name" value="">
                        <div class="row">
                            <div class="col-md-8 offset-2">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="name">Name<span class="text-error">*</span></label>
                                            <input type="text" class="form-control" data-required="yes" id="name" name="name" placeholder="Name" value="{{$data->name}}" autofocus>
                                            @error('name')
                                                <div class="error text-error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 form-group col-sm-12 mb-2">
                                        <label class="mb-0" for="sort_order">Sort Order</label>
                                        <input type="number" class="form-control allow-only-number" id="sort_order" name="sort_order" placeholder="Sort Order" value="{{$data->sort_order}}">
                                        @error('sort_order')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="industry_id">Industry<span class="text-error">*</span></label>
                                            <select class="form-control" id="industry_id" data-required="yes" name="industry_id">
                                                <option value="" >Select Industry</option>
                                                @foreach( $industries as $ar )
                                                    <option value="{{$ar->id}}" {{$data->industry_id == $ar->id ? 'selected' : ''}}>{{$ar->name}}</option>
                                                @endforeach
                                            </select>
                                            @error('industry_id')
                                                <div class="error text-error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="company_id">Company<span class="text-error">*</span></label>
                                            <select name="company_id" id="company_id" data-required="yes" class="company_id form-control">
                                                <option value="" >Select Parent Category</option>
                                                @foreach( $companies as $ar )
                                                    <option class="company-parent-id company_parent_id_{{$ar->industry_id}} {{ ( $data->company_id == $ar->id ) ? '' : 'd-none' }}" value="{{$ar->id}}" {{$data->company_id == $ar->id ? 'selected' : ''}}>{{$ar->name}}</option>
                                                @endforeach
                                            </select>
                                            @error('company_id')
                                                <div class="error text-error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6 col-sm-12 mb-2">
                                        <label class="mb-0" for="admin_menu_id">Menu Route<span class="text-error">*</span></label>
                                        <select name="admin_menu_id" id="admin_menu_id" data-required="yes" class="admin_menu_id form-control">
                                            <option value="" >Select Menu Route</option>
                                            @foreach ($adminMenus as $ar)
                                                <option value="{{ $ar->id }}" {{$data->admin_menu_id == $ar->id ? 'selected' : ''}}>{{ $ar->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('admin_menu_id')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="status">Status</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="1" {{$data->status == 1 ? 'selected' : ''}}>Active</option>
                                                <option value="0" {{$data->status == 0 ? 'selected' : ''}}>De Active</option>
                                            </select>
                                            @error('status')
                                                <div class="error text-error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-success pr-4 pl-4">
                                    <i class="fa fa-save"></i> Update
                                </button>
                                <a href="{{ route('admin.department.index') }}" class="btn btn-danger pr-4 pl-4">
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
        $('#industry_id').on("change", function(){
            $(".company-parent-id").addClass('d-none')
            $(".company_parent_id_"+$(this).val()).removeClass('d-none')
        });

        $('#company_id').on( "change", function(){
            $( "#company_name" ).val( $(this).find("option:selected").text() );
        } );
    });
</script>
@endsection
