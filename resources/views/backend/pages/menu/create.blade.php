
@extends('backend.layouts.master')

@section('title')
Admin Menu Create - Admin Panel
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
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                {{-- <h4 class="page-title pull-left d-none">Admin Menu Create</h4> --}}
                <ul class="breadcrumbs pull-left m-2">
                    <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.menu.index') }}">All Menu</a></li>
                    <li><span>Create Admin Menu</span></li>
                </ul>
            </div>
        </div>
        <div class="col-md-3">
            <p class="float-end">
                @if (Auth::guard('admin')->user()->can('menu.create'))
                    <button type="button" class="btn btn-success pr-4 pl-4" onclick="$('#submitForm').click();">
                        <i class="fa fa-save"></i> Save
                    </button>
                @endif
                <a href="{{ route('admin.menu.index') }}" class="btn btn-danger">
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
            <h3 class="pb-3">Create Menu</h3>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.menu.store') }}" onsubmit="return onSubmitValidateForm();" method="POST" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <div class="form-group">
                                    <label class="mb-0" for="parent_id">Parent Menu</label>
                                    <select class="form-control parent-id" id="parent_id" name="parent_id" autofocus>
                                        @foreach( getMultiLevelAdminMenuDropdown() as $k=>$name )
                                            <option value="{{$k}}">{{$name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('parent_id')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-2">
                                <div class="form-group">
                                    <label class="mb-0" for="name">Menu Name<span class="text-error">*</span></label>
                                    <input type="text" data-required="yes" class="form-control" id="name" name="name" placeholder="Menu Name">
                                </div>
                                @error('name')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-2">
                                <div class="form-group">
                                    <label class="mb-0" for="group_name">Group Name<span class="text-error">*</span></label>
                                    <input type="text" data-required="yes" class="form-control" id="group_name" name="group_name" placeholder="Group Name">
                                </div>
                                @error('group_name')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-2">
                                <div class="form-group">
                                    <label class="mb-0" for="class_name">Class Name(Route)<span class="text-error">*</span></label>
                                    <input type="text" data-required="yes" class="form-control" id="class_name" name="class_name" placeholder="Class Name (Route)">
                                </div>
                                @error('class_name')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <div class="form-group">
                                    <label class="mb-0" for="icon">Icon</label>
                                    <input type="text" class="form-control" id="icon" name="icon" placeholder="Icon">
                                </div>
                                @error('icon')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-2">
                                <div class="form-group">
                                    <label class="mb-0" for="sort_order">Sort Order</label>
                                    <input type="number" class="form-control allow-only-number" id="sort_order" name="sort_order" placeholder="Sort Order">
                                </div>
                                @error('sort_order')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-2">
                                <div class="form-group">
                                    <label class="mb-0" for="status">Status</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="1">Active</option>
                                        <option value="0">De Active</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-success pr-4 pl-4" id="submitForm">
                                    <i class="fa fa-save"></i> Save
                                </button>
                                <a href="{{ route('admin.menu.index') }}" class="btn btn-danger pr-4 pl-4">
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
