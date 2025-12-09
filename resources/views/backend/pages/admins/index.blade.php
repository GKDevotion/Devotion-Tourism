
@extends('backend.layouts.master')

@section('title')
Users - Admin Panel
@endsection

@section('styles')
    <!-- Start datatable css -->
    {{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css"> --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
    <style>
        .badge-info {
            min-width: 100px;
            padding: 8px;
            margin: 2px;
        }
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
        <div class="col-md-7">
            <div class="breadcrumbs-area clearfix">
                {{-- <h4 class="page-title pull-left d-none">Admins</h4> --}}
                <ul class="breadcrumbs pull-left m-2">
                    <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li><span>All Users</span></li>
                </ul>
            </div>
        </div>
        <div class="col-md-2 text-end">
            @if ( false && Auth::guard('admin')->user()->can('admin.edit'))
                <a class="btn btn-success text-white" href="{{ route('admin.admin.create') }}">
                    <i class="fa fa-plus"></i> User
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
                    <h3 class="mt-2">User Management</h3>
                </div>
                <div class="col-4 text-end mb-2">
                    @if (fetchSinglePermission( $user, 'admin.admin', 'add'))
                        <a class="btn btn-success text-white" href="{{ route('admin.admin.create') }}">
                            <i class="fa fa-plus"></i> User
                        </a>
                    @endif
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="data-tables">

                        @include('backend.layouts.partials.messages')

                        <table id="dataTable" class="text-center">
                            <thead class="bg-light text-capitalize">
                                <tr>
                                    <th width="2%">#</th>
                                    <th width="2%">Acc No.</th>
                                    <th width="2%">Name</th>
                                    {{-- <th width="2%">User Name</th> --}}
                                    <th width="2%">Email</th>
                                    <th width="2%">Contact No.</th>
                                    <th width="2%">Designation</th>
                                    <th width="2%">Updated At</th>
                                    <th width="2%">Status</th>
                                    <th width="2%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                               @foreach ($admins as $data)
                               <tr>
                                    <td class="text-center">{{ $loop->index+1 }}</td>
                                    <td class="text-left">{{ $data->acc_no }}</td>
                                    <td class="text-left">{{ $data->first_name }} {{ $data->last_name }}</td>
                                    {{-- <td class="text-left">{{ $data->username }}</td> --}}
                                    <td class="text-left">{{ $data->email }}</td>
                                    <td class="text-left">{{ $data->mobile_number }}</td>
                                    <td class="text-left">{{ $data->group->name }}</td>
                                    <td>{{formatDate( "Y-m-d H:i", $data->updated_at )}}</td>
                                    <td>
                                        <i class="fa fa-{{( $data->status == 0 ? 'times' : 'check')}} update-status" data-status="{{$data->status}}" data-id="{{$data->id}}" aria-hidden="true" data-table="admins"></i>
                                    </td>
                                    <td>

                                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="action_menu_{{$data->id}}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            &#x22EE;
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="action_menu_{{$data->id}}">

                                            @if ( fetchSinglePermission( $user, 'admin.admin', 'edit') )
                                                <a class="btn btn-edit text-white dropdown-item" href="{{ route('admin.admin.edit', $data->id) }}">
                                                    <i class="fa fa-pencil"></i> Edit
                                                </a>
                                            @endif

                                            @if ( false && fetchSinglePermission( $user, 'admin.admin', 'delete') && $data->group->key != "SUPER_ADMIN" )
                                                <a class="btn btn-edit text-white dropdown-item" href="{{ route('admin.admin.destroy', $data->id) }}"
                                                    onclick="event.preventDefault(); document.getElementById('delete-form-{{ $data->id }}').submit();">
                                                    <i class="fa fa-trash"></i> Delete
                                                </a>
                                                <form id="delete-form-{{ $data->id }}" action="{{ route('admin.admin.destroy', $data->id) }}" method="POST" style="display: none;">
                                                    @method('DELETE')
                                                    @csrf
                                                </form>
                                            @endif

                                            @if ( fetchSinglePermission( $user, 'admin.permission', 'edit') )
                                                <a class="btn btn-edit text-white dropdown-item" href="{{ url('admin/permission?item_id='._en( $data->id ) ) }}">
                                                    <i class="fa fa-lock"></i> Permission
                                                </a>
                                            @endif

                                        </div>
                                    </td>
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
     <!-- Start datatable js -->
     <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
     <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
     <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
     <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
     <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>

     <script>
         /*================================
        datatable active
        ==================================*/
        if ($('#dataTable').length) {
            $('#dataTable').DataTable({
                responsive: true
            });
        }

     </script>
@endsection
