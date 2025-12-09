
@extends('backend.layouts.master')

@section('title')
Role Create - Admin Panel
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
        <div class="col-sm-7">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left d-none">Role Create</h4>
                <ul class="breadcrumbs pull-left m-2">
                    <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.role.index') }}">All Roles</a></li>
                    <li><span>Create Role</span></li>
                </ul>
            </div>
        </div>
        <div class="col-md-3">
            <p class="float-end">
                @if ( fetchSinglePermission( Auth::guard('admin')->user(), 'admin.role', 'add'))
                    <button type="button" class="btn btn-success pr-4 pl-4" onclick="$('#submitForm').click();">
                        <i class="fa fa-save"></i> Save
                    </button>
                @endif
                <a href="{{ route('admin.role.index') }}" class="btn btn-danger">
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
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">

                    <form action="{{ route('admin.role.store') }}" method="POST" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="mb-0">Role Name</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter a Role Name">
                                    @error('name')
                                        <div class="error text-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <div class="form-group mt-2">
                            <label for="name" class="mb-0">Permissions</label>

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="checkPermissionAll" value="1">
                                <label class="form-check-label" for="checkPermissionAll">All</label>
                            </div>
                            <hr>
                            @php $i = 1; @endphp
                            <div class="row">
                                @foreach ($permission_groups as $group)
                                    <div class="col-md-2">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="{{ $i }}Management" value="{{ $group->name }}" onclick="checkPermissionByGroup('role-{{ $i }}-management-checkbox', this)">
                                                    <label class="form-check-label mb-0" for="checkPermission">All {{ $group->name }}</label>
                                                </div>
                                                <div class="role-{{ $i }}-management-checkbox" style="margin-left: 20px;">
                                                    @php
                                                        $permissions = App\User::getpermissionsByGroupName($group->name, 'admin');
                                                        $j = 1;
                                                    @endphp
                                                    @foreach ($permissions as $permission)
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" name="permissions[]" id="checkPermission{{ $permission->id }}" value="{{ $permission->name }}">
                                                            <label class="mb-0 form-check-label {{$group->name}}" for="checkPermission{{ $permission->id }}">{{ str_ireplace( [str_ireplace( ' ', '-', $group->name.'s' ), str_ireplace( ' ', '-', $group->name ), $group->name, '.'], '', $permission->name ) }}</label>
                                                        </div>
                                                        @php  $j++; @endphp
                                                    @endforeach
                                                    <br>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                @php  $i++; @endphp
                                @endforeach
                            </div>

                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-success pr-4 pl-4" id="submitForm">
                                    <i class="fa fa-save"></i> Save
                                </button>
                                <a href="{{ route('admin.role.index') }}" class="btn btn-danger pr-4 pl-4">
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
     @include('backend.pages.roles.partials.scripts')
@endsection
