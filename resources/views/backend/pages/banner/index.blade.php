@extends('backend.layouts.master')

@section('title')
    Banner - Admin Panel
@endsection

@section('styles')
    <!-- Start datatable css -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
    <style>

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
                    {{-- <h4 class="page-title pull-left d-none">Configuration</h4> --}}
                    <ul class="breadcrumbs pull-left m-2">
                        <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                        <li><span>All Banner</span></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-2 text-end">
                <a class="btn btn-success text-white" href="{{ route('admin.banner.create') }}">
                    <i class="fa fa-plus"></i> Banner
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
            <div class="col-12 mt-3">
                <h3 class="pb-3">Banner History</h3>
                <div class="card">
                    <div class="card-body">

                        <div class="data-tables">
                            @include('backend.layouts.partials.messages')
                            <table id="dataTable" class="table table-bordered table-striped display responsive nowrap">
                                <thead id="banners" class="bg-light text-capitalize">
                                    <tr>
                                        <th width="1%">Sr</th>
                                        <th width="3%">image</th>
                                        <th width="5%">Title</th>
                                        <th width="5%">Sub Title</th>
                                        <th width="2%">Status</th>
                                        <th width="3%">Update At</th>
                                        <th width="3%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($banners as $key => $banner)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>

                                            <td>
                                                <img src="{{ $banner->image
                                                    ? asset('storage/app/banner/' . $banner->image)
                                                    : asset('public/img/devotion-group-favicon.png') }}"
                                                    width="100%" style="object-fit:cover" alt="{{ $banner->name }}">
                                            </td>

                                            <td>{{ $banner->name }}</td>

                                            <td>{{ $banner->sub_title }}</td>
                                            <td>
                                                @if (true)
                                                    <i class="fa fa-{{ $banner->status == 0 ? 'times' : 'check' }} update-status"
                                                        data-status="{{ $banner->status }}" data-id="{{ $banner->id }}"
                                                        aria-hidden="true" data-table="banners"></i>
                                                @else
                                                    <select
                                                        class="form-control update-status badge {{ $banner->status == 0 ? 'bg-warning' : 'bg-success' }} text-white"
                                                        name="status" data-id="{{ $banner->id }}" data-table="banners">
                                                        <option value="1"
                                                            {{ $banner->status == 1 ? 'selected' : '' }}>
                                                            Active</option>
                                                        <option value="0"
                                                            {{ $banner->status == 0 ? 'selected' : '' }}>
                                                            De-Active</option>
                                                    </select>
                                                @endif
                                            </td>

                                            <td>{{ formatDate('Y-m-d H:i', $banner->updated_at) }}</td>

                                            <td>

                                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button"
                                                    id="action_menu_{{ $banner->id }}" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    &#x22EE;
                                                </button>
                                                <div class="dropdown-menu"
                                                    aria-labelledby="action_menu_{{ $banner->id }}">

                                                    <a class="btn btn-edit text-white dropdown-item"
                                                        href="{{ route('admin.banner.edit', $banner->id) }}">
                                                        <i class="fa fa-pencil"></i> Edit
                                                    </a>
                                                    <button class="btn btn-edit text-white delete-record dropdown-item"
                                                        data-id="{{ $banner->id }}" data-title="{{ $banner->name }}"
                                                        data-segment="banner">
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
    @include('backend.layouts.partials.data-table')

    <script>
        /*================================
                datatable active
                ==================================*/
        if ($('#dataTable').length) {
            $('#dataTable').DataTable({
                responsive: true,
                dom: '<"row"<"col-md-4"B><"col-md-4 text-left"l><"col-md-4 text-right"f>>' +
                    'rt' +
                    '<"row"<"col-md-6"i><"col-md-6"p>>', // Custom structure with multiple parameters
                buttons: ['excel', 'pdf'],
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "All"]
                ],
                pageLength: 10,
                columnDefs: [{
                        responsivePriority: 1,
                        targets: 0
                    }, // Sr
                    {
                        responsivePriority: 2,
                        targets: 1
                    }, // Unique ID
                    {
                        responsivePriority: 3,
                        targets: 2
                    }, // Invoice
                    {
                        responsivePriority: 4,
                        targets: 3
                    }, // Amount
                    {
                        responsivePriority: 5,
                        targets: 4
                    }, // Serial No
                    {
                        responsivePriority: 6,
                        targets: 5
                    }, // Purchase Order

                    // All others move to "+" expandable view
                    {
                        responsivePriority: 10001,
                        targets: [6, 7, 8, 9, 10, 11]
                    }
                ]
            });

            $('#dataTable').css("width", "100%");
        }
    </script>
@endsection
