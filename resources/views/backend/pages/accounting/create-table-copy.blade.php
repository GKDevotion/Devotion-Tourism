
@extends('backend.layouts.master')

@section('title')
Create {{$company->name}} Account Summery - Admin Panel
@endsection

@section('styles')

    <style>
        .form-check-label {
            text-transform: capitalize;
        }

        .select2-container--default .select2-selection--single {
            border: 1px solid rgba(170, 170, 170, .3); /* Green border */
            height: 40px;              /* Custom height */
            border-radius: var(--bs-border-radius);        /* Optional: rounded corners */
        }

        /* Adjust the vertical alignment of the selected text */
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 40px;
        }

        .select2-selection{
            height: 45px;
        }

        .fa-refresh{
            cursor: pointer;
        }
    </style>
@endsection

@section('admin-content')

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-md-7">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left d-none">Company Account Create</h4>
                <ul class="breadcrumbs pull-left m-2">
                    <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.company.index') }}">All Company</a></li>
                    <li><span>Create {{$company->name}} Account</span></li>
                </ul>
            </div>
        </div>
        <div class="col-md-3">
            <p class="float-end">
                @if ( fetchSinglePermission( $auth, 'accounting', 'view') )
                    <a href="{{ route('company-account-management-index', $company->id) }}" class="btn btn-danger">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                @endif

                @if ( fetchSinglePermission( $auth, 'admin.company', 'view') )
                    <a href="{{ route('admin.company.index' ) }}" class="btn btn-danger">
                        <i class="fa fa-arrow-left"></i> Com. List
                    </a>
                @endif
            </p>
        </div>
        <div class="col-md-2 clearfix">
            @include('backend.layouts.partials.logout')
        </div>
    </div>
</div>
<!-- page title area end -->

<div class="main-content-inner">
    <div class="row">
        <!-- data table start -->
        <div class="col-12 mt-3">
            <h3 class="pb-3">Create {{$company->name}} Account</h3>
            <div class="card">
                <div class="card-body">

                    <form action="{{ route('admin.accounting.store') }}" id="submitAccountingForm" method="POST" autocomplete="off" enctype="multipart/form-data">
                        <!-- onsubmit="return onSubmitAccountValidateForm();" -->
                        @csrf
                        <input type="hidden" name="company_id" value="{{$company->id}}">

                        <div id="past_summery_div">
                            <table class="table">
                                <thead>
                                    <tr>
                                        @foreach ( $accountManagementFieldObj as $tbl )
                                            @if( $tbl->type == "dropdown" )
                                                <th style="">
                                                    @if( $tbl->slug == "company_code" )
                                                        Company Name
                                                    @else
                                                        {{$tbl->name}}
                                                    @endif
                                                    @if( $tbl->required )
                                                        <span class="text-error">*</span>
                                                    @endif
                                                </th>
                                            @endif

                                            @if( $tbl->type == "date" )
                                                <th style="">
                                                    {{$tbl->name}}
                                                    @if( $tbl->required )
                                                        <span class="text-error">*</span>
                                                    @endif
                                                </th>
                                            @endif

                                            @if( $tbl->type == "textarea" )
                                                <th style="">
                                                    {{$tbl->name}}
                                                    @if( $tbl->required )
                                                        <span class="text-error">*</span>
                                                    @endif
                                                </th>
                                            @endif

                                            @if( $tbl->type == "float" )
                                                <th style="">
                                                    {{$tbl->name}}
                                                    @if( $tbl->required )
                                                        <span class="text-error">*</span>
                                                    @endif
                                                </th>
                                            @endif

                                            @if( $tbl->type == "document" )
                                                <th style="">
                                                    {{$tbl->name}}
                                                    <i class="fa fa-info-circle" aria-hidden="true" data-bs-toggle="tooltip" data-bs-placement="top" title="( accept only JPG, PNG, JPEG,PDF )"></i>
                                                    @if( $tbl->required )
                                                        <span class="text-error">*</span>
                                                    @endif
                                                </th>
                                            @endif

                                        @endforeach
                                        <th style="">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="past_summery_div_table">
                                    <tr>
                                        @foreach ( $accountManagementFieldObj as $tbl )
                                            @if( $tbl->type == "dropdown" )
                                                <td>
                                                    <select class="form-control select2" id="{{$tbl->slug}}" name="summery[{{$tbl->slug}}][]" {{$tbl->required ? "data-required=yes" : "" }} >
                                                        @if( $tbl->slug == "payment_type" )
                                                            <option value="0">Cash</option>
                                                            @foreach ( getPaymentType( $company->id ) as $bank )
                                                                <option value="{{$bank->id}}">{{$bank->bank_name}}</option>
                                                            @endforeach
                                                        @elseif( $tbl->slug == "company_code" )
                                                            @foreach ( getClientCompany($company->id) as $com )
                                                                <option value="{{$com->id}}">{{$com->name}} ({{$com->code}})</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    @error($tbl->name)
                                                        <div class="error text-error">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            @endif

                                            @if( $tbl->type == "date" )
                                                <td>
                                                    <input type="date" {{$tbl->required ? "data-required=yes" : "" }} class="form-control default-date" id="{{$tbl->slug}}" name="summery[{{$tbl->slug}}][]" placeholder="{{$tbl->name}}" value="{{date('d/m/Y')}}" >
                                                    @error($tbl->name)
                                                        <div class="error text-error">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            @endif

                                            @if( $tbl->type == "textarea" )
                                                <td>
                                                    <textarea {{$tbl->required ? "data-required=yes" : "" }} class="form-control" id="{{$tbl->slug}}" name="summery[{{$tbl->slug}}][]" placeholder="{{$tbl->name}}"></textarea>
                                                    @error($tbl->name)
                                                        <div class="error text-error">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            @endif

                                            @if( $tbl->type == "float" )
                                                <td>
                                                    <input type="number" step="any" min="0" {{$tbl->required ? "data-required=yes" : "" }} class="form-control readonly-oposite-amount-type" data-amount-type="{{$tbl->slug}}" id="{{$tbl->slug}}_0" data-number="0" name="summery[{{$tbl->slug}}][]" placeholder="{{$tbl->name}}" value="0.00">
                                                    @error($tbl->name)
                                                        <div class="error text-error">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            @endif

                                            @if( $tbl->type == "document" )
                                                <td>
                                                    <input type="file" {{$tbl->required ? "data-required=yes" : "" }} class="form-control accounting-document" id="{{$tbl->slug}}" name="summery[{{$tbl->slug}}][]" placeholder="{{$tbl->name}}" accept="image/*,application/pdf" data-height="100">
                                                    @error($tbl->name)
                                                        <div class="error text-error">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            @endif
                                        @endforeach
                                        <td></td>
                                    </tr>
                                </tbody>
                                <tfoot class="d-none">
                                    <tr id="copy_summery_div_table">
                                        @foreach ( $accountManagementFieldObj as $tbl )
                                            @if( $tbl->type == "dropdown" )
                                                <td>
                                                    <select {{$tbl->required ? "data-required=yes" : "" }} class="form-control select2" id="{{$tbl->slug}}" name="summery[{{$tbl->slug}}][]" >
                                                        @if( $tbl->slug == "payment_type" )
                                                            <option value="0">Cash</option>
                                                            @foreach ( getPaymentType( $company->id ) as $bank )
                                                                <option value="{{$bank->id}}">{{$bank->bank_name}}</option>
                                                            @endforeach
                                                        @elseif( $tbl->slug == "company_code" )
                                                            @foreach ( getClientCompany($company->id) as $com )
                                                                <option value="{{$com->id}}">{{$com->name}} ({{$com->code}})</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    @error($tbl->name)
                                                        <div class="error text-error">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            @endif

                                            @if( $tbl->type == "date" )
                                                <td>
                                                    <input type="date" {{$tbl->required ? "data-required=yes" : "" }} class="form-control default-date" id="{{$tbl->slug}}" name="summery[{{$tbl->slug}}][]" placeholder="{{$tbl->name}}" value="{{date('d/m/Y')}}" >
                                                    @error($tbl->name)
                                                        <div class="error text-error">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            @endif

                                            @if( $tbl->type == "textarea" )
                                                <td>
                                                    <textarea {{$tbl->required ? "data-required=yes" : "" }} class="form-control" id="{{$tbl->slug}}" name="summery[{{$tbl->slug}}][]" placeholder="{{$tbl->name}}"></textarea>
                                                    @error($tbl->name)
                                                        <div class="error text-error">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            @endif

                                            @if( $tbl->type == "float" )
                                                <td>
                                                    <input type="number" step="any" min="0" {{$tbl->required ? "data-required=yes" : "" }} class="form-control readonly-oposite-amount-type" data-amount-type="{{$tbl->slug}}" id="{{$tbl->slug}}" name="summery[{{$tbl->slug}}][]" placeholder="{{$tbl->name}}" value="0.00">
                                                    @error($tbl->name)
                                                        <div class="error text-error">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            @endif

                                            @if( $tbl->type == "document" )
                                                <td>
                                                    <input type="file" {{$tbl->required ? "data-required=yes" : "" }} class="form-control accounting-document" id="{{$tbl->slug}}" name="summery[{{$tbl->slug}}][]" placeholder="{{$tbl->name}}" accept="image/*,application/pdf" data-height="100">
                                                    @error($tbl->name)
                                                        <div class="error text-error">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            @endif
                                        @endforeach

                                        <td>
                                            <div class="col-md-12 text-center">
                                                <button type="button" class="btn btn-success" onclick="removeSummeryDiv($(this))" id="remove_summery" style="min-width: 10px;">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12 text-center">
                                <button type="button" class="btn btn-success pr-4 pl-4" id="add_more_summery">
                                    <i class="fa fa-plus"></i> Add More
                                </button>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12 text-center">

                                @if ( fetchSinglePermission( $auth, 'accounting', 'add') )
                                    <button type="submit" class="btn btn-success pr-4 pl-4" id="submitForm">
                                        <i class="fa fa-save"></i> Save
                                    </button>
                                @endif

                                @if ( fetchSinglePermission( $auth, 'accounting', 'view') )
                                    <a href="{{ route('company-account-management-index', $company->id) }}" class="btn btn-danger pr-4 pl-4">
                                        <i class="fa fa-arrow-left"></i> Back
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- data table end -->

    </div>
</div>
@endsection

@section('scripts')

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).on( "ready", function() {

            const dropifyClass = ["accounting-document"];
            $( dropifyClass ).each(function( index, className ) {

                if( $('.'+className).length > 0){
                    $('.'+className).dropify();
                }
            });

            const today = new Date().toISOString().split('T')[0];
            $('.default-date').val( today );

            // $('.select2').prop('disabled', false).trigger('change.select2');
            // $('.select2').select2();

            var accountSummery = 0;
            $("#add_more_summery").on( "click", function(){
                accountSummery++;
                const original = document.getElementById("copy_summery_div_table");
                const clone = original.cloneNode(true); // deep clone with content

                // Set new unique id
                clone.id = `remove_summery_${accountSummery}_div`;

                // Optionally update class attribute (e.g., add a new class or pass dynamic value)
                clone.className = `template a${accountSummery}`; // dynamic class "a1", "a2", etc.

                const child = clone.querySelector("#remove_summery"); // Get the first child with class "child"
                child.id = `remove_summery_${accountSummery}`; // Update ID dynamically

                // Get the child Credit / Debit with id "credit_amount / debit_amount", Add a new attribute: data-number
                const creditAmountChild = clone.querySelector("#credit_amount");
                creditAmountChild.setAttribute("data-number", accountSummery);
                creditAmountChild.id = `credit_amount_${accountSummery}`; // Update ID dynamically
                const debitAmountChild = clone.querySelector("#debit_amount");
                debitAmountChild.setAttribute("data-number", accountSummery);
                debitAmountChild.id = `debit_amount_${accountSummery}`; // Update ID dynamically

                // // Get the refresh child Credit / Debit with id Add a new attribute: data-number
                // const creditRefreshChild = clone.querySelector(".refresh-credit_amount");
                // creditRefreshChild.setAttribute("data-number", accountSummery);
                // const debitRefreshChild = clone.querySelector(".refresh-debit_amount");
                // debitRefreshChild.setAttribute("data-number", accountSummery);

                // Append the cloned div to the container
                document.getElementById("past_summery_div_table").appendChild(clone);
            });

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });

        $(document).on( "keypress", ".readonly-oposite-amount-type", function(){
            let dataAmountType = $(this).attr('data-amount-type');
            let dataNumber = $(this).attr('data-number')

            if( dataAmountType == "credit_amount" ){
                // $("#debit_amount_"+dataNumber).attr( "readonly", true );
                $("#debit_amount_"+dataNumber).val( "0.00" );
                // $("#credit_amount_"+dataNumber).attr( "readonly", false );
            } else {
                // $("#debit_amount_"+dataNumber).attr( "readonly", false );
                // $("#credit_amount_"+dataNumber).attr( "readonly", true );
                $("#credit_amount_"+dataNumber).val( "0.00" );
            }
        } );

        function removeSummeryDiv( obj ){
            $("#"+obj.attr('id')+"_div" ).remove();
        };

        function onSubmitAccountValidateForm(){
            let isValid = true;

            $('[data-required="yes"]').each(function () {
                let $field = $(this);
                let value = $field.val();

                // Special case for Select2
                if ($field.hasClass('select2-hidden-accessible')) {
                    value = $field.val();
                }

                if (!value || $.trim(value) === '') {
                    $field.css('border', '1px solid red');
                    isValid = false;
                } else {
                    $field.css('border', '1px solid #ccc');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Please fill all required fields.');
            }

            alert( isValid );
            return isValid;
        }

        $('#submitAccountingForm').on('submit', function (e) {
            let isValid = true;
            let firstInvalidField = null;

            $('#submitAccountingForm [data-required="yes"]').each(function () {
                let $field = $(this);
                let value = $field.val();

                if ($field.hasClass('select2-hidden-accessible')) {
                    value = $field.val();
                }

                if (!value || $.trim(value) === '') {
                    $field.css('border', '1px solid red');
                    if (!firstInvalidField) {
                        firstInvalidField = $field;
                    }
                    isValid = false;
                }
                // else {
                //     $field.css('border', '1px solid #ccc');
                // }
            });

            if (!isValid) {
                e.preventDefault();

                // Scroll to first invalid field
                $('html, body').animate({
                    scrollTop: firstInvalidField.offset().top - 50
                }, 500);

                // Focus for input and textarea
                if (firstInvalidField.is('input, textarea')) {
                    firstInvalidField.focus();
                }

                // Open select2 dropdown if needed
                if (firstInvalidField.hasClass('select2-hidden-accessible')) {
                    firstInvalidField.select2('open');
                }
            }
        });

        $(document).on( "click", ".fa-refresh", function(){
            let dataNumber = $(this).attr('data-number');
            $("#debit_amount_"+dataNumber).removeAttr( "readonly" ).val("0.00");
            $("#credit_amount_"+dataNumber).removeAttr( "readonly" ).val("0.00");
        } );
    </script>
@endsection
