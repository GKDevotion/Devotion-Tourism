
@extends('backend.layouts.master')

@section('title')
City Page - Admin Panel
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
                {{-- <h4 class="page-title pull-left d-none">City</h4> --}}
                <ul class="breadcrumbs pull-left m-2">
                    <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li><span>All City</span></li>
                </ul>
            </div>
        </div>
        <div class="col-md-2 text-end">
            @if (Auth::guard('admin')->user()->can('city.create'))
                <a class="btn btn-success text-white" href="{{ route('admin.city.create') }}">
                    <i class="fa fa-plus"></i> City
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
        <div class="col-12 mt-3">
            <h3 class="pb-3">City History</h3>
            <div class="card">
                <div class="card-body">

                    <div class="data-tables">
                        @include('backend.layouts.partials.messages')
                        <table id="city_index" class="text-center">
                            <thead id="city" class="bg-light text-capitalize">
                                <tr>
                                    <th width="1%">Sr</th>
                                    <th width="20%">Name</th>
                                    <th width="5%">State</th>
                                    <th width="2%">Country</th>
                                    <th width="2%">Continent</th>
                                    <th width="5%">Latitude</th>
                                    <th width="5%">Longitude</th>
                                    <th width="2%">Status</th>
                                    <th width="3%">Update At</th>
                                    <th width="3%">Action</th>
                                </tr>
                            </thead>
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
            $('#city_index').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                dom: '<"row"<"col-md-4"B><"col-md-4 text-left"l><"col-md-4 text-right"f>>' +
                    'rt' +
                    '<"row"<"col-md-6"i><"col-md-6"p>>', // Custom structure with multiple parameters
                buttons: ['excel', 'pdf'],
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                pageLength: 10,
                ajax: "{{ route('city.ajaxIndex') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'state_name', name: 'state_name' },
                    { data: 'country_name', name: 'country_name' },
                    { data: 'continent_name', name: 'continent_name' },
                    { data: 'latitude', name: 'latitude' },
                    { data: 'longitude', name: 'longitude' },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'updated_at', name: 'updated_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });
        });
     </script>
@endsection
