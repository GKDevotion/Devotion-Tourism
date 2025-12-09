
@extends('backend.layouts.master')

@section('title')
Client Company Management Page - Admin Panel
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
        <div class="col-md-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left d-none">Client Company</h4>
                <ul class="breadcrumbs pull-left m-2">
                    <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li><span>All Client Company Management</span></li>
                </ul>
            </div>
        </div>
        <div class="col-md-3 text-end">
            @if ( false && fetchSinglePermission( $auth, 'account-management', 'add') )
                <a class="btn btn-success text-white" href="{{ route('company-account-management-create', $company->id) }}">
                    <i class="fa fa-plus"></i> Client Company
                </a>
            @endif

            @if ( false && fetchSinglePermission( $auth, 'admin.company', 'view') )
                <a href="{{ route('admin.company.index' ) }}" class="btn btn-danger">
                    <i class="fa fa-arrow-left"></i> Company List
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
                    <h3 class="mt-2">'{{$company->name}}' Client Account Management</h3>
                </div>
                <div class="col-4 text-end mb-2">
                    @if ( fetchSinglePermission( $auth, 'account-management', 'add') )
                        <a class="btn btn-success text-white" href="{{ route('company-account-management-create', $company->id) }}">
                            <i class="fa fa-plus"></i> Client Company
                        </a>
                    @endif

                    @if ( fetchSinglePermission( $auth, 'admin.company', 'view') )
                        <a href="{{ route('admin.company.index' ) }}" class="btn btn-danger">
                            <i class="fa fa-arrow-left"></i> Company List
                        </a>
                    @endif
                </div>
            </div>
            <div class="card">
                <div class="table-card-body card-body">

                    <div class="data-tables">

                        @include('backend.layouts.partials.messages')

                        <div class="row" style="border-bottom: 1px dashed #ab8134; padding: 10px;">
                            <input type="hidden" name="company_name" id="company_name" value="{{ $company->name ?? 'client-company' }}">
                            <div class="col-md-3 text-center">
                                <button class="btn btn-primary" id="downloadClientCompanyCSV">
                                    <i class="fa fa-file-excel"></i> Excel
                                </button>
                                <button class="btn btn-primary" id="downloadClientCompanyPDF">
                                    <i class="fa fa-file-pdf-o"></i> PDF
                                </button>
                            </div>
                            <div class="col-md-9">
                                <form action="{{ url('client-company-upload-csv') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="company_id" id="company_id" value="{{ $company->id ?? 0 }}">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input type="file" class="form-control" name="excel_file" required>
                                        </div>
                                        <div class="col-md-2 text-center">
                                            <button type="submit" class="btn btn-success">Import Excel</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <table id="account-managements_index" class="text-center">
                            <thead id="account-managements" class="bg-light text-capitalize">
                                <tr>
                                    <th>#</th>
                                    <th>Company Code</th>
                                    <th>Company Name</th>
                                    <th>Status</th>
                                    <th>Created At</th>
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
            var table = $('#account-managements_index').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                dom: '<"row"<"col-md-8 text-left"l><"col-md-4 text-right"f>>' +
                    'rt' +
                    '<"row"<"col-md-6"i><"col-md-6"p>>', // Custom structure with multiple parameters
                buttons: [],//'excel', 'pdf'
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                pageLength: 10,
                ajax: {
                    url: "{{ route('company-account-management.ajaxIndex') }}",
                    type: 'GET',
                    data: function (d) {
                        d.cid = {{$company->id}}; // Pass company parameter
                        // d.iid = ""; // Pass industry parameter
                    }
                },
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                        orderable: true,
                        searchable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { data: 'code', name: 'code' },
                    { data: 'name', name: 'name' },
                    { data: 'status', name: 'status'},
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                createdRow: function (row, data, dataIndex) {
                    $(row).attr('id', 'row_' + data.id);// Assign a custom ID to the row
                    $(row).attr('class', 'account-managements_row');// Assign a custom Class to the row
                },
                language: {
                    emptyTable: "No data available in table"  // Custom message for empty table
                },
            });

            // Adjust the table width after the data is loaded
            table.on('xhr', function() {
                var data = table.ajax.json().data;

                $('#account-managements_index').css('width', '100%');
            });
        });
     </script>
@endsection
