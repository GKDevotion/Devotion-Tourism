
@extends('backend.layouts.master')

@section('title')
Create {{$company->name}} Account Summery - Admin Panel
@endsection

@section('styles')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
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

    .form-control{
        padding: 5px;
    }

    .select2-container--default .select2-selection--single:focus,
    .select2-container--default .select2-selection--single.select2-selection--focus {
        border-color: #9b6e1a !important; /* Change to your desired color */
        outline: none;
    /* box-shadow: 0 0 0 1px rgba(0, 123, 255, 0.25); Optional highlight */
    }
    .dropify-wrapper.focused {
        border-color: #9b6e1a !important; /* Change to your desired color */
    }

    .dropify-clear, .dropify-error {
        display: none;
    }

    .dropify-wrapper{
        width: 40%;
    }

    .dz-default.dz-message {
  display: none;
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
                {{-- <h4 class="page-title pull-left d-none">Company Account Create</h4> --}}
                <ul class="breadcrumbs pull-left m-2">
                    <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.company.index') }}">All Company</a></li>
                    <li><span>Create {{$company->name}} Account</span></li>
                </ul>
            </div>
        </div>
        <div class="col-md-2">
            <p class="float-end">
                @if ( false && fetchSinglePermission( $auth, 'accounting', 'view') )
                    <a href="{{ route('company-account-management-index', $company->id) }}" class="btn btn-danger">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                @endif

                @if ( false && fetchSinglePermission( $auth, 'admin.company', 'view') )
                    <a href="{{ route('admin.company.index' ) }}" class="btn btn-danger">
                        <i class="fa fa-arrow-left"></i> Com. List
                    </a>
                @endif
            </p>
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
                    <h3 class="pb-3">Create {{$company->name}} Account</h3>
                </div>
                <div class="col-4 text-end mb-2">
                    @if ( fetchSinglePermission( $auth, 'accounting', 'view') )
                        <a href="{{ route('company-account-management-index', $company->id) }}" class="btn btn-danger">
                            <i class="fa fa-arrow-left"></i> Back
                        </a>
                    @endif

                    @if ( fetchSinglePermission( $auth, 'admin.company', 'view') )
                        <a href="{{ route('admin.company.index' ) }}" class="btn btn-danger">
                            <i class="fa fa-arrow-left"></i> Comp. List
                        </a>
                    @endif
                </div>
            </div>
            <div class="card">
                <div class="card-body">

                    <?php
                    $adminAccess = [1, 3];
                    $checkIndex = 1;
                    if( in_array( $auth->admin_user_group_id, $adminAccess ) ){
                        $checkIndex = 0;
                    }
                    ?>
                    <div class="d-none" id="copy_summery_div">
                        <div class="row box-shadow-10">
                            @foreach ( $accountManagementFieldObj as $k=>$tbl )
                                @if( $tbl->type == "dropdown" )
                                    <div class="col-md-3 col-sm-12 col-12 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="{{$tbl->slug}}">
                                                {{$tbl->name}}
                                                @if( $tbl->required )
                                                    <span class="text-error">*</span>
                                                @endif
                                            </label>

                                            <select {{$tbl->required ? "data-required=yes" : "" }} class="form-control select2 {{$k == $checkIndex ? 'autofocus' : ''}}" id="{{$tbl->slug}}" name="summery[{{$tbl->slug}}][]">
                                                <option value="">Select {{$tbl->name}}</option>
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
                                        </div>
                                        @error($tbl->name)
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif

                                @if( $tbl->type == "date" )
                                    <div class="col-md-2 col-sm-12 col-12 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="{{$tbl->slug}}">
                                                {{$tbl->name}}
                                                @if( $tbl->required )
                                                    <span class="text-error">*</span>
                                                @endif
                                            </label>
                                            <input type="date" {{$tbl->required ? "data-required=yes" : "" }} class="form-control default-date {{$k == $checkIndex ? 'autofocus' : ''}}" id="{{$tbl->slug}}" name="summery[{{$tbl->slug}}][]" placeholder="{{$tbl->name}}" value="{{date('d/m/Y')}}" {{ in_array( $auth->admin_user_group_id, $adminAccess ) ? '' : 'readonly' }}>
                                        </div>
                                        @error($tbl->name)
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif

                                @if( $tbl->type == "textarea" )
                                    <div class="col-md-3 col-sm-12 col-12 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="{{$tbl->slug}}">
                                                {{$tbl->name}}
                                                @if( $tbl->required )
                                                    <span class="text-error">*</span>
                                                @endif
                                            </label>
                                            <textarea {{$tbl->required ? "data-required=yes" : "" }} class="form-control {{$k == $checkIndex ? 'autofocus' : ''}}" id="{{$tbl->slug}}" name="summery[{{$tbl->slug}}][]" placeholder="{{$tbl->name}}"></textarea>
                                        </div>
                                        @error($tbl->name)
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif

                                @if( $tbl->type == "float" )
                                    <div class="col-md-2 col-sm-12 col-12 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="{{$tbl->slug}}">
                                                {{$tbl->name}}
                                                @if( $tbl->required )
                                                    <span class="text-error">*</span>
                                                @endif
                                            </label>
                                            <?php
                                            $resetName = "";
                                            if( $tbl->slug == "credit_amount" ){
                                                $resetName = "debit_amount";
                                            } else if( $tbl->slug == "debit_amount" ){
                                                $resetName = "credit_amount";
                                            }
                                            ?>
                                            <input type="number" step="any" min="0" {{$tbl->required ? "data-required=yes" : "" }} class="form-control {{$k == $checkIndex ? 'autofocus' : ''}}" data-amount-type="{{$tbl->slug}}" id="{{$tbl->slug}}" name="summery[{{$tbl->slug}}][]" placeholder="{{$tbl->name}}" value="0.00" oninput="resetOther( this, '{{$resetName}}')" >
                                        </div>
                                        @error($tbl->name)
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif

                                @if( $tbl->type == "document" )
                                    <div class="col-md-3 col-sm-12 col-12 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="{{$tbl->slug}}">
                                                {{$tbl->name}}
                                                @if( $tbl->required )
                                                    <span class="text-error">*</span>
                                                @endif
                                            </label>
                                            <input type="file" {{$tbl->required ? "data-required=yes" : "" }} class="form-control  dropify accounting-document" id="{{$tbl->slug}}" name="summery[{{$tbl->slug}}][]" placeholder="{{$tbl->name}}" data-height="50" multiple accept=".jpg, .jpeg, .png, .pdf" data-default-file="">
                                        </div>
                                        @error($tbl->name)
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif
                            @endforeach

                            <div class="col-md-12 text-center">
                                <button type="button" class="btn btn-success pr-4 pl-4" onclick="removeSummeryDiv($(this))" id="remove_summery">
                                    <i class="fa fa-minus"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.accounting.store') }}" id="submitAccountingForm" method="POST" autocomplete="off" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="company_id" value="{{$company->id}}">
                        <input type="hidden" name="form_token" value="{{ Str::uuid() }}">

                        <div id="past_summery_div">
                            <div class="row box-shadow-10" id="remove_summery_0_div">
                                @foreach ( $accountManagementFieldObj as $k=>$tbl )
                                    @if( $tbl->type == "dropdown" )
                                        <div class="col-md-3 col-sm-12 col-12 mb-2">
                                            <div class="form-group">
                                                <label class="mb-0" for="{{$tbl->slug}}">
                                                    {{$tbl->name}}
                                                    @if( $tbl->required )
                                                        <span class="text-error">*</span>
                                                    @endif
                                                </label>

                                                <select {{$tbl->required ? "data-required=yes" : "" }} class="form-control select2" id="{{$tbl->slug}}" name="summery[{{$tbl->slug}}][]" {{$k == $checkIndex ? 'autofocus' : ''}}>
                                                    <option value="">Select {{$tbl->name}}</option>
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
                                            </div>
                                            @error($tbl->name)
                                                <div class="error text-error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endif

                                    @if( $tbl->type == "date" )
                                        <div class="col-md-2 col-sm-12 col-12 mb-2">
                                            <div class="form-group">
                                                <label class="mb-0" for="{{$tbl->slug}}">
                                                    {{$tbl->name}}
                                                    @if( $tbl->required )
                                                        <span class="text-error">*</span>
                                                    @endif
                                                </label>
                                                <input type="date" {{$tbl->required ? "data-required=yes" : "" }} class="form-control default-date" id="{{$tbl->slug}}" name="summery[{{$tbl->slug}}][]" placeholder="{{$tbl->name}}" value="{{date('d/m/Y')}}" {{ in_array( $auth->admin_user_group_id, $adminAccess ) ? '' : 'readonly' }} {{$k == $checkIndex ? 'autofocus' : ''}} >
                                            </div>
                                            @error($tbl->name)
                                                <div class="error text-error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endif

                                    @if( $tbl->type == "textarea" )
                                        <div class="col-md-3 col-sm-12 col-12 mb-2">
                                            <div class="form-group">
                                                <label class="mb-0" for="{{$tbl->slug}}">
                                                    {{$tbl->name}}
                                                    @if( $tbl->required )
                                                        <span class="text-error">*</span>
                                                    @endif
                                                </label>
                                                <textarea {{$tbl->required ? "data-required=yes" : "" }} class="form-control" id="{{$tbl->slug}}" name="summery[{{$tbl->slug}}][]" placeholder="{{$tbl->name}}" {{$k == $checkIndex ? 'autofocus' : ''}}></textarea>
                                            </div>
                                            @error($tbl->name)
                                                <div class="error text-error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endif

                                    @if( $tbl->type == "float" )
                                        <div class="col-md-2 col-sm-12 col-12 mb-2">
                                            <div class="form-group">
                                                <label class="mb-0" for="{{$tbl->slug}}">
                                                    {{$tbl->name}}
                                                    @if( $tbl->required )
                                                        <span class="text-error">*</span>
                                                    @endif
                                                </label>
                                                <?php
                                                $resetName = "";
                                                if( $tbl->slug == "credit_amount" ){
                                                    $resetName = "debit_amount";
                                                } else if( $tbl->slug == "debit_amount" ){
                                                    $resetName = "credit_amount";
                                                }
                                                ?>
                                                <input type="number" step="any" min="0" {{$tbl->required ? "data-required=yes" : "" }} class="form-control disabled-oposite-amount-type" data-amount-type="{{$tbl->slug}}" id="{{$tbl->slug}}_0" number="0" name="summery[{{$tbl->slug}}][]" placeholder="{{$tbl->name}}" value="0.00" oninput="resetOther( this, '{{$resetName}}')" {{$k == $checkIndex ? 'autofocus' : ''}}>
                                            </div>
                                            @error($tbl->name)
                                                <div class="error text-error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endif

                                    @if( $tbl->type == "document" )
                                        <div class="col-md-3 col-sm-12 col-12 mb-2">
                                            <div class="form-group">
                                                <label class="mb-0" for="{{$tbl->slug}}">
                                                    {{$tbl->name}}
                                                    @if( $tbl->required )
                                                        <span class="text-error">*</span>
                                                    @endif
                                                </label>
                                                <input type="file" {{$tbl->required ? "data-required=yes" : "" }} class="form-control dropify accounting-document" id="{{$tbl->slug}}" name="summery[{{$tbl->slug}}][0][]" placeholder="{{$tbl->name}}" data-height="50" multiple accept=".jpg, .jpeg, .png, .pdf">
                                            </div>
                                            @error($tbl->name)
                                                <div class="error text-error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endif
                                @endforeach

                                <div class="col-md-12 text-center">
                                    <button type="button" class="btn btn-success pr-4 pl-4" onclick="removeSummeryDiv($(this))" id="remove_summery_0">
                                        <i class="fa fa-minus"></i> Delete
                                    </button>
                                </div>
                            </div>
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
                                    <button type="submit" class="btn btn-success pr-4 pl-4" id="submitFormBTN">
                                        <i class="fa fa-save"></i> Save
                                    </button>
                                @endif

                                @if ( fetchSinglePermission( $auth, 'accounting', 'view') )
                                    <a href="{{ route('company-account-summery-index', $company->id) }}" id="backBTN" class="btn btn-danger pr-4 pl-4">
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

<style>
    #ajaxBlocker{
        display:none;
        position:fixed;
        top:0;
        left:0;
        width:100%;
        height:100%;
        background:rgba(0,0,0,0.2);
        z-index:99999;
        backdrop-filter: blur(1.5px);
        cursor: wait;
    }
</style>

<div id="ajaxBlocker"></div>

@endsection
@section('scripts')

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>

    // document.getElementById('submitAccountingForm').addEventListener('submit', function () {
    //     document.getElementById('submitFormBTN').disabled = true;
    //     document.getElementById('backBTN').disabled = true;
    // });

    $(document).on( "ready", function() {

        // Add focus style when tabbing into the Dropify input
        $(document).on('focus', '.dropify', function () {
            $(this).closest('.dropify-wrapper').addClass('focused');
        });

        $(document).on('blur', '.dropify', function () {
            $(this).closest('.dropify-wrapper').removeClass('focused');
        });

        setDatePicker();
        // const today = new Date().toISOString().split('T')[0];
        // $('.default-date').val( today ).attr('max', today).datepicker({ dateFormat: 'yy-mm-dd' });

        // $('.select2').select2();
        $('#remove_summery_0_div .select2').select2();

        // Auto open on focus
        $('.select2').on('focus', function() {
            $(this).select2('open');
        });

        // Also support tabbing into the field
        $(document).on('focus', '.select2-selection', function(e) {
            const select = $(this).closest('.select2-container').prev('select');
            select.select2('open');
        });

        initDropify();
    });

    var accountSummery = 0;
    $(document).on( "click", "#add_more_summery", function( e ){

        if( onSubmitAccountValidateForm( e, false ) ){
            accountSummery++;

            if( accountSummery > 30 ){
                Swal.fire({
                    icon: 'warning',
                    title: 'Limit Reached',
                    text: 'You can add only up to 10 rows.',
                    confirmButtonText: 'OK'
                });
            } else {
                // $("#past_summery_div").append( $("#copy_summery_div").html() );
                const original = document.getElementById("copy_summery_div");
                const clone = original.cloneNode(true); // deep clone with content

                // Set new unique id
                clone.id = `remove_summery_${accountSummery}_div`;

                // Optionally update class attribute (e.g., add a new class or pass dynamic value)
                clone.className = `template a${accountSummery}`; // dynamic class "a1", "a2", etc.

                const child = clone.querySelector("#remove_summery"); // Get the first child with class "child"
                child.id = `remove_summery_${accountSummery}`; // Update ID dynamically

                // Get the child Credit / Debit with id "credit_amount / debit_amount", Add a new attribute: number
                const creditAmountChild = clone.querySelector("#credit_amount");
                creditAmountChild.setAttribute("number", accountSummery);
                creditAmountChild.id = `credit_amount_${accountSummery}`; // Update ID dynamically
                const debitAmountChild = clone.querySelector("#debit_amount");
                debitAmountChild.setAttribute("number", accountSummery);
                debitAmountChild.id = `debit_amount_${accountSummery}`; // Update ID dynamically

                // Get the refresh child Credit / Debit with id Add a new attribute: number
                const documentMultiple = clone.querySelector("#document");
                documentMultiple.setAttribute("name", 'summery[document]['+accountSummery+'][]');
                //documentMultiple.setAttribute("class", 'summery_'+accountSummery);

                // Append the cloned div to the container
                document.getElementById("past_summery_div").appendChild(clone);

                $('#remove_summery_'+accountSummery+'_div .select2').select2();

                // Cleanup Dropify DOM wrappers inside the clone
                $(clone).find('.dropify-wrapper').each(function () {
                    const $wrapper = $(this);
                    const $input = $wrapper.find('input[type="file"]');
                    $input.unwrap(); // remove dropify-wrapper
                });

                // Append to container
                $('#past_summery_div').append(clone);

                // Re-initialize Dropify
                initDropify();
                // $('.accounting-document').last().dropify();

                $('#remove_summery_'+accountSummery+'_div .default-date').focus();
            }
        }
        // setDatePicker();
    });

    function initDropify(){
        // const dropifyClass = ["accounting-document"];
        // $( dropifyClass ).each(function( index, className ) {

        //     if( $('.'+className).length > 0){
        //         $('.'+className).dropify();
        //     }
        // });
        $('.accounting-document').dropify({
            messages: {
                'default': '', // Custom text
                'replace': '',
                'remove':  'Remove',
                'error':   'Oops, something wrong happened.'
            }
        });

        // Remove Dropify wrappers if present in the clone
        // $(clone).find('.dropify-wrapper').each(function () {
        //     const $wrapper = $(this);
        //     const $input = $wrapper.find('input[type="file"]');

        //     // Replace dropify wrapper with the original input
        //     $input.unwrap();
        // });
    }

    function removeSummeryDiv( obj ){
        $("#"+obj.attr('id')+"_div" ).remove();
    };

    function onSubmitAccountValidateForm( e, check ){

        let isValid = true;
        let firstInvalidField = null;

        $(".form-control").css('border', '1px solid rgba(170, 170, 170, .3)');
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
        } else if(check) {
            // document.getElementById('submitFormBTN').disabled = true;
            // document.getElementById('backBTN').disabled = true;
            // Disable or hide the Back button
            const backBtn = document.getElementById('backBTN');
            backBtn.style.pointerEvents = 'none';  // prevent clicks
            backBtn.style.opacity = '0.5';         // make it look disabled
            // OR to completely hide:
            // backBtn.style.display = 'none';

            // Optionally, disable the submit button too
            const submitBtn = document.getElementById('submitFormBTN');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processing...';

            $("#ajaxBlocker").show();  // ❌ Block all clicks
        }

        return isValid;
    }

    $('#submitAccountingForm').on('submit', function (e) {
        onSubmitAccountValidateForm(e, true);
    });

    function resetOther(element, oppositeType) {
        let dataNumber = element.getAttribute( 'number' );

        //Reset the opposite input
        const oppositeInputId = oppositeType + '_' + dataNumber;
        const oppositeInput = document.getElementById(oppositeInputId);

        if (oppositeInput) {
            oppositeInput.value = '0.00';
        }

    }

    function setDatePicker(){
        const today = new Date().toISOString().split("T")[0]; // Format: YYYY-MM-DD
        const dateInputs = document.querySelectorAll('.default-date');

        dateInputs.forEach(input => {
            input.max = today;
            input.value = today;

            // Prevent manually entering future dates
            input.addEventListener('change', function () {
                if (this.value > today) {
                    // alert("Future dates are not allowed.");
                    this.value = today;
                }
            });
        });
    }
</script>
@endsection
