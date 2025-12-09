
@extends('backend.layouts.master')

@section('title')
Company Management - Admin Panel
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
                {{-- <h4 class="page-title pull-left d-none">Company</h4> --}}
                <ul class="breadcrumbs pull-left m-2">
                    <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li><span>All Company</span></li>
                </ul>
            </div>
        </div>
        <div class="col-md-2 text-end">
            @if ( fetchSinglePermission( $auth, 'admin.company', 'add') )
                <a class="btn btn-success text-white" href="{{ route('admin.company.create') }}">
                    <i class="fa fa-plus"></i> Company
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
            <h3 class="pb-3">Company Management</h3>
            <div class="card">
                <div class="table-card-body card-body">

                    <div class="data-tables">

                        @include('backend.layouts.partials.messages')

                        <table id="companies_index">
                            <thead id="companies" class="bg-light text-capitalize">
                                <tr>
                                    <th>Sr</th>
                                    {{-- <th>Logo</th> --}}
                                    <th>Name</th>
                                    <th>Contact</th>
                                    <th>Email ID</th>
                                    <th>Currency</th>
                                    {{-- <th>Sort Name</th> --}}
                                    <th>Address</th>
                                    <th>Status</th>
                                    <th>Assign User</th>
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

<style>
    .modal-dialog.modal-dialog-centered{
        max-width: 85%;
    }
</style>

@endsection

@section('scripts')

    @include('backend.layouts.partials.data-table')

     <script>

        $(document).ready(function() {
            var table = $('#companies_index').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                // dom: '<"row"<"col-md-4"B><"col-md-4 text-left"l><"col-md-4 text-right"f>>' +
                //     'rt' +
                //     '<"row"<"col-md-6"i><"col-md-6"p>>', // Custom structure with multiple parameters
                buttons: [],//'excel', 'pdf'
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                pageLength: 10,
                // ajax: "{{ route('company.ajaxIndex') }}",
                ajax: {
                    url: "{{ route('company.ajaxIndex' ) }}",
                    type: 'GET',
                    data: function (d) {
                        d.cid = "{{$request->cid}}"; // Pass company parameter
                        d.iid = "{{$request->iid}}"; // Pass industry parameter
                    }
                },
                columns: [
                    {
                        data: 'id', // no actual field
                        name: 'id',
                        orderable: true,
                        searchable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    // { data: 'id', name: 'id' },
                    // {
                    //     data: 'logo',
                    //     render: function (data, type, row, meta) {
                    //         return '<img src="'+row.logo+'" title="'+row.website_link+'">'; // Auto-increment based on row index
                    //     }
                    // },
                    {
                        data: 'name',
                        render: function (data, type, row) {
                            return `<a href="${row.website_link}" target="_blank">${row.name}</a>`;
                        },
                        orderable: false, // Prevent sorting on this column
                        searchable: true // Prevent searching on this column
                    },
                    { data: 'contact_number', name: 'contact_number' },
                    { data: 'email_id', name: 'email_id' },
                    { data: 'currency', name: 'currency' },
                    // { data: 'sort_name', name: 'sort_name' },
                    { data: 'address', name: 'address' },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    // { data: 'updated_at', name: 'updated_at' },
                    { data: 'assign_user', name: 'assign_user' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                createdRow: function (row, data, dataIndex) {
                    $(row).attr('id', 'row_' + data.id);// Assign a custom ID to the row
                    $(row).attr('class', 'companies_row');// Assign a custom Class to the row
                },
                language: {
                    emptyTable: "No data available in table"  // Custom message for empty table
                },
            });

            // Adjust the table width after the data is loaded
            table.on('xhr', function() {
                var data = table.ajax.json().data;

                // if (data.length === 0) {
                    $('#companies_index').css('width', '100%');
                // } else {
                //     $('#companies_index').css('width', 'auto');
                // }
            });
        });

        /**
         *
         */
        $(document).on('click', '.metting-record', function () {
            $(".company-id").text( $(this).attr('data-id') );
            $(".company-id").val( $(this).attr('data-id') );

            if ( $.fn.dataTable.isDataTable( '#company_meeting_index' ) ) {
                $('#company_meeting_index').DataTable().destroy();
            }

            company_meeting_index = $('#company_meeting_index').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                dom: '<"row"<"col-md-4"B><"col-md-4 text-left"l><"col-md-4 text-right"f>>' +
                    'rt' +
                    '<"row"<"col-md-6"i><"col-md-6"p>>', // Custom structure with multiple parameters
                buttons: ['excel', 'pdf'],
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                pageLength: 10,
                ajax: $(".get-ajax-meeting-list-url").text(),
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'communication_type', name: 'communication_type' },
                    { data: 'title', name: 'title' },
                    { data: 'date', name: 'date' },
                    { data: 'description', name: 'description'},
                    { data: 'follow_up_date', name: 'follow_up_date' },
                    { data: 'follow_up_detail', name: 'follow_up_detail'},
                    { data: 'created_at', name: 'created_at' },
                    // { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                language: {
                    emptyTable: "No data available in table"  // Custom message for empty table
                },
            });

            // Adjust the table width after the data is loaded
            company_meeting_index.on('xhr', function() {
                var data = company_meeting_index.ajax.json().data;

                if (data.length === 0) {
                    $('#company_meeting_index').css('width', '100%');
                } else {
                    $('#company_meeting_index').css('width', 'auto');
                }
            });

            // Validate all input fields
            $("input[data-required='yes']").each(function() {
                $(this).removeClass("error-border");
            });

            // Validate all textareas
            $("textarea[data-required='yes']").each(function() {
                $(this).siblings(".error").text("");
            });

            // Validate all select dropdowns
            $("select[data-required='yes']").each(function() {
                $(this).siblings(".error").text("");
            });
        });

        /**
         *
         */
        if( $('#companyMeetingSubmitForm').length > 0){
            var company_meeting_index;
            $('#companyMeetingSubmitForm').on('submit', function(e) {
                e.preventDefault();
                var isValid = true;

                // Validate all input fields
                $("input[data-required='yes']").each(function() {
                    if ($(this).val().trim() === "") {
                        // $(this).next(".error").text("This field is required.");
                        $(this).addClass("error-border");
                        isValid = false;
                    } else {
                        // $(this).next(".error").text("");
                        $(this).removeClass("error-border");
                    }
                });

                // Validate all textareas
                $("textarea[data-required='yes']").each(function() {
                    if ($(this).val().trim() === "") {
                        // $(this).next(".error").text("This field is required.");
                        // $(this).addClass("error-border");
                        $(this).siblings(".error").text("Please write some information.");
                        isValid = false;
                    } else {
                        // $(this).next(".error").text("");
                        // $(this).removeClass("error-border");
                        $(this).siblings(".error").text("");
                    }
                });

                // Validate all select dropdowns
                $("select[data-required='yes']").each(function() {
                    console.log( "> "+$(this).val() );
                    if ($(this).val() == "" || $(this).val() == 0 || $(this).val() == null) {
                        $(this).siblings(".error").text("Please select an option.");
                        // $(this).siblings().addClass("error-border");
                        isValid = false;
                    } else {
                        // $(this).removeClass("error-border");
                        $(this).siblings(".error").text("");
                        // $(this).next(".error").text("");
                    }
                });

                // If any field is invalid, prevent form submission
                if (!isValid) {
                    return false;
                }

                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    success: function(response) {
                        // Handle success response
                        $("#meeting_div_form").trigger("click");
                        showToast( response.message );
                        company_meeting_index.ajax.reload();
                    },
                    error: function(xhr) {
                        // Handle error response
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = "";//'<div class="alert alert-danger">';
                        $.each(errors, function(key, value) {
                            errorMessage += value[0] + ', ';
                        });

                        showToast( errorMessage );

                    }
                });
            });
        }
     </script>
@endsection
