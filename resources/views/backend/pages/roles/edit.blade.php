
@extends('backend.layouts.master')

@section('title')
Roles Edit - Admin Panel
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
                {{-- <h4 class="page-title pull-left d-none">Configuration Edit - {{ $data->name }}</h4> --}}
                <ul class="breadcrumbs pull-left m-2">
                <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.roles.index') }}">All Roles</a></li>
                    <li><span>Edit Configuration</span></li>
                </ul>
            </div>
        </div>
        <div class="col-md-3">
            <p class="float-end">
                <button type="button" class="btn btn-success pr-4 pl-4" onclick="$('#submitForm').click();">
                    <i class="fa fa-save"></i> Update
                </button>

                <a href="{{ route('admin.roles.index') }}" class="btn btn-danger">
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
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">

                    <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Role Name</label>
                                    <input type="text" class="form-control" id="name" value="{{ $role->name }}" name="name" placeholder="Enter a Role Name">
                                    @error('name')
                                        <div class="error text-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Guard Name</label>
                                    <select name="guard_name" id="guard_name" class="form-control">
                                        @foreach( $role_guardObj as $guard )
                                            <option value="{{$guard->slug}}" {{( $guard->slug == $role->guard_name ) ? 'selected' : '' }}>{{$guard->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('name')
                                        <div class="error text-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="name">Permissions</label>

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="checkPermissionAll" value="1" {{ App\User::roleHasPermissions($role, $all_permissions) ? 'checked' : '' }}>
                                <label class="form-check-label" for="checkPermissionAll">All</label>
                            </div>
                            <hr>
                            @php $i = 1; @endphp
                            <div class="row">
                                @foreach ($permission_groups as $group)
                                    @php
                                        $permissions = App\User::getpermissionsByGroupName($group->name, $role->guard_name);
                                        $j = 1;
                                    @endphp

                                    <div class="col-md-2">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="{{ $i }}Management" value="{{ $group->name }}" onclick="checkPermissionByGroup('role-{{ $i }}-management-checkbox', this)" {{ App\User::roleHasPermissions($role, $permissions) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="checkPermission">All {{ $group->name }}</label>
                                                </div>
                                                <div class="role-{{ $i }}-management-checkbox" style="margin-left: 20px;">

                                                    @foreach ($permissions as $permission)
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" onclick="checkSinglePermission('role-{{ $i }}-management-checkbox', '{{ $i }}Management', {{ count($permissions) }})" name="permissions[]" {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }} id="checkPermission{{ $permission->id }}" value="{{ $permission->name }}">
                                                            <label class="form-check-label {{$group->name}}" for="checkPermission{{ $permission->id }}">{{ str_ireplace( [str_ireplace( ' ', '-', $group->name.'s' ), str_ireplace( ' ', '-', $group->name ), $group->name, '.'], '', $permission->name ) }}</label>
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
                                    <i class="fa fa-save"></i> Update
                                </button>
                                <a href="{{ route('admin.roles.index') }}" class="btn btn-danger pr-4 pl-4">
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
