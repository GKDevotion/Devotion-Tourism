
@extends('backend.layouts.master')

@section('title')
Account Management Field Indexing for - Admin Panel
@endsection

@section('styles')
<style>
    .table{
        border: 1px solid grey;
    }
    .table td {
        padding: 5px 10px;
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
        <div class="col-md-2">
            <p class="float-end">
                @if ( false && fetchSinglePermission( $auth, 'account-field', 'add') )
                    <a class="btn btn-success text-white" href="{{ route('admin.account-field.create') }}">
                        <i class="fa fa-plus"></i> MGT Field
                    </a>
                @endif
                <a href="{{ route('admin.company-account-field-map.update', $company->id ) }}" class="btn btn-danger">
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
                        <a class="btn btn-success text-white" href="{{ route('admin.account-field.create') }}">
                            <i class="fa fa-plus"></i> MGT Field
                        </a>
                    @endif
                </div>
            </div>
            <div class="card">
                <div class="card-body">

                    @include('backend.layouts.partials.messages')

                    <form action="{{ route('admin.company-account-field-indexing.store') }}" onsubmit="return onSubmitValidateForm();" id="submitForm" method="POST" autocomplete="off" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="company_id" value="{{$company->id}}">
                        <div class="row">
                            <div class="col-md-6 offset-md-3">
                                <div class="row">
                                    <table class="table">
                                        @foreach ( $accountFields as $k=>$data )
                                            @if( in_array( $data->id, $companyAccountMappingFieldHidden ) )
                                                <tr>
                                                    <td style="width: 35%">
                                                        <label for="data_indexing_{{$data->id}}">{{$data->name}}</label>
                                                    </td>
                                                    <td style="width: 30%">
                                                        <?php
                                                        $oldVal = 0;
                                                        ?>
                                                        @if( isset( $accountMappingFieldIndexings[ $data->id ] ) )
                                                            <?php
                                                            $oldVal = $accountMappingFieldIndexings[ $data->id ]
                                                            ?>
                                                        @endif

                                                        <select class="unique-dropdown-selection form-control" id="data_indexing_{{$data->id}}" name="accountMappingIndexing[{{$data->id}}][sort_order]" data-selection="{{$oldVal}}">
                                                            <option value="0">Select Index</option>
                                                            @for ( $i=1; $i<=max( COUNT( $accountMappingFieldIndexings ), COUNT( $companyAccountMappingFieldHidden ) ); $i++ )
                                                                <option value="{{$i}}" {{ $oldVal == $i ? 'selected' : ''}}>{{$i}}</option>
                                                            @endfor
                                                        </select>
                                                    </td>
                                                    <td style="width: 35%">
                                                        @if( $data->is_hidden_option )
                                                            <input type="checkbox" id="is_hidden_{{$data->id}}"  name="accountMappingIndexing[{{$data->id}}][is_hidden]" {{( $accountMappingFieldHidden[$data->id] ?? 0 == 1 ) ? 'checked' : ''}}>
                                                            <label for="is_hidden_{{$data->id}}">Is Hidden Field?</label>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                </div>
                            </div>
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
    if( true ){
        $(document).on('change', '.unique-dropdown-selection', function () {
            let oldVal = $(this).attr('data-selection');

            let selectedValues = [];
            let duplicate = false;

            $('.unique-dropdown-selection').each(function () {
                let val = $(this).val();
                if (val && selectedValues.includes(val)) {
                    duplicate = true;
                    return false; // exit loop
                }

                if( val > 0 ){
                    selectedValues.push(val);
                }
            });

            if (duplicate) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Duplicate Selection',
                    text: 'You have already selected this option ('+$(this).val()+') in another dropdown!',
                });

                $(this).val(oldVal); // reset current dropdown
            } else {
                $(this).attr( "data-selection", $(this).val() ); // set current selected dropdown value
            }
        });
    }

    if( false ){
        $(document).on('change', '.unique-dropdown-selection', function () {
            let oldVal = $(this).attr('data-selection');

            let selectedValues = [];
            let duplicate = false;

            $('.unique-dropdown-selection').each(function () {
                let val = $(this).val();
                if (val && selectedValues.includes(val)) {
                    duplicate = true;
                    return false; // exit loop
                }
                selectedValues.push(val);
            });

            if (duplicate) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Duplicate Selection',
                    text: 'You have already selected this option (' + $(this).val() + ') in another dropdown!',
                });

                $(this).val(oldVal).trigger('change').focus();
            } else {
                $(this).attr('data-selection', $(this).val());
            }
        });
    }
</script>
@endsection
