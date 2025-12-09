
@extends('backend.layouts.master')

@section('title')
{{ ( $company_id ) ? $company->name : '' }} Accounting Summery - Admin Panel
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

        .bg-warning {
            background-color: #ffed4a !important;
        }

        .bg-success{
            background-color: #198754 !important;
        }
        tbody{
            font-size: 13px;
        }

        .dropdown-toggle{
            min-width: 50px !important;
        }

        .crm-update{
            font-size: 12px;
            width: 70px;
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
                {{-- <h4 class="page-title pull-left d-none">Accounting</h4> --}}
                <ul class="breadcrumbs pull-left m-2">
                    <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.company.index') }}">All Company</a></li>
                    <li><span>All {{ ( $company_id ) ? $company->name : '' }} Accounting Summery</span></li>
                </ul>
            </div>
        </div>
        <div class="col-md-2 text-end">
            @if ( false && fetchSinglePermission( $auth, 'accounting', 'add') )
                <a class="btn btn-success text-white" href="{{ route('create-company-account-summery', $company->id) }}">
                    <i class="fa fa-plus"></i> Summery
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
                    <h3 class="mt-2">{{ ( $company_id ) ? $company->name : '' }} Account Hisotry</h3>
                </div>
                <div class="col-4 text-end mb-2">
                     @if ( fetchSinglePermission( $auth, 'accounting', 'add') )
                        <a class="btn btn-success text-white" href="{{ route('create-company-account-summery', $company->id) }}">
                            <i class="fa fa-plus"></i> Summery
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
                            <div class="col-md-6 col-sm-12 col-12">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12 col-12">
                                        <div class="form-group">
                                            <label for="from_date">From Date : (eg. 01-01-2023)</label>
                                            <input type="date" class="form-control" id="from_date" placeholder="From Date : (eg. 01-01-2023)" max="" value="{{$from_date}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-12">
                                        <div class="form-group">
                                            <label for="to_date">To Date : (eg. 31-01-2023)</label>
                                            <input type="date" class="form-control" id="to_date" placeholder="To Date : (eg. 31-01-2023)" max="" value="{{$to_date}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-12">
                                        <div class="form-group">
                                            <label for="to_date">Payment Type</label>
                                            <select id="payment_type" class="form-control select payment-type-multiple" multiple>
                                                <option value="">Select Type</option>
                                                @foreach ( $bankInfoObj as $k=>$bank )
                                                    @if( $k==0 )
                                                        <option value="0" {{in_array( 0 , explode( ',', $paymentType )) ? 'selected' : ''}} >Cash</option>
                                                    @endif
                                                    <option value="{{$bank->id}}" {{in_array( $bank->id , explode( ',', $paymentType )) ? 'selected' : ''}}>{{$bank->bank_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-2 text-center offset-md-3">
                                    <div class="col-md-3 col-sm-12 col-12">
                                        <button class="btn btn-primary" id="downloadAccountSummeryCSVFile">
                                            <i class="fa fa-file-excel"></i> Excel
                                        </button>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-12">
                                        <button class="btn btn-secondary" id="downloadAccountSummeryPDFFile">
                                            <i class="fa fa-file-pdf"></i> PDF
                                        </button>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-12 d-none">
                                        <button class="btn btn-secondary" id="viewAccountSummery">
                                            <i class="fa fa-eye"></i> View
                                        </button>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-12">
                                        <button class="btn btn-secondary" id="searchAccountSummery">
                                            <i class="fa fa-search"></i> Search
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12 col-12">
                                <form class="d-none" action="{{ url('account-summery-upload-csv') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="company_id" id="company_id" value="{{ $company_id ?? 0 }}">
                                    <div class="row">
                                        <div class="col-md-10 col-sm-12 col-12">
                                            <input type="file" class="form-control" name="excel_file" required>
                                        </div>
                                        <div class="col-md-2 col-sm-12 col-12 text-center">
                                            <button type="submit" class="btn btn-success">Import Excel</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <?php
                        $debitAmountColumn = 1;
                        $balanceColumn = 2;
                        ?>
                        <script>
                            let accountManagementFieldObj = [];
                        </script>
                        <table id="accounting_index" class="text-center display nowrap w-100">
                            <thead id="accounting" class="bg-light text-capitalize">
                                <tr>
                                    {{-- <th style="width: 2%">#</th> --}}
                                    <th style="">Action</th>
                                    <th style="">TXN No.</th>
                                    <th style="">UserName</th>
                                    @foreach ( $accountManagementFieldObj as $th )
                                        <?php
                                        $width = "";
                                        $balanceColumn++;
                                        if( $th->slug == "debit_amount" ){
                                            $debitAmountColumn+= $balanceColumn;
                                        }

                                        if( $th->slug == "description" ){
                                            $width = "width: 250px";
                                        }

                                        if($th->slug == "remarks" ){
                                            $width = "width: 110px";
                                        }
                                        ?>
                                        <th style="{{$width}}">{{$th->name}}</th>
                                        <script>
                                            accountManagementFieldObj.push( '{{$th->slug }}' );
                                        </script>
                                    @endforeach
                                    <th style="">Balance</th>
                                    <?php
                                    $balanceColumn++;// = $debitAmountColumn;
                                    ?>
                                    <th style="">Updated At</th>

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
<div class="modal fade" id="accountDescriptionModal" tabindex="-1" role="dialog" aria-labelledby="accountSummeryDescriptionModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center" id="accountSummeryDescriptionModal"><snap id="select_TXN_no"></snap> Description</h5>
                <button type="button" class="btn btn-danger close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12" id="select_TXN_description">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #ajaxBlocker{
        display:none;
        position:fixed;
        top:0; left:0;
        width:100%; height:100%;
        background:rgba(0,0,0,0.2);
        z-index:99999;
        backdrop-filter: blur(2px);
        cursor: wait;
    }
</style>

<div id="ajaxBlocker"></div>

@endsection


@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @include('backend.layouts.partials.data-table')

    <script src="https://cdn.datatables.net/colreorder/1.5.0/js/dataTables.colReorder.min.js"></script>

     <script>
        window.addEventListener('DOMContentLoaded', function () {
            const select = document.getElementById('payment_type');

            // Select first options
            if (select.options.length > 0) {
            select.options[0].selected = true;
            // select.options[1].selected = true;
            }
        });

        $(document).ready(function() {
            $(".select").select2();

            let currentPage = {{ $currentPage ?? 1 }}; // from server or default to 1
            let pageSize = 10; // or your configured length

            let columns = [
                // { data: 'id', name: 'id' },
                // { data: 'company', name: 'company' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
                { data: 'txn_no', name: 'txn_no' },
                { data: 'admin', name: 'admin' },
            ];

            // Dynamically add fields from `accountManagementFieldObj`
            accountManagementFieldObj.forEach(function(val) {
                columns.push({ data: val, name: val });
            });

            // Add remaining static columns
            columns.push(
                // { data: 'status', name: 'status' },
                { data: 'balance', name: 'balance' },
                { data: 'updated_at', name: 'updated_at' }
            );

            var table = $('#accounting_index').DataTable({
                columnDefs: [
                    {
                        orderable: false,
                        targets: "_all",
                        // targets: [0, 3] //on 1st and 4th columns
                    } // disables sorting
                ],
                processing: true,
                serverSide: true,
                responsive: true,
                colReorder: true,
                scrollX: true,
                responsive: false,
                dom: '<"row"<"col-md-8 text-left"l><"col-md-4 text-right"f>>' +
                    'rt' +
                    '<"row"<"col-md-6"i><"col-md-6"p>>', // Custom structure with multiple parameters
                buttons: [],
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                displayStart: ( currentPage - 1) * pageSize, // ✅ Load proper records
                pageLength: pageSize,
                ajax: {
                    url: "{{ route('accouting.ajaxIndex') }}",
                    type: 'GET',
                    data: function (d) {

                        // const table = $('#accounting_index').DataTable();
                        // const currentPage = Math.floor(d.start / d.length);// + 1;
                        d.cid = '{{$company_id}}'; // Pass company parameter
                        d.paymentType = '{{ $paymentType }}'; // Pass industry parameter
                        d.from_date = '{{ $from_date }}'; // pass from_date page
                        d.to_date = '{{ $to_date }}'; // pass to_date page
                    }
                },
                columns: columns,
                createdRow: function (row, data, dataIndex) {
                    $(row).attr('id', 'row_' + data.id);// Assign a custom ID to the row
                    $(row).attr('class', 'accounting_row');// Assign a custom Class to the row
                },
                language: {
                    emptyTable: "No data available in table"  // Custom message for empty table
                },
                initComplete: function () {
                    // Set page to 3rd (index 2)
                    this.api().page({{ $currentPage - 1 }}).draw(false);
                },
            });

            table.colReorder.move( 2, 1); // Move "Date" column after "User Name"
            table.colReorder.move( {{$balanceColumn}}, {{$debitAmountColumn}}); // Move "Balance" column after "Debit Amount"

            // Adjust the table width after the data is loaded
            table.on('xhr', function() {
                var data = table.ajax.json().data;
                $('#accounting_index').css('width', '100%');
            });

            // Delete record with confirmation
            $(document).on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                var title = $(this).data('title');
                var segment = $(this).data('segment');

                bootbox.confirm({
                    message: "Are you sure you want to  delete this <b><u>'"+title+"</u>'.</b> ?",
                    buttons: {
                        confirm: {
                            label: 'Yes',
                            className: 'btn-success'
                        },
                        cancel: {
                            label: 'No',
                            className: 'btn-danger'
                        }
                    },
                    callback: function (result) {
                        if( result ) {
                            $.ajax({
                                url: url + '/admin/'+segment+'/'+id,
                                type: "DELETE",
                                data: {},
                                dataType: 'json',
                                // 🔵 SHOW LOADER BEFORE AJAX STARTS
                                beforeSend: function() {
                                    $("#ajaxBlocker").show();  // ❌ Block all clicks
                                },
                                success: function(result) {
                                    $("#preloader").hide();
                                    if( result.data.status == 201 ){
                                        showToast( result.data.message, "success");
                                    } else {
                                        table.ajax.reload(); // Refresh DataTable
                                        showToast( result.data.message, "success");
                                    }
                                },
                                error: function() {
                                    showToast("Something went wrong!", "error");
                                },
                                complete: function () {
                                    $("#ajaxBlocker").hide(); // ✅ Unblock page
                                }
                            });
                        } else {
                            // dataTable.ajax.reload();
                            showToast( "Entry was reverted", "warning");
                        }
                    }
                });
            });
        });

        const today = new Date().toISOString().split('T')[0];
        document.getElementById('from_date').setAttribute('max', today);
        document.getElementById('to_date').setAttribute('max', today);
        // $('#to_date, #from_date').val( today );

        $(document).on( "click", ".get-txn-details", function(){
            $("#select_TXN_no").text( $(this).attr( 'data-txnno' ) );
            $("#select_TXN_description").html( $(this).attr( 'data-description' ) );
        } );
     </script>
@endsection
