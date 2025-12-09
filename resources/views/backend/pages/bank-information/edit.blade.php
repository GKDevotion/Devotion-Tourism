
@extends('backend.layouts.master')

@section('title')
{{$data->company->name}} Bank Account Edit - Admin Panel
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
        <div class="col-md-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left d-none">Company Bank Account Create</h4>
                <ul class="breadcrumbs pull-left m-2">
                    <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.company.index') }}">All Company</a></li>
                    <li><a href="{{ route('company-bank-information-index', $data->company->id) }}">All Bank Account</a></li>
                    <li><span>Update {{$data->company->name}} Bank Account</span></li>
                </ul>
            </div>
        </div>
        <div class="col-md-3 text-end">
            <p class="float-end">
                @if ( false && fetchSinglePermission( $auth, 'bank-information', 'add') )
                    <a class="btn btn-success text-white" href="{{ route('company-bank-information-create', $data->company_id) }}">
                        <i class="fa fa-plus"></i> New Bank
                    </a>
                @endif

                @if ( false && fetchSinglePermission( $auth, 'bank-information', 'view') )
                    <a href="{{ route('company-bank-information-index', $data->company->id) }}" class="btn btn-danger">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                @endif

                @if ( false && fetchSinglePermission( $auth, 'admin.company', 'view') )
                    <a href="{{ route('admin.company.index' ) }}" class="btn btn-danger">
                        <i class="fa fa-arrow-left"></i> Company List
                    </a>
                @endif
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
        <div class="col-12 mt-2">
            <div class="row">
                <div class="col-8">
                    <h3 class="mt-2">'{{$data->company->name}}' Bank Account Information</h3>
                </div>
                <div class="col-4 text-end mb-2">
                    @if ( fetchSinglePermission( $auth, 'bank-information', 'add') )
                        <a class="btn btn-success text-white" href="{{ route('company-bank-information-create', $data->company_id) }}">
                            <i class="fa fa-plus"></i> New Bank
                        </a>
                    @endif

                    @if ( fetchSinglePermission( $auth, 'bank-information', 'view') )
                        <a href="{{ route('company-bank-information-index', $data->company->id) }}" class="btn btn-danger">
                            <i class="fa fa-arrow-left"></i> Back
                        </a>
                    @endif

                    @if ( fetchSinglePermission( $auth, 'admin.company', 'view') )
                        <a href="{{ route('admin.company.index' ) }}" class="btn btn-danger">
                            <i class="fa fa-arrow-left"></i> Company List
                        </a>
                    @endif
                </div>
            </div>
            <div class="card">
                <div class="card-body">

                    <form action="{{ route('admin.bank-information.update', $data->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <input type="hidden" name="company_id" value="{{$data->company->id}}">

                        <div class="row">
                            <div class="col-md-4 col-sm-12 mb-2">
                                <label class="mb-0" for="bank_name">Bank Name<span class="text-error">*</span></label>
                                <input type="text" class="form-control mb-2" data-required="yes"  id="bank_name" name="bank_name" placeholder="Bank Name" value="{{old('bank_name', $data->bank_name)}}">
                                @error('bank_name')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 col-sm-12 mb-2">
                                <label class="mb-0" for="holder_name">Holder Name<span class="text-error">*</span></label>
                                <input type="text" class="form-control mb-2" data-required="yes"  id="holder_name" name="holder_name" placeholder="holder Name" value="{{old('holder_name', $data->holder_name)}}">
                                @error('holder_name')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 col-sm-12 mb-2">
                                <label class="mb-0" for="account_number">Account Number<span class="text-error">*</span></label>
                                <input type="text" class="form-control mb-2" data-required="yes"  id="account_number" name="account_number" placeholder="Account Number" value="{{old('account_number', $data->account_number)}}">
                                @error('account_number')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 col-sm-12 mb-2">
                                <label class="mb-0" for="ifsc_code">IFSC Code </label>
                                <input type="text" class="form-control mb-2" id="ifsc_code" name="ifsc_code" placeholder="IFSC Code" value="{{old('ifsc_code', $data->ifsc_code)}}">
                                @error('ifsc_code')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 col-sm-12 mb-2">
                                <label class="mb-0" for="iban">IBAN </label>
                                <input type="text" class="form-control mb-2" id="iban" name="iban" placeholder="IBAN Code" value="{{old('iban', $data->iban)}}">
                                @error('iban')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 col-sm-12 mb-2">
                                <label class="mb-0" for="branch_code">Branch Name<span class="text-error">*</span></label>
                                <input type="text" class="form-control mb-2" data-required="yes"  id="branch_code" name="branch_code" placeholder="Branch Name" value="{{old('branch_code', $data->branch_code)}}">
                                @error('branch_code')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-2">
                                <div class="form-group">
                                    <label class="mb-0" for="currency_id">Currency<span class="text-error">*</span></label>
                                    <select class="form-control" data-required="yes" id="currency_id" name="currency_id">
                                        <option value="">Select Currency</option>
                                        @foreach( $currency as $ar )
                                            <option value="{{$ar->id}}" {{$data->currency_id == $ar->id ? 'selected' : ''}}>{{$ar->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('currency_id')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-8 col-sm-12 mb-2">
                                <label class="mb-0" for="description">Description</label>
                                <textarea name="description" id="description" class="form-control bank-address" rows="4" placeholder="Description">{{old('description', $data->description)}}</textarea>
                                @error('description')
                                    <div class="error text-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12 text-center">
                                @if ( fetchSinglePermission( $auth, 'bank-information', 'edit') )
                                    <button type="submit" class="btn btn-success pr-4 pl-4" id="submitForm">
                                        <i class="fa fa-save"></i> Save
                                    </button>
                                @endif

                                @if ( fetchSinglePermission( $auth, 'bank-information', 'view') )
                                    <a href="{{ route('company-bank-information-index', $data->company->id) }}" class="btn btn-danger pr-4 pl-4">
                                        <i class="fa fa-arrow-left"></i> Back
                                    </a>
                                @endif
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
