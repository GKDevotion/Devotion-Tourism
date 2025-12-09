
@extends('backend.layouts.master')

@section('title')
Log History Page - Admin Panel
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
                {{-- <h4 class="page-title pull-left d-none">Admin Log</h4> --}}
                <ul class="breadcrumbs pull-left m-2">
                    <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li><span>All Admin Log</span></li>
                </ul>
            </div>
        </div>
        <div class="col-md-1 text-end">
            @if (Auth::guard('admin')->user()->can('admin-log.edit'))
                <a class="btn btn-success text-white" href="{{ route('admin.admin-log.create') }}">
                    <i class="fa fa-plus"></i> Admin Log
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
            <h3 class="pb-3">Admin Log History</h3>
            <div class="card">
                <div class="card-body">
                   <div class="data-tables">
                        @include('backend.layouts.partials.messages')

                        <div class="row" style="border-bottom: 1px dashed #ab8134; padding: 10px;">
                            <form action="{{url('admin/logs')}}" method="get">
                                <div class="col-md-8 col-sm-12 col-12">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-12 col-12">
                                            <div class="form-group">
                                                <label for="from_date">From Date : (eg. 01-01-2023)</label>
                                                <input type="date" class="form-control" id="from_date" name="from_date" placeholder="From Date : (eg. 01-01-2023)" max="" value="{{$request->from_date}}">
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-12 col-12">
                                            <div class="form-group">
                                                <label for="to_date">To Date : (eg. 31-01-2023)</label>
                                                <input type="date" class="form-control" id="to_date" name="to_date" placeholder="To Date : (eg. 31-01-2023)" max="" value="{{$request->to_date}}">
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-12 col-12">
                                            <div class="form-group">
                                                <label for="to_date">User</label>
                                                <select id="uid" name="uid" class="form-control select">
                                                    <option value="">Select User</option>
                                                    @foreach ( $user as $ar )
                                                        <option value="{{$ar->id}}" {{$request->uid == $ar->id ? 'selected' : ''}}>{{$ar->username}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-12 col-12">
                                            <button class="btn btn-primary" style="margin-top: 32px !important; padding: 10px;" type="submit">
                                                <i class="fa fa-search"></i> Search
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <table id="admin_log_index" class="text-center w-100">
                            <thead id="admin_log" class="bg-light text-capitalize">
                                <tr>
                                    <th>Sr</th>
                                    <th>User Name</th>
                                    <th>Information</th>
                                    <th>Description</th>
                                    <th>IP Address</th>
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

<style>
    .modal-dialog{
        max-width: 70% !important;
    }
</style>
<div class="modal fade" id="differentDescriptionModal" tabindex="-1" role="dialog" aria-labelledby="differentSummeryDescriptionModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center" id="differentSummeryDescriptionModal">Show Different</h5>
                <button type="button" class="btn btn-danger close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12" id="show_different_tbl">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
     <!-- Start datatable js -->
     <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
     <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
     <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
     <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
     <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>

     <script>
        $(document).ready(function() {
            var table = $('#admin_log_index').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                // dom: '<"row"<"col-md-4"B><"col-md-4 text-left"l><"col-md-4 text-right"f>>' +
                //     'rt' +
                //     '<"row"<"col-md-6"i><"col-md-6"p>>', // Custom structure with multiple parameters
                buttons: [],//'excel', 'pdf'
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                pageLength: 10,
                // ajax: "{{ route('admin-log.ajaxIndex') }}",
                ajax: {
                    url: "{{ route('admin-log.ajaxIndex' ) }}",
                    type: 'GET',
                    data: function (d) {
                        d.uid = "{{$request->uid}}"; // Pass company parameter
                        d.from_date = "{{$request->from_date}}"; // Pass industry parameter
                        d.to_date = "{{$request->to_date}}"; // Pass industry parameter
                    }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'information', name: 'information' },
                    { data: 'description', name: 'description' },
                    { data: 'ip_address', name: 'ip_address' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action' },
                ]
            });

            // // Adjust the table width after the data is loaded
            // table.on('xhr', function() {
            //     var data = table.ajax.json().data;

            //     // if (data.length === 0) {
            //         $('#admin_log_index').css('width', '100%');
            //     // } else {
            //     //     $('#admin_log_index').css('width', 'auto');
            //     // }
            // });

            $(document).on( "click", ".show-difference", function(){
                $.ajax({
                    url: url+"/api/get-admin-log-different-details/"+$(this).attr( 'data-id' ),
                    method: 'GET',

                    // Show loader before the request
                    beforeSend: function() {
                        $("#preloader").show();
                    },

                    // Process the response data on success
                    success: function (response) {
                        $("#show_different_tbl").html( response.data );
                        showToast(response.message);
                    },

                    // Hide the loader regardless of success or failure
                    complete: function() {
                        $("#preloader").hide();
                    },

                    // Optional: Handle errors
                    error: function (xhr) {
                        showToast(xhr.responseJSON.message);
                    }
                });
            } );
        });

     </script>
@endsection
