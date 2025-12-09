
@extends('backend.layouts.master')

@section('title')
Notice Board Page - Admin Panel
@endsection

@section('styles')
    <!-- Start datatable css -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
    <style>
        .notice_board_row{
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
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                {{-- <h4 class="page-title pull-left d-none">Notice Board</h4> --}}
                <ul class="breadcrumbs pull-left m-2">
                    <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li><span>All Notice Board</span></li>
                </ul>
            </div>
        </div>
        <div class="col-md-3 text-end">
            @if (Auth::guard('admin')->user()->can('notice-board.create'))
                <a class="btn btn-success text-white" href="{{ route('admin.notice-board.create') }}">
                    <i class="fa fa-plus"></i> Notice
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
            <h3 class="pb-3">Notice Board History</h3>
            <div class="card">
                <div class="card-body">
                    <div class="data-tables">
                        @include('backend.layouts.partials.messages')
                        <table id="notice-board_index" class="text-center">
                            <thead id="notice-board" class="bg-light text-capitalize">
                                <tr>
                                    <th width="1%">Sr</th>
                                    <th width="8%">Type</th>
                                    <th width="20%">Description</th>
                                    <th width="5%">Date</th>
                                    <th width="10%">Notice By</th>
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
            var table = $('#notice-board_index').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                dom: '<"row"<"col-md-4"B><"col-md-4 text-left"l><"col-md-4 text-right"f>>' +
                    'rt' +
                    '<"row"<"col-md-6"i><"col-md-6"p>>', // Custom structure with multiple parameters
                buttons: ['excel', 'pdf'],
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                pageLength: 10,
                ajax: "{{ route('notice-board.ajaxIndex') }}",
                columns: [
                    {
                        data: 'id',
                        render: function (data, type, row, meta) {
                            return meta.row + 1; // Auto-increment based on row index
                        }
                    }, // Auto index { data: 'id', name: 'id' },
                    { data: 'type', name: 'type' },
                    { data: 'description', name: 'description' },
                    { data: 'date', name: 'date' },
                    { data: 'notice_by', name: 'notice_by' },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'updated_at', name: 'updated_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                createdRow: function (row, data, dataIndex) {
                    $(row).attr('id', 'row_' + data.id);// Assign a custom ID to the row
                    $(row).attr('class', 'notice-board_row');// Assign a custom Class to the row
                },
                language: {
                    emptyTable: "No data available in table"  // Custom message for empty table
                },
            });

            // Adjust the table width after the data is loaded
            table.on('xhr', function() {
                var data = table.ajax.json().data;

                if (data.length === 0) {
                    $('#notice-board_index').css('width', '100%');
                } else {
                    $('#notice-board_index').css('width', 'auto');
                }
            });
        });
     </script>
@endsection
