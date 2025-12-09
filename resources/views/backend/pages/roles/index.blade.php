
@extends('backend.layouts.master')

@section('title')
Role Page - Admin Panel
@endsection

@section('styles')
    <!-- Start datatable css -->
    {{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css"> --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
    <style>
        .badge-info {
            min-width: 125px;
            padding: 5px;
            margin: 5px 3px;
            background-color: #ab8134;
            border-radius: 25px;
        }
    </style>
@endsection


@section('admin-content')

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-7">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left d-none">Roles</h4>
                <ul class="breadcrumbs pull-left m-2">
                    <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li><span>All Roles</span></li>
                </ul>
            </div>
        </div>
        <div class="col-md-3 text-end">
            @if ( fetchSinglePermission( Auth::guard('admin')->user(), 'admin.role', 'add'))
                <a class="btn btn-success text-white" href="{{ route('admin.role.create') }}">
                    <i class="fa fa-plus"></i> Role
                </a>
            @endif
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

                    <div class="data-tables">
                        @include('backend.layouts.partials.messages')
                        <table id="role_index" class="">
                            <thead id="role" class="bg-light text-capitalize">
                                <tr>
                                    <th width="5%">Sl</th>
                                    <th width="10%">Name</th>
                                    <th width="10%">Update At</th>
                                    <th width="15%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                               @foreach ($roles as $role)
                                    <tr id="row_{{$role->id}}" class="role_row">
                                            <td>{{ $loop->index+1 }}</td>
                                            <td>{{ pgTitle( $role->slug ) }}</td>
                                            <td>{{formatDate( "Y-m-d H:i", $role->updated_at )}}</td>
                                            <td>

                                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="action_menu_{{$role->id}}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    &#x22EE;
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="action_menu_{{$role->id}}">

                                                    @if ( fetchSinglePermission( $auth, 'admin.role', 'edit') )
                                                        <a class="btn btn-edit text-white dropdown-item" href="{{ route('admin.role.edit', $role->id) }}">
                                                            <i class="fa fa-pencil"></i> Edit
                                                        </a>
                                                    @endif

                                                    @if ( fetchSinglePermission( $auth, 'admin.role', 'delete') )
                                                        <button class="btn btn-edit text-white delete-record dropdown-item" data-id="{{$role->id}}" data-title="{{ pgTitle( $role->slug ) }}" data-segment="roles">
                                                            <i class="fa fa-trash fa-sm" aria-hidden="true"></i> Delete
                                                        </button>
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
        if ($('#role_index').length) {
            $('#role_index').DataTable({
                responsive: true
            });
        }

     </script>
@endsection
