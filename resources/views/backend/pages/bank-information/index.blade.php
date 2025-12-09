
@extends('backend.layouts.master')

@section('title')
Bank Account Management - Admin Panel
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
                <h4 class="page-title pull-left d-none">Bank Account</h4>
                <ul class="breadcrumbs pull-left m-2">
                    <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.company.index') }}">All Company</a></li>
                    <li><span>All Bank Account</span></li>
                </ul>
            </div>
        </div>
        <div class="col-md-2 text-end">
            @if ( false && fetchSinglePermission( $auth, 'bank-information', 'add') )
                <a class="btn btn-success text-white" href="{{ route('company-bank-information-create', $company->id) }}">
                    <i class="fa fa-plus"></i> Bank
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
                <div class="col-8 mt-2">
                    <h3 class="pb-3">'{{$company->name}}' Bank Account Management</h3>
                </div>
                <div class="col-4 mb-2 text-end">
                    @if ( fetchSinglePermission( $auth, 'bank-information', 'add') )
                        <a class="btn btn-success text-white" href="{{ route('company-bank-information-create', $company->id) }}">
                            <i class="fa fa-plus"></i> Bank
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

                        <table id="bank-information_index" class="text-center">
                            <thead id="bank-information" class="bg-light text-capitalize">
                                <tr>
                                    <th>#</th>
                                    <th>Bank Name</th>
                                    <th>A/C No.</th>
                                    <th>A/C Name</th>
                                    <th>IBAN</th>
                                    <th>Branch Code</th>
                                    <th>Currency</th>
                                    <th>Status</th>
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
            var table = $('#bank-information_index').DataTable({
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
                    url: "{{ route('bank-information.ajaxIndex') }}",
                    type: 'GET',
                    data: function (d) {
                        d.cid = {{$company->id}}; // Pass company parameter
                        // d.iid = ""; // Pass industry parameter
                    }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'bank_name', name: 'bank_name' },
                    { data: 'account_number', name: 'account_number' },
                    { data: 'holder_name', name: 'holder_name' },
                    { data: 'iban', name: 'iban' },
                    { data: 'branch_code', name: 'branch_code'},
                    { data: 'currency', name: 'currency' },
                    { data: 'status', name: 'status'},
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                createdRow: function (row, data, dataIndex) {
                    $(row).attr('id', 'row_' + data.id);// Assign a custom ID to the row
                    $(row).attr('class', 'bank-information_row');// Assign a custom Class to the row
                },
                language: {
                    emptyTable: "No data available in table"  // Custom message for empty table
                },
            });

            // Adjust the table width after the data is loaded
            table.on('xhr', function() {
                var data = table.ajax.json().data;

                $('#bank-information_index').css('width', '100%');
            });
        });
     </script>
@endsection
