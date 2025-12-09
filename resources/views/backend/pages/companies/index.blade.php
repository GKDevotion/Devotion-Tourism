
@extends('backend.layouts.master')
<?php
use App\Models\Currency;
?>
@section('title')
Company Management - Admin Panel
@endsection

@section('styles')
    <!-- Start datatable css -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
    <style>
        td{
            text-align: left;
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
                {{-- <h4 class="page-title pull-left d-none">Company</h4> --}}
                <ul class="breadcrumbs pull-left m-2">
                    <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li><span>All Company</span></li>
                </ul>
            </div>
        </div>
        <div class="col-md-2 text-end">
            @if ( false && fetchSinglePermission( $auth, 'admin.company', 'add') )
                <a class="btn btn-success text-white" href="{{ route('admin.company.create') }}">
                    <i class="fa fa-plus"></i> Company
                </a>
            @endif
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
                    <h3 class="mt-2">Company Management</h3>
                </div>
                <div class="col-4 mb-2 text-end">
                    @if ( fetchSinglePermission( $auth, 'admin.company', 'add') )
                        <a class="btn btn-success text-white" href="{{ route('admin.company.create') }}">
                            <i class="fa fa-plus"></i> Company
                        </a>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="table-card-body card-body">

                    <div class="data-tables">

                        @include('backend.layouts.partials.messages')

                        <table id="companies_index" class="w-100">
                            <thead id="companies" class="bg-light text-capitalize">
                                <tr>
                                    <th>Action</th>
                                    <th>Sr</th>
                                    {{-- <th>Logo</th> --}}
                                    <th>Name</th>
                                    <th>Contact</th>
                                    <th>Email ID</th>
                                    <th>Currency</th>
                                    {{-- <th>Sort Name</th> --}}
                                    <th>Address</th>
                                    <th>Status</th>
                                    <th>Desktop</th>
                                    <th>Assign User</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataArr as $data)
                                    <tr>
                                        <td>

                                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="action_menu_{{$data->id}}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    &#x22EE;
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="action_menu_{{$data->id}}">

                                                @if ( $auth->admin_user_group_id == 1 && fetchSinglePermission( $auth, 'admin.company', 'edit') )
                                                    <a class="btn btn-edit text-white dropdown-item" href="{{route('admin.company.edit', $data->id)}}">
                                                        <i class="fa fa-pencil"></i> Edit
                                                    </a>

                                                @endif

                                                @if( fetchSinglePermission( $auth, 'account-management', 'add') )
                                                    <a class="btn btn-edit text-white dropdown-item" href="{{route('admin.company-account-field-map.update', $data->id)}}">
                                                        <i class="fa fa-columns"></i> Field Mapping
                                                    </a>

                                                    <a class="btn btn-edit text-white dropdown-item" href="{{route('company-account-management-index', $data->id)}}">
                                                        <i class="fa fa-building-o"></i> Client Company(s)
                                                    </a>
                                                @endif

                                                @if( fetchSinglePermission( $auth, 'bank-information', 'add') )
                                                    <a class="btn btn-edit text-white dropdown-item" href="{{route('company-bank-information-index', $data->id)}}">
                                                        <i class="fa fa-university"></i> Bank Account
                                                    </a>
                                                @endif

                                                @if( fetchSinglePermission( $auth, 'account-management', 'add') )
                                                    <a class="btn btn-edit text-white dropdown-item" href="{{route('company-account-summery-index', $data->id)}}">
                                                        <i class="fa fa-file-archive-o"></i> Account Summery
                                                    </a>
                                                @endif

                                                @if ( fetchSinglePermission( $auth, 'admin.company', 'delete') )
                                                    <button class="btn btn-edit text-white dropdown-item delete-record" data-id="{{$data->id}}" data-title="'.$data->name.'" data-segment="companies">
                                                        <i class="fa fa-trash fa-sm" aria-hidden="true"></i> Delete
                                                    </button>
                                                @endif

                                            </div>
                                        </td>
                                        <td class="text-center">{{ $loop->index+1 }}</td>
                                        <td class="text-left">{{ $data->name }}</td>
                                        <td class="text-left">{{ $data->contact_number }}</td>
                                        <td class="text-left">{{ $data->email_id }}</td>
                                        <td class="text-left">
                                            <?php
                                            $currencyArr = json_decode( $data->currency_id, 1 );
                                            $currency = "";
                                            if( is_array( $currencyArr ) ){
                                                foreach( $currencyArr as $id ){
                                                    $currencyObj = Currency::select( 'name' )->find($id);
                                                    $currency.= $currencyObj->name.", ";
                                                }
                                            }
                                            ?>
                                            {{rtrim( $currency, ", ")}}
                                        </td>
                                        <td class="text-left">{{ $data->address }}</td>
                                        <td>
                                            <i class="fa fa-{{( $data->status == 0 ? 'times' : 'check')}} update-status" data-status="{{$data->status}}" data-id="{{$data->id}}" aria-hidden="true" data-table="companies"></i>
                                        </td>
                                        <td>
                                            <i class="fa fa-{{( $data->is_dashboard == 0 ? 'times' : 'check')}} update-field-status" data-status="{{$data->is_dashboard}}" data-field="is_dashboard" data-id="{{$data->id}}" aria-hidden="true" data-table="companies"></i>
                                        </td>
                                        <td>
                                            <?php
                                            $assignUser = "";
                                            foreach( $data->adminmap as $am ){
                                                $assignUser.= $am->admin->username." (".$am->admin->acc_no."),<br>";
                                            }
                                            ?>
                                            {!!rtrim( $assignUser, ",<br>" )!!}
                                        </td>
                                        {{-- <td>{{formatDate( "Y-m-d H:i", $data->updated_at )}}</td> --}}

                                    </tr>
                               @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- data table end -->

    </div>
</div>

@endsection

@section('scripts')

    @include('backend.layouts.partials.data-table')

     <script>

        $(document).ready(function() {
            var table = $('#companies_index').DataTable();
        });
     </script>
@endsection
