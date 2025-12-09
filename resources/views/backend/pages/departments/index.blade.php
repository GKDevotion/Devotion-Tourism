
@extends('backend.layouts.master')

@section('title')
Department Page - Admin Panel
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
        <div class="col-md-7">
            <div class="breadcrumbs-area clearfix">
                {{-- <h4 class="page-title pull-left d-none">Department</h4> --}}
                <ul class="breadcrumbs pull-left m-2">
                    <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li><span>All Department</span></li>
                </ul>
            </div>
        </div>
        <div class="col-md-3 text-end">
            @if (Auth::guard('admin')->user()->can('department.edit'))
                <a class="btn btn-success text-white" href="{{ route('admin.department.create') }}">
                    <i class="fa fa-plus"></i> Department
                </a>
            @endif
        </div>
        @include('backend.layouts.partials.header-menu')
    </div>
</div>
<!-- page title area end -->

<div class="main-content-inner">
    <div class="row">
        <!-- data table start -->
        <div class="col-12 mt-3">
            <h3 class="pb-3">Department History</h3>
            <div class="card">
                <div class="card-body">

                    <div class="data-tables">

                        @include('backend.layouts.partials.messages')

                        <div class="row" style="border: 1px dashed #ab8134; padding: 10px; margin-bottom: 15px;">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="department_name" placeholder="Department Name">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select class="form-control" id="company_id">
                                        <option value="">All Company</option>
                                        @foreach( $companies as $data )
                                            <option value="{{$data->id}}">{{$data->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select class="form-control" id="industry_id">
                                        <option value="">All Industry</option>
                                        @foreach( $industries as $data )
                                            <option value="{{$data->id}}">{{$data->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select class="form-control" id="status">
                                        <option value="">All Status</option>
                                        <option value="1">Active</option>
                                        <option value="0">Suspended</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-1 text-center">
                                <button class="btn btn-secondary m-0 p-2" id="viewDepartmentPDF"><i class="fa fa-eye"></i> PDF</button>
                            </div>
                        </div>

                        <table id="department_index">
                            <thead id="department" class="bg-light text-capitalize">
                                <tr>
                                    <th>Sr</th>
                                    <th>Name</th>
                                    <th>Admin</th>
                                    <th>Industry</th>
                                    <th>Company</th>
                                    <th>URL</th>
                                    <th>Status</th>
                                    <th>Update Date</th>
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
            var table = $('#department_index').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                dom: '<"row"<"col-md-4"B><"col-md-4 text-left"l><"col-md-4 text-right"f>>' +
                    'rt' +
                    '<"row"<"col-md-6"i><"col-md-6"p>>', // Custom structure with multiple parameters
                buttons: ['excel', 'pdf'],
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                pageLength: 10,
                ajax: "{{ route('department.ajaxIndex') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'admin', name: 'admin' },
                    { data: 'industry', name: 'industry' },
                    { data: 'company', name: 'company' },
                    { data: 'url', name: 'url' },
                    { data: 'status', name: 'status' },
                    { data: 'updated_at', name: 'updated_at' },
                    { data: 'action', name: 'action' },
                ],
                createdRow: function (row, data, dataIndex) {
                    $(row).attr('id', 'row_' + data.id);// Assign a custom ID to the row
                    $(row).attr('class', 'department_row');// Assign a custom Class to the row
                },
                language: {
                    emptyTable: "No data available in table"  // Custom message for empty table
                },
            });

            // Adjust the table width after the data is loaded
            table.on('xhr', function() {
                var data = table.ajax.json().data;

                if (data.length === 0) {
                    $('#department_index').css('width', '100%');
                } else {
                    $('#department_index').css('width', 'auto');
                }
            });
        });

     </script>
@endsection
