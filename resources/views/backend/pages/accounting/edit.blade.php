
@extends('backend.layouts.master')

@section('title')
Edit {{$company->name}} Account Summery - Admin Panel
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

    .fa-refresh{
        cursor: pointer;
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

    .dropify-clear {
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
                {{-- <h4 class="page-title pull-left d-none">Company Account Update</h4> --}}
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
                <div class="col-8">
                    <h3 class="mt-2">Update {{$company->name}} Account</h3>
                </div>
                <div class="col-4 text-end mb-2">
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
                </div>
            </div>
            <div class="card">
                <div class="card-body">

                    <?php
                    $adminAccess = [1, 3];
                    ?>
                    @foreach ( $accountManagementFieldObj as $tbl )
                        @if( $tbl->type == "document" )
                            <div id="copy_document_div" class="row d-none" >
                                <div class="col-md-3 col-sm-12 col-12 mb-2">
                                    <div class="form-group">
                                        <label class="mb-0" for="{{$tbl->slug}}">
                                            {{$tbl->name}}
                                            @if( $tbl->required )
                                                <span class="text-error">*</span>
                                            @endif
                                        </label>
                                        <input type="file" {{$tbl->required ? "data-required=yes" : "" }} class="form-control accounting-document" id="{{$tbl->slug}}" name="summery[{{$tbl->slug}}][]" placeholder="{{$tbl->name}}" data-height="80" accept=".jpg, .jpeg, .png, .pdf">
                                    </div>
                                    @error($tbl->name)
                                        <div class="error text-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @endif
                    @endforeach
                    <form action="{{ route('admin.accounting.update', $data->id) }}" method="POST" autocomplete="off" enctype="multipart/form-data" onsubmit="$('#ajaxBlocker').show();">
                        @method('PUT')
                        @csrf

                        <input type="hidden" name="currentPage" value="{{$currentPage }}">
                        <input type="hidden" name="company_id" value="{{$company->id}}">

                        <div class="row">
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

                                            <select {{$tbl->required ? "data-required=yes" : "" }} class="form-control select2" id="{{$tbl->slug}}" name="summery[{{$tbl->slug}}]" {{$k==0 ? 'autofocus' : ''}}>
                                                @if( $tbl->slug == "payment_type" )
                                                    <option value="0">Cash</option>
                                                    @foreach ( getPaymentType( $company->id ) as $bank )
                                                        <option value="{{$bank->id}}" {{$data->payment_type == $bank->id ? 'selected' : ''}} >{{$bank->bank_name}}</option>
                                                    @endforeach
                                                @elseif( $tbl->slug == "company_code" )
                                                    @foreach ( getClientCompany($company->id) as $com )
                                                        <option value="{{$com->id}}" {{$data->company_code == $com->id ? 'selected' : ''}} >{{$com->name}} ({{$com->code}})</option>
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
                                            <input type="date" {{$tbl->required ? "data-required=yes" : "" }} class="form-control default-date" id="{{$tbl->slug}}" name="summery[{{$tbl->slug}}]" placeholder="{{$tbl->name}}" value="{{formatDate( 'Y-m-d', $data->date )}}" {{ in_array( $auth->admin_user_group_id, $adminAccess ) ? '' : 'readonly' }} {{$k==0 ? 'autofocus' : ''}}>
                                        </div>
                                        @error($tbl->name)
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif

                                @if( $tbl->type == "textarea" )
                                    <div class="col-md-4 col-sm-12 col-12 mb-2">
                                        <div class="form-group">
                                            <label class="mb-0" for="{{$tbl->slug}}">
                                                {{$tbl->name}}
                                                @if( $tbl->required )
                                                    <span class="text-error">*</span>
                                                @endif
                                            </label>
                                            <?php
                                            $valTextarea = $tbl->slug;
                                            ?>
                                            <textarea {{$tbl->required ? "data-required=yes" : "" }} class="form-control" id="{{$tbl->slug}}" name="summery[{{$tbl->slug}}]" placeholder="{{$tbl->name}}" {{$k==0 ? 'autofocus' : ''}}>{{$data->$valTextarea ?? ''}}</textarea>
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
                                            $valFloat = $tbl->slug;
                                            $resetName = "";
                                            if( $tbl->slug == "credit_amount" ){
                                                $resetName = "debit_amount";
                                            } else if( $tbl->slug == "debit_amount" ){
                                                $resetName = "credit_amount";
                                            }
                                            ?>
                                            <input type="number" step="any" min="0" {{$tbl->required ? "data-required=yes" : "" }} class="form-control readonly-oposite-amount-type" data-amount-type="{{$tbl->slug}}" id="{{$tbl->slug}}_0" data-number="0" name="summery[{{$tbl->slug}}]" data-val="{{$data->$valFloat}}" placeholder="{{$tbl->name}}" value="{{$data->$valFloat}}" oninput="resetOther( this, '{{$resetName}}')">
                                        </div>
                                        @error($tbl->name)
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif

                                @if( $tbl->type == "document" )
                                    <div class="row" id="past_document_div">
                                        @if( $data->document )
                                            @foreach ( $data->upload_file as $file )
                                                <div class="col-md-3 col-sm-12 col-12 mb-2">
                                                    <div class="form-group">
                                                        <label class="mb-0" for="{{$tbl->slug}}">
                                                            {{$tbl->name}}
                                                            @if( $tbl->required )
                                                                <span class="text-error">*</span>
                                                            @endif
                                                        </label>
                                                        <input type="file" {{$tbl->required ? "data-required=yes" : "" }} data-default-file="{{url( 'storage/app/'.$file->path )}}" class="form-control accounting-document" id="{{$tbl->slug}}" name="summery[{{$tbl->slug}}][]" placeholder="{{$tbl->name}}" data-height="80" {{$k==0 ? 'autofocus' : ''}} accept=".jpg, .jpeg, .png, .pdf">
                                                    </div>
                                                    @error($tbl->name)
                                                        <div class="error text-error">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="col-md-3 col-sm-12 col-12 mb-2">
                                                <div class="form-group">
                                                    <label class="mb-0" for="{{$tbl->slug}}">
                                                        {{$tbl->name}}
                                                        @if( $tbl->required )
                                                            <span class="text-error">*</span>
                                                        @endif
                                                    </label>
                                                    <input type="file" {{$tbl->required ? "data-required=yes" : "" }} class="form-control accounting-document" id="{{$tbl->slug}}" name="summery[{{$tbl->slug}}][]" placeholder="{{$tbl->name}}" data-height="80" {{$k==0 ? 'autofocus' : ''}} accept=".jpg, .jpeg, .png, .pdf">
                                                </div>
                                                @error($tbl->name)
                                                    <div class="error text-error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-12 mb-2">
                                        <button type="button" class="btn btn-success pr-4 pl-4" id="add_more_documents">
                                            <i class="fa fa-plus"></i> Add More
                                        </button>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-success pr-4 pl-4" id="submitForm">
                                    <i class="fa fa-save"></i> Update
                                </button>
                                <a href="{{ route('company-account-summery-index', $company->id) }}" class="btn btn-danger pr-4 pl-4">
                                    <i class="fa fa-arrow-left"></i> Back
                                </a>
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
    $(document).on( "ready", function() {
        initDropify();
        $('.select2').select2();

    });

    // const today = new Date().toISOString().split('T')[0];
    // $('.default-date').attr('max', '{{$todayDate}}').datepicker({ dateFormat: 'yy-mm-dd' });;
    // Set max date to today and default value to today
    const dateInput = document.getElementById("date");
    const today = new Date().toISOString().split("T")[0]; // format YYYY-MM-DD
    dateInput.max = today;
    // dateInput.value = today;//'{{$todayDate}}';

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
        } else {
            $("#ajaxBlocker").show();  // ❌ Block all clicks
        }

        alert(isValid);
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
        } else {
            $("#ajaxBlocker").show();  // ❌ Block all clicks
        }

    });

    $(document).on( "click", "#add_more_documents", function(){
        var documentDiv = $('#copy_document_div').html();
        $('#past_document_div').append(documentDiv);
    });

    function initDropify(){
        $('.accounting-document').dropify();
    }

    function resetOther(element, oppositeType) {
        let dataNumber = element.getAttribute( 'data-number' );

        //Reset the opposite input
        const oppositeInputId = oppositeType + '_' + dataNumber;
        const oppositeInput = document.getElementById(oppositeInputId);

        if (oppositeInput) {
            oppositeInput.value = '0.00';
        }

    }

    // Auto open on focus
    $('.select2').on('focus', function() {
        $(this).select2('open');
    });

    // Also support tabbing into the field
    $(document).on('focus', '.select2-selection', function(e) {
        const select = $(this).closest('.select2-container').prev('select');
        select.select2('open');
    });

</script>
@endsection
