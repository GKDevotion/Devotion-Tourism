
@extends('backend.layouts.master')

@section('title')
User Create - Admin Panel
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

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
                {{-- <h4 class="page-title pull-left d-none">User Create</h4> --}}
                <ul class="breadcrumbs pull-left m-2">
                    <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.user.index') }}">All Users</a></li>
                    <li><span>Create User</span></li>
                </ul>
            </div>
        </div>
        <div class="col-md-3">
            <p class="float-end">
                @if (Auth::guard('admin')->user()->can('user.create'))
                    <button type="button" class="btn btn-success pr-4 pl-4" onclick="$('#submitForm').click();">
                        <i class="fa fa-save"></i> Save
                    </button>
                @endif
                <a href="{{ route('admin.user.index') }}" class="btn btn-danger">
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
            <h3 class="pb-3">Create User</h3>
            <div class="card">
                <div class="card-body">

                    <form action="{{ route('admin.user.store') }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12 mb-2">
                                <label class="mb-0" for="name">User Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
                                @error('name')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6 col-sm-12 mb-2">
                                <label class="mb-0" for="email">User Email</label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email">
                                @error('email')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6 col-sm-12 mb-2">
                                <label class="mb-0" for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password">
                                @error('password')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6 col-sm-12 mb-2">
                                <label class="mb-0" for="password_confirmation">Confirm Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Enter Password">
                                @error('password_confirmation')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-4 col-sm-12 mb-2">
                                <label class="mb-0" for="industry_id">Industry</label>
                                <select name="industry_id" id="industry_id" class="industry_id form-control">
                                    <option value="0">Select Industry</option>
                                    @foreach ($industries as $ar)
                                        <option value="{{ $ar->id }}">{{ $ar->name }}</option>
                                    @endforeach
                                </select>
                                @error('industry_id')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-4 col-sm-12 mb-2">
                                <label class="mb-0" for="company_parent_id">Holding Company</label>
                                <select name="company_parent_id" id="company_parent_id" class="company_parent_id form-control">
                                    <option value="0">Select Holding Company</option>
                                    @foreach ($parent_companies as $ar)
                                        <option value="{{ $ar->id }}">{{ $ar->name }}</option>
                                    @endforeach
                                </select>
                                @error('company_parent_id')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-4 col-sm-12 mb-2">
                                <label class="mb-0" for="company_id">Company</label>
                                <select name="company_id" id="company_id" class="company_id form-control">
                                    <option value="0">Select Company</option>
                                    @foreach ($companies as $ar)
                                        <option class="company-parent-id company_parent_id_{{$ar->parent_id}} d-none" value="{{ $ar->id }}">{{ $ar->name }}</option>
                                    @endforeach
                                </select>
                                @error('company_id')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6 col-sm-12 mb-2">
                                <label class="mb-0" for="roles">Assign Roles</label>
                                <select name="roles[]" id="roles" class="form-control">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                @error('roles')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6 col-sm-12 mb-2">
                                <label class="mb-0" for="status">status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="0">Disabled</option>
                                    <option value="1">Enabled</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-success pr-4 pl-4" id="submitForm">
                                    <i class="fa fa-save"></i> Save
                                </button>
                                <a href="{{ route('admin.user.index') }}" class="btn btn-danger pr-4 pl-4">
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
        $('.select2').select2();
    })

    $(window).ready(function() {
        $('#company_parent_id').on("change", function(){
            $(".company-parent-id").addClass('d-none')
            $(".company_parent_id_"+$(this).val()).removeClass('d-none')
        });
    });
</script>
@endsection
