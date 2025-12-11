@extends('backend.layouts.master')

@section('title')
    Websites - Admin Panel
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
                        <li><span>All Website</span></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-2 text-end">
                <a class="btn btn-success text-white" href="{{ route('admin.website.create') }}">
                    <i class="fa fa-plus" aria-hidden="true"></i> Add Website
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
            <div class="col-md-12">
                <div class="card">

                    <div class="card-body table-responsive">
                        <table id="website" class="table table-bordered table-striped" data-order='[[ 4, "desc" ]]'>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Favicon</th>
                                    <th class="text-center">Header Logo</th>
                                    <th class="text-center">Footer Logo</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Advertisement</th>
                                    <th class="text-center">Updated At</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($dataArr as $ar)
                                    <tr id="row_{{ $ar->id }}" class="role_row">
                                        <td class="text-center">{{ $ar->id }}</td>
                                        <td class="text-center">{{ $ar->name }}</td>
                                        <td>
                                            <img src="{{ url('storage/app/' . $ar->favicon) }}" alt="{{ $ar->name }}"
                                                height="55px">
                                        </td>
                                        <td>
                                            <img src="{{ url('storage/app/' . $ar->header_logo) }}"
                                                alt="{{ $ar->name }}" height="55px">
                                        </td>
                                        <td>
                                            <img src="{{ url('storage/app/' . $ar->header_logo) }}"
                                                alt="{{ $ar->name }}" height="55px">
                                        </td>
                                        <td class="text-center">
                                            <select
                                                class="form-control update-status badge-{{ $ar->status == 0 ? 'warning' : 'success' }}"
                                                name="status" data-id="{{ $ar->id }}" data-table="websites">
                                                <option value="1" {{ $ar->status == 1 ? 'selected' : '' }}>Active
                                                </option>
                                                <option value="0" {{ $ar->status == 0 ? 'selected' : '' }}>De-Active
                                                </option>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            @if ($ar->is_run_advertisement == 0)
                                                <span class="badge badge-pill badge-warning"> Disabled </span>
                                            @else
                                                <span class="badge badge-pill badge-success"> Enabled </span>
                                            @endif
                                        </td>
                                        <td class="text-center"> {{ formatDate('d-m-Y h:i', $ar->updated_at) }} </td>
                                        <td>

                                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button"
                                                id="action_menu_{{ $ar->id }}" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                &#x22EE;
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="action_menu_{{ $ar->id }}">

                                                <a class="btn btn-edit text-white dropdown-item"
                                                    href="{{ route('admin.website.edit', $ar->id) }}">
                                                    <i class="fa fa-pencil"></i> Edit
                                                </a>
                                                <button class="btn btn-edit text-white delete-record dropdown-item"
                                                    data-id="{{ $ar->id }}" data-title="{{ $ar->name }}"
                                                    data-segment="website">
                                                    <i class="fa fa-trash fa-sm" aria-hidden="true"></i> Delete
                                                </button>
                                            </div>
                                        </td>

                                    </tr>
                                @empty
                                    <tr class="text-center">
                                        <td colspan="9">There is no role available.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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
