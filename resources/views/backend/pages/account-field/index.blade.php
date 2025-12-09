
@extends('backend.layouts.master')

@section('title')
Account Management Fields Page - Admin Panel
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
        <div class="col-sm-8">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left d-none">Account Management Field</h4>
                <ul class="breadcrumbs pull-left m-2">
                    <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li><span>All Account Management Fields</span></li>
                </ul>
            </div>
        </div>
        <div class="col-sm-1 text-end">
            @if ( false && fetchSinglePermission( $auth, 'account-field', 'add') )
                <a class="btn btn-success text-white" href="{{ route('admin.account-field.create') }}">
                    <i class="fa fa-plus"></i> Field
                </a>
            @endif
        </div>
        <div class="col-sm-1">
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
                    <h3 class="mt-2">Account Management Field Hisotry</h3>
                </div>
                <div class="col-4 text-end mb-2">
                    @if ( fetchSinglePermission( $auth, 'account-field', 'add') )
                        <a class="btn btn-success text-white" href="{{ route('admin.account-field.create') }}">
                            <i class="fa fa-plus"></i> Field
                        </a>
                    @endif
                </div>
            </div>
            <div class="card">
                <div class="table-card-body card-body">

                    <div class="data-tables">

                        @include('backend.layouts.partials.messages')

                        <table id="account_field_index" class="text-center">
                            <thead id="account-field" class="bg-light text-capitalize">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Required</th>
                                    <th>Status</th>
                                    <th>Updated At</th>
                                    <th>Action</th>
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
            var table = $('#account_field_index').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                // dom: '<"row"<"col-md-4"B><"col-md-4 text-left"l><"col-md-4 text-right"f>>' +
                //     'rt' +
                //     '<"row"<"col-md-6"i><"col-md-6"p>>', // Custom structure with multiple parameters
                buttons: [],//'excel', 'pdf'
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                pageLength: 10,
                ajax: {
                    url: "{{ route('account-field.ajaxIndex') }}",
                    type: 'GET',
                    data: function (d) {
                        // d.cid = ""; // Pass company parameter
                        // d.iid = ""; // Pass industry parameter
                    }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'type', name: 'type' },
                    { data: 'required', name: 'required' },
                    { data: 'status', name: 'status'},
                    { data: 'updated_at', name: 'updated_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                createdRow: function (row, data, dataIndex) {
                    $(row).attr('id', 'row_' + data.id);// Assign a custom ID to the row
                    $(row).attr('class', 'locations_row');// Assign a custom Class to the row
                },
                language: {
                    emptyTable: "No data available in table"  // Custom message for empty table
                },
            });

            // Adjust the table width after the data is loaded
            table.on('xhr', function() {
                var data = table.ajax.json().data;

                $('#account_field_index').css('width', '100%');
            });
        });
     </script>
@endsection
