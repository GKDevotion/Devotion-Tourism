
@extends('backend.layouts.master')

@section('title')
Company Account Field Mapping - Admin Panel
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
                {{-- <h4 class="page-title pull-left d-none">Company Create</h4> --}}
                <ul class="breadcrumbs pull-left m-2">
                    <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.company.index') }}">All Companies</a></li>
                    <li><span>Update Company Mapping</span></li>
                </ul>
            </div>
        </div>
        <div class="col-md-3">
            <p class="float-end">
                @if ( false && fetchSinglePermission( $auth, 'account-field', 'add') )
                    <a class="btn btn-success text-white" href="{{ route('admin.company-account-field-index', $company->id) }}">
                        <i class="fa fa-plus"></i> Field Indexing
                    </a>
                @endif

                @if ( false && fetchSinglePermission( $auth, 'account-field', 'add') )
                    <a class="btn btn-success text-white" href="{{ route('admin.account-field.create') }}">
                        <i class="fa fa-plus"></i> MGT Field
                    </a>
                @endif

                @if ( fetchSinglePermission( $auth, 'admin.company', 'add') )
                    <a class="btn btn-success text-white" href="{{ route('admin.company.create') }}">
                        <i class="fa fa-plus"></i> Company
                    </a>
                @endif

                <a href="{{ route('admin.company.index') }}" class="btn btn-danger">
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
        <div class="col-12 mt-2">
            <div class="row">
                <div class="col-8">
                    <h3 class="mt-2">Account Management Field Selection for '{{$company->name}}'</h3>
                </div>
                <div class="col-4 mb-2 text-end">
                    @if ( fetchSinglePermission( $auth, 'account-field', 'add') )
                        <a class="btn btn-success text-white" href="{{ route('admin.company-account-field-index', $company->id) }}">
                            <i class="fa fa-plus"></i> Field Indexing
                        </a>
                    @endif

                    @if ( fetchSinglePermission( $auth, 'account-field', 'add') )
                        <a class="btn btn-success text-white" href="{{ route('admin.account-field.create') }}">
                            <i class="fa fa-plus"></i> MGT Field
                        </a>
                    @endif
                </div>
            </div>
            <div class="card">
                <div class="card-body">

                    @include('backend.layouts.partials.messages')

                    <form action="{{ route('admin.company-account-field-map.store') }}" onsubmit="return onSubmitValidateForm();" id="submitForm" method="POST" autocomplete="off" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="company_id" value="{{$company->id}}">
                        <div class="row">
                            @foreach ( $accountFields as $data )
                                <div class="col-md-3 col-sm-6 col-12">
                                    <input type="checkbox" id="data_type_{{$data->id}}" {{ in_array( $data->id, $accountMappingFields ) ? 'checked' : ''}} name="accountMappingField[]" value="{{$data->id}}">
                                    <label for="data_type_{{$data->id}}">{{$data->name}}</label>
                                </div>
                            @endforeach
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12 text-center">
                                @if ( fetchSinglePermission( $auth, 'account-field', 'add') )
                                    <button type="submit" class="btn btn-success pr-4 pl-4">
                                        <i class="fa fa-save"></i> Save
                                    </button>
                                @endif
                                <a href="{{ route('admin.company.index') }}" class="btn btn-danger pr-4 pl-4">
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
    $(document).on( "ready", function() {
        const dropifyClass = ["company-logo", "favicon"];
        $( dropifyClass ).each(function( index, className ) {

            if( $('.'+className).length > 0){
                $('.'+className).dropify();
            }
        });
    });
</script>
@endsection
