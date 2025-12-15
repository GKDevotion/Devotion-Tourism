@extends('backend.layouts.master')

@section('title')
    Role - Admin Panel
@endsection

@section('styles')
    <!-- Start datatable css -->
    {{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css"> --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
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
                    {{-- <h4 class="page-title pull-left d-none">Configuration</h4> --}}
                    <ul class="breadcrumbs pull-left m-2">
                        <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                        <li><span>All Role</span></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-2 text-end">
                <a class="btn btn-success text-white" href="{{ route('admin.roles.create') }}">
                    <i class="fa fa-plus" aria-hidden="true"></i> Add Role
                </a>
            </div>
            <div class="col-md-1">
                <span class="text-theme">
                    <i class="fa fa-user"></i>
                    {{ auth()->guard('admin')->user()->username }}
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

                        <div class="data-tables">
                            @include('backend.layouts.partials.messages')
                            <table id="role_index" class="">
                                <thead id="role" class="bg-light text-capitalize">
                                    <tr>
                                        <th width="5%">Sl</th>
                                        <th width="25%">Name</th>
                                        <th width="60%">Permissions</th>
                                        <th width="10%">Update At</th>
                                        <th width="15%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roles as $role)
                                        <tr id="row_{{ $role->id }}" class="role_row">
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ pgTitle($role->slug) }}</td>
                                            <td class="text-left">
                                                <?php
                                                $checkGroup = [];
                                                ?>
                                                @if (isset($role->permissions) && count($role->permissions) > 0)
                                                    @foreach ($role->permissions as $perm)
                                                        @if (isset($perm->name) && isset($perm->group_name))
                                                            @if (!in_array($perm->group_name, $checkGroup))
                                                                <?php
                                                                // Extract group name from permission name (before the dot)
                                                                $groupName = '';
                                                                if (strpos($perm->name, '.') !== false) {
                                                                    $groupName = substr($perm->name, 0, strpos($perm->name, '.'));
                                                                } else {
                                                                    $groupName = $perm->name; // fallback if no dot found
                                                                }
                                                                
                                                                // Or use group_name directly if available
                                                                if (!empty($perm->group_name)) {
                                                                    $groupName = $perm->group_name;
                                                                }
                                                                ?>

                                                                <span class="badge badge-info mr-1">
                                                                    {{ pgTitle($groupName) }}
                                                                </span>

                                                                <?php
                                                                $checkGroup[] = $perm->group_name;
                                                                ?>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">No permissions</span>
                                                @endif
                                            </td>
                                            <td>{{ formatDate('Y-m-d H:i', $role->updated_at) }}</td>
                                            <td>

                                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button"
                                                    id="action_menu_{{ $role->id }}" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    &#x22EE;
                                                </button>
                                                <div class="dropdown-menu"
                                                    aria-labelledby="action_menu_{{ $role->id }}">

                                                    <a class="btn btn-edit text-white dropdown-item"
                                                        href="{{ route('admin.roles.edit', $role->id) }}">
                                                        <i class="fa fa-pencil"></i> Edit
                                                    </a>
                                                    <button class="btn btn-edit text-white delete-record dropdown-item"
                                                        data-id="{{ $role->id }}" data-title="{{ $role->name }}"
                                                        data-segment="roles">
                                                        <i class="fa fa-trash fa-sm" aria-hidden="true"></i> Delete
                                                    </button>
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
