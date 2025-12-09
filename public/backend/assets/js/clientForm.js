var currentTab = 0; // Current tab is set to be the first tab (0)
var checkValidation = false;
let formChanged = false;
let formSubmitted = false;

// Track form changes
function trackChanges() {
    formChanged = true;
}

// Warn if leaving without submitting
window.onbeforeunload = function(event) {
    if (formChanged && !formSubmitted) {
        return "You have unsaved changes or incomplete steps. If you leave now, any unsaved progress may be lost. Are you sure you want to proceed?";
    }
};

const select2 = ["religion-id", "continent-id", "country-id", "state-id", "city-id"];

$( select2 ).each(function( index ) {
    if( $('.'+index).length > 0){
        console.log( index );
        $('.'+index).select2();
    }
});

const dropifyClass = ["avtar", "emp_avtar", "business-logo"];
$( dropifyClass ).each(function( index, className ) {
    if( $('.'+className).length > 0){
        $('.'+className).dropify();
    }
});

var countAddressDiv = countCorporateUserDiv = countEmployeeUserDiv = countSocialMediaDiv = 0;

/**
 * add client more permanent address
 */
if( $(".add-more-permanent-address").length > 0 ){
    $(".add-more-permanent-address").on( "click", function(){
        countAddressDiv++;

        // = $("#personal_permanent_address_container .permanent-address").length;

        $.ajax({
            url: $(".get-continent-list-url").text(),
            type: "GET",
            success: function (obj) {
                var selectDropdown = "<option value='0'>Select Continent</option>";

                $.each( obj.data, function( key, value ) {
                    selectDropdown+= '<option value="'+value.id+'">'+value.name+'</option>';
                });

                var addressHtml = `<div id="permanent_address_div_${countAddressDiv}" class="row permanent-address box-shadow-10">
                                    <div class="col-md-4 col-sm-12">
                                        <label for="personal_detail_address_${countAddressDiv}_address">Address ${countAddressDiv + 1}</label>
                                        <textarea name="personal_detail[address][${countAddressDiv}][address]" id="personal_detail_address_${countAddressDiv}_address" class="form-control address required-field" rows="5" data-required="yes" placeholder="Address"></textarea>
                                    </div>

                                    <div class="col-md-8 col-sm-12">
                                        <div class="row">
                                            <div class="col-md-4 col-sm-12 mb-2">
                                                <label class="mb-0" for="personal_detail_address_${countAddressDiv}_contact_number">Home Country Mobile<span class="text-error">*</span></label>
                                                <input type="text" class="mobile-number-with-country-flag allow-only-number required-field" id="personal_detail_address_${countAddressDiv}_contact_number" name="personal_detail[address][${countAddressDiv}][contact_number]" placeholder="Mobile Number" data-required="yes">
                                            </div>
                                            <div class="col-md-4 col-sm-12 mb-2">
                                                <label class="mb-0" for="personal_detail_address_${countAddressDiv}_continent_id">Continent<span class="text-error">*</span></label>
                                                <select name="personal_detail[address][${countAddressDiv}][continent_id]" id="personal_detail_address_${countAddressDiv}_continent_id" class="form-control get-country-list continent-id required-field" data-id="personal_detail_address_${countAddressDiv}_country_id" data-required="yes">
                                                    ${selectDropdown}
                                                </select>
                                            </div>
                                            <div class="col-md-4 col-sm-12 mb-2">
                                                <label class="mb-0" for="personal_detail_address_${countAddressDiv}_country_id">Country<span class="text-error">*</span></label>
                                                <select name="personal_detail[address][${countAddressDiv}][country_id]" id="personal_detail_address_${countAddressDiv}_country_id" class="form-control get-state-list country-id required-field" data-id="personal_detail_address_${countAddressDiv}_state_id" data-required="yes">
                                                    <option value="0">Select Country</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 col-sm-12 mb-2">
                                                <label class="mb-0" for="personal_detail_address_${countAddressDiv}_state_id">State<span class="text-error">*</span></label>
                                                <select name="personal_detail[address][${countAddressDiv}][state_id]" id="personal_detail_address_${countAddressDiv}_state_id" class="form-control get-city-list state-id required-field" data-id="personal_detail_address_${countAddressDiv}_city_id" data-required="yes">
                                                    <option value="0" >Select State</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 col-sm-12 mb-2">
                                                <label class="mb-0" for="personal_detail_address_${countAddressDiv}_city_id">City<span class="text-error">*</span></label>
                                                <select name="personal_detail[address][${countAddressDiv}][city_id]" id="personal_detail_address_${countAddressDiv}_city_id" class="form-control city-id required-field" data-required="yes">
                                                    <option value="0" >Select City</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 col-sm-12 mb-2">
                                                <label class="mb-0" for="personal_detail_address_${countAddressDiv}_zipcode">Zipcode<span class="text-error">*</span></label>
                                                <input type="text" class="form-control" id="personal_detail_address_${countAddressDiv}_zipcode" name="personal_detail[address][${countAddressDiv}][zipcode]" placeholder="Zipcode" data-required="yes">
                                            </div>
                                            <div class="col-md-4 col-sm-12 permanent-address-checkbox-div">
                                                <input type="checkbox" class="permanent-address-checkbox required-field" id="personal_detail_address_${countAddressDiv}_checkbox" name="personal_detail[address][${countAddressDiv}][checkbox]" style="width: auto;">
                                                <label for="personal_detail_address_${countAddressDiv}_checkbox">Permanent Address</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 text-center">
                                        <button type="button" class="btn btn-outline-danger pr-4 pl-4 delete-permanent-address" data-id="${countAddressDiv}">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>`;

                $("#personal_permanent_address_container").append( addressHtml );

                addNewMobileCountryFlag( "personal_detail_address_"+countAddressDiv+"_contact_number" );
            },
            error: function (error) {
                console.log(`Error ${error}`);
            }
        });
    });
}

if( $(".add-more-social-media").length > 0){
    $(".add-more-social-media").on( "click", function(){
        countSocialMediaDiv++;

        $.ajax({
            url: $(".get-social-media-platform-url").text(),
            type: "GET",
            success: function (obj) {
                var selectDropdown = "<option value='0'>Select Platform</option>";

                $.each( obj.data, function( key, value ) {
                    selectDropdown+= '<option value="'+value.id+'">'+value.name+'</option>';
                });

                var addressHtml = `<div class="col-md-2 col-sm-12 mb-2">
                                        <select name="social_media[${countSocialMediaDiv}][platform]" id="social_media_${countSocialMediaDiv}" class="form-control">
                                            ${selectDropdown}
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <input name="social_media[${countSocialMediaDiv}][link]" id="link_${countSocialMediaDiv}" class="form-control" value="" placeholder="Enter Social Media link">
                                    </div>`;

                $("#addSocialMediaPlatform").append( addressHtml );
            },
            error: function (error) {
                console.log(`Error ${error}`);
            }
        });
    });
}

// if( $(".textarea").length > 0 ){
//     document.querySelectorAll('textarea').forEach(textarea => {
//         ClassicEditor
//             .create(textarea)
//             .catch(error => {
//                 console.error(error);
//             });
//     });
// }

if( $("#clientForm").length > 0){
    showTab(currentTab); // Display the current tab
}

function moveStep(n) {
    currentTab = 0;
    if( n == 0 ){
        showTab(0);
    } else {
        for( i=0;i<n;i++ ){
            nextPrev(1, '');
        }
    }
}

function showTab(n) {
    // This function will display the specified tab of the form...
    var x = document.getElementsByClassName("step");
    $(".step").hide();
    x[n].style.display = "block";

    //... and fix the Previous/Next buttons:
    if (n == 0) {
        // document.getElementById("prevBtn").style.display = "none";
        $("#prevBtn").attr("disabled", true);
    } else {
        // document.getElementById("prevBtn").style.display = "inline";
        $("#prevBtn").attr("disabled", false);
    }

    if (n == (x.length - 1)) {
        $("#nextBtn").addClass("d-none");
        $("#submitBtn").removeClass("d-none");
    } else {
        $("#nextBtn").removeClass("d-none");
        $("#submitBtn").addClass("d-none");
        // document.getElementById("nextBtn").innerHTML = "Next";
    }

    //... and run a function that will display the correct step indicator:
    fixStepIndicator(n)
}

function nextPrev( n, formID ) {

    // This function will figure out which tab to display
    var x = document.getElementsByClassName("step");

    // Exit the function if any field in the current tab is invalid:
    // if ( n == 1 && !validateForm() ) {
    //     return false;
    // }

    // x[currentTab].style.display = "none"; // Hide the current tab:

    // currentTab = currentTab + n; // Increase or decrease the current tab by 1:

    // if you have reached the end of the form...
    // if ( currentTab >= ( x.length + 1 ) ) {
    //     document.getElementById("signUpForm").submit(); // ... the form gets submitted:
    //     return false;
    // }

    if( formID != "" ){

        let formData = new FormData( document.getElementById( formID ) );

        $.ajax({
            url: $(".client-submit-url").text(),
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {

                if (response.type == "success") {
                    $('.client-id').val(response.id);

                    showToast(response.message);

                    if( response.isGetLogRecord ){
                        $('#log_records_index tbody').html('<tr> <td colspan="8"Loading data.....</td></tr>');

                        // Fetch data via AJAX
                        $.ajax({
                            url: url+'/api/get-activity-log/'+response.id,
                            method: 'GET',
                            success: function (response) {

                                var html = "";
                                //set table body rows
                                $.each( response.data, function( key, value ){
                                    html+= `<tr>
                                            <td>${key+1}</td>
                                            <td>${value.user}</td>
                                            <td>${value.table}</td>
                                            <td>${value.field}</td>
                                            <td>${value.action}</td>
                                            <td>${value.ip_address}</td>
                                            <td>${value.description}</td>
                                            <td>${value.created_at}</td>
                                        </tr>`;
                                })

                                // Update modal content
                                $('#log_records_index tbody').html( html );

                                currentTab = currentTab + n; // Increase or decrease the current tab by 1:
                                showTab(currentTab);// Otherwise, display the correct tab:
                            },
                            error: function () {
                                $('#log_records_index tbody').html('<tr><td colspan="8">Failed to load data.</td></tr>');
                            }
                        });
                    } else if( response.isReDirect ){
                        // $("#clientDashbordURL").click();
                        // window.location.href = url+'/admin/client';
                        $(".other-info-step").addClass("d-none");
                        $(".thank-you-page").removeClass("d-none");
                        $(".client-unique-id").text(response.unique_id);

                        formSubmitted = true; // Prevent the confirmation on successful submit
                        formChanged = false;
                    } else {
                        currentTab = currentTab + n; // Increase or decrease the current tab by 1:
                        showTab(currentTab);// Otherwise, display the correct tab:
                    }
                } else {
                    showToast(response.message);
                }
            },
            error: function (xhr) {

                showToast("Check validation errors");
                displayErrors(xhr.responseJSON.errors);

                // showToast(xhr.responseText);
                // $('#responseMessage').html('An error occurred. Please try again.');

                // let errors = xhr.responseJSON.errors;
                // let message = 'Validation errors:<br>';
                // $.each(errors, function (key, value) {
                //     message += key + ': ' + value + '<br>';
                // });
                // $('#responseMessage').html(message);
            }
        });
    } else {
        currentTab = currentTab + n; // Increase or decrease the current tab by 1:
        showTab(currentTab);// Otherwise, display the correct tab:
    }
}

function validateForm() {

    alert( "validate" );
    if( !checkValidation ){
        return true;
    }

    var x, y, i, textarea, select, valid = true; // This function deals with validation of the form fields
    x = document.getElementsByClassName("step");
    y = x[currentTab].getElementsByTagName("input");

    for (i = 0; i < y.length; i++) {// A loop that checks every input field in the current tab:
        if (y[i].value == "" && $(y[i]).attr("data-required") == 'yes'  && !$(y[i]).is( ":disabled" ) ) {// If a field is empty...
            y[i].className += " invalid";// add an "invalid" class to the field:
            valid = false;// and set the current valid status to false
        }
    }

    textarea = x[currentTab].getElementsByTagName("textarea");
    for (i = 0; i < textarea.length; i++) {// A loop that checks every input field in the current tab:
        if (textarea[i].value == "" && $(textarea[i]).attr("data-required") == 'yes'  && !$(textarea[i]).is( ":disabled" ) ) {// If a field is empty...
            textarea[i].className += " invalid";// add an "invalid" class to the field:
            valid = false;// and set the current valid status to false
        }
    }

    select = x[currentTab].getElementsByTagName("select");
    for (i = 0; i < select.length; i++) {// A loop that checks every input field in the current tab:
        console.log( select[i] );
        if ( ( select[i].value == "" || select[i].value == "0" || select[i].value == null ) && $(select[i]).attr("data-required") == 'yes' && !$(select[i]).is( ":disabled" ) ) {// If a field is empty...
            select[i].className += " invalid";// add an "invalid" class to the field:
            valid = false;// and set the current valid status to false
        }
    }

    // If the valid status is true, mark the step as finished and valid:
    if (valid) {
        document.getElementsByClassName("stepIndicator")[currentTab].className += " finish";
    }

    return valid; // return the valid status
}

function fixStepIndicator(n) {
    // This function removes the "active" class of all steps...
    var i, x = document.getElementsByClassName("stepIndicator");
    for (i = 0; i < x.length; i++) {
        x[i].className = x[i].className.replace(" active", "");
    }

    //... and adds the "active" class on the current step:
    x[n].className += " active";
}

/**
 *
 */
$(".business-client-type").on("click", function(){
    var type = $(this).val();

    $(".disable-client-type").addClass("d-none");
    if( type == 1 ){
        $(".corporate-user").removeClass("d-none");
    } else if(type == 2){
        $(".employee-user").removeClass("d-none");
    }
});

/**
 *
 */
if( $("#corporate_user_container" ).length > 0 ){
    $(".add-more-corporate-user").on( "click", function(){
        countCorporateUserDiv++;

        $.ajax({
            url: $(".get-continent-list-url").text(),
            type: "GET",
            success: function (obj) {
                var selectDropdown = "<option value='0'>Select Continent</option>";

                $.each( obj.data, function( key, value ) {
                    selectDropdown+= '<option value="'+value.id+'">'+value.name+'</option>';
                });

                var addressHtml = `<div id="corporate_user_div_${countCorporateUserDiv}" class="row box-shadow-10">
                                    <fieldset>
                                        <div class="row">
                                            <div class="col-md-8 col-sm-12 mb-3 mb-2">
                                                <div class="row">
                                                    <div class="col-md-6 col-sm-12 mb-2">
                                                        <label class="mb-0" for="corporate_user_${countCorporateUserDiv}_name">Business Name<span class="text-error">*</span></label>
                                                        <input type="text" class="form-control" id="corporate_user_${countCorporateUserDiv}_name" name="corporate_user[${countCorporateUserDiv}][name]" placeholder="Business Name" data-required="yes" value="">
                                                    </div>
                                                    <div class="col-md-6 col-sm-12 mb-2">
                                                        <label class="mb-0" for="corporate_user_${countCorporateUserDiv}_email_id">Business Email<span class="text-error">*</span></label>
                                                        <input type="text" class="form-control check-email-validation" id="corporate_user_${countCorporateUserDiv}_email_id" name="corporate_user[${countCorporateUserDiv}][email_id]" placeholder="Business Email" data-required="yes" value="">
                                                    </div>
                                                    <div class="col-md-6 col-sm-12 mb-2">
                                                        <label class="mb-0" for="corporate_user_${countCorporateUserDiv}_website">Business Website<span class="text-error">*</span></label>
                                                        <input type="text" class="form-control website-link-validation" id="corporate_user_${countCorporateUserDiv}_website" name="corporate_user[${countCorporateUserDiv}][website]" placeholder="Website" data-required="yes" value="">
                                                    </div>
                                                    <div class="col-md-6 col-sm-12 mb-2">
                                                        <label class="mb-0" for="corporate_user_${countCorporateUserDiv}_mobile_number">Business Mobile Number<span class="text-error">*</span></label>
                                                        <input type="text" class="form-control mobile-number-with-country-flag allow-only-number" id="corporate_user_${countCorporateUserDiv}_mobile_number" name="corporate_user[${countCorporateUserDiv}][mobile_number]" placeholder="Mobile Number" data-required="yes" value="">
                                                    </div>
                                                    <div class="col-md-6 col-sm-12 mb-2">
                                                        <label class="mb-0" for="corporate_user_${countCorporateUserDiv}_industry_id">Business Industry<span class="text-error">*</span></label>
                                                        <select name="corporate_user[${countCorporateUserDiv}][industry_id]" id="corporate_user_${countCorporateUserDiv}_industry_id" class="form-control industry-id" data-required="yes">
                                                            ${industrySelectDropdownObj}
                                                        </select>
                                                        <div class="error text-error"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-12 mb-3 mb-2">
                                                <div class="col-md-12 col-sm-12 mb-3">
                                                    <label class="mb-0" for="corporate_user_${countCorporateUserDiv}_description">Description<span class="text-error">*</span></label>
                                                    <textarea name="corporate_user[${countCorporateUserDiv}][description]" id="corporate_user_${countCorporateUserDiv}_description" class="form-control description" rows="10" placeholder="Business Description" data-required="yes"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <fieldset>
                                        <legend>Business Address:</legend>
                                        <div class="row">
                                            <div class="col-md-4 col-sm-12 mb-3 mb-2">
                                                <label class="mb-0" for="corporate_user_${countCorporateUserDiv}_address">Address<span class="text-error">*</span></label>
                                                <textarea name="corporate_user[${countCorporateUserDiv}][address]" id="corporate_user_${countCorporateUserDiv}_address" class="form-control address" rows="9" placeholder="Business Address" data-required="yes"></textarea>
                                            </div>
                                            <div class="col-md-8 col-sm-12">
                                                <div class="row">
                                                    <div class="col-md-6 col-sm-12 mb-2">
                                                        <label class="mb-0" for="corporate_user_${countCorporateUserDiv}_continent_id">Continent<span class="text-error">*</span></label>
                                                        <select name="corporate_user[${countCorporateUserDiv}][continent_id]" id="corporate_user_${countCorporateUserDiv}_continent_id" class="form-control get-country-list bussiness-continent-id" data-id="corporate_user_${countCorporateUserDiv}_country_id" data-required="yes">
                                                            ${selectDropdown}
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 col-sm-12 mb-2">
                                                        <label class="mb-0" for="corporate_user_${countCorporateUserDiv}_country_id">Country<span class="text-error">*</span></label>
                                                        <select name="corporate_user[${countCorporateUserDiv}][country_id]" id="corporate_user_${countCorporateUserDiv}_country_id" class="form-control get-state-list bussiness-country-id" data-id="corporate_user_${countCorporateUserDiv}_state_id" data-required="yes">
                                                            <option value="0">Select Country</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 col-sm-12 mb-2">
                                                        <label class="mb-0" for="corporate_user_${countCorporateUserDiv}_state_id">State<span class="text-error">*</span></label>
                                                        <select name="corporate_user[${countCorporateUserDiv}][state_id]" id="corporate_user_${countCorporateUserDiv}_state_id" class="form-control get-city-list bussiness-state-id" data-id="corporate_user_${countCorporateUserDiv}_city_id" data-required="yes">
                                                            <option value="0">Select State</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 col-sm-12 mb-2">
                                                        <label class="mb-0" for="corporate_user_${countCorporateUserDiv}_city_id">City<span class="text-error">*</span></label>
                                                        <select name="corporate_user[${countCorporateUserDiv}][city_id]" id="corporate_user_${countCorporateUserDiv}_city_id" class="form-control bussiness-city-id" data-required="yes">
                                                            <option value="0">Select City</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 col-sm-12 mb-2">
                                                        <label class="mb-0" for="corporate_user_${countCorporateUserDiv}_zipcode">Zip Code<span class="text-error">*</span></label>
                                                        <input type="text" class="form-control" id="corporate_user_${countCorporateUserDiv}_zipcode" name="corporate_user[${countCorporateUserDiv}][zipcode]" placeholder="Zip Code" data-required="yes" value="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <div class="col-md-12 text-center">
                                        <button type="button" class="btn btn-outline-danger pr-4 pl-4 delete-corporate-user" data-id="${countCorporateUserDiv}">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>`;

                $("#corporate_user_container").append( addressHtml );

                addNewMobileCountryFlag( "corporate_user_"+countCorporateUserDiv+"_contact_number" );
            },
            error: function (error) {
                console.log(`Error ${error}`);
            }
        });
    });
}

/**
 *
 */
if( $("#employee_user_container" ).length > 0 ){
    $(".add-more-employee-user").on( "click", function(){
        countEmployeeUserDiv++;

        $.ajax({
            url: $(".get-continent-list-url").text(),
            type: "GET",
            success: function (obj) {
                var selectDropdown = "<option value='0'>Select Continent</option>";

                $.each( obj.data, function( key, value ) {
                    selectDropdown+= '<option value="'+value.id+'">'+value.name+'</option>';
                });

                var addressHtml = `<div id="employee_user_div_${countEmployeeUserDiv}"  class="row box-shadow-10">
                                        <fieldset>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-12 mb-2">
                                                    <div class="row">
                                                        <div class="col-md-12 col-sm-12 mb-2">
                                                            <label class="mb-0" for="employee_user_${countEmployeeUserDiv}_job_title">Job Title</label>
                                                            <input type="text" class="form-control" id="employee_user_${countEmployeeUserDiv}_job_title" name="employee_user[${countEmployeeUserDiv}][job_title]" placeholder="Job Title" value="">
                                                        </div>
                                                        <div class="col-md-12 col-sm-12 mb-2">
                                                            <label class="mb-0" for="employee_user_${countEmployeeUserDiv}_job_experience">Expreience in Month</label>
                                                            <input type="text" class="form-control" id="employee_user_${countEmployeeUserDiv}_job_experience" name="employee_user[${countEmployeeUserDiv}][job_experience]" placeholder="Job Experience" value="">
                                                        </div>
                                                        <div class="col-md-12 col-sm-12 mb-2">
                                                            <label class="mb-0" for="employee_user_${countEmployeeUserDiv}_company_name">Company Name</label>
                                                            <input type="text" class="form-control" id="employee_user_${countEmployeeUserDiv}_company_name" name="employee_user[${countEmployeeUserDiv}][company_name]" placeholder="Job Company Name" value="">
                                                        </div>
                                                        <div class="col-md-12 col-sm-12 mb-2">
                                                            <label class="mb-0" for="employee_user_${countEmployeeUserDiv}_company_website">Company Website</label>
                                                            <input type="text" class="form-control" id="employee_user_${countEmployeeUserDiv}_company_website" name="employee_user[${countEmployeeUserDiv}][company_website]" placeholder="Job Company Website" value="">
                                                        </div>
                                                        <div class="col-md-12 col-sm-12 mb-2">
                                                            <label class="mb-0" for="employee_user_${countEmployeeUserDiv}_company_email">Company Email</label>
                                                            <input type="text" class="form-control" id="employee_user_${countEmployeeUserDiv}_company_email" name="employee_user[${countEmployeeUserDiv}][company_email]" placeholder="Job Company Email" value="">
                                                        </div>
                                                        <div class="col-md-12 col-sm-12 mb-2">
                                                            <label class="mb-0" for="employee_user_${countEmployeeUserDiv}_company_mobile">Company Mobile</label>
                                                            <input type="text" class="form-control mobile-number-with-country-flag allow-only-number" id="employee_user_${countEmployeeUserDiv}_company_mobile" name="employee_user[${countEmployeeUserDiv}][company_mobile]" placeholder="Job Company Name" value="">
                                                        </div>
                                                        <div class="col-md-12 col-sm-12 mb-2">
                                                            <label class="mb-0" for="employee_user_${countEmployeeUserDiv}_company_landline">Company Landline</label>
                                                            <input type="text" class="form-control" id="employee_user_${countEmployeeUserDiv}_company_landline" name="employee_user[${countEmployeeUserDiv}][company_landline]" placeholder="Job Company Name" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12 mb-2">
                                                    <div class="row">
                                                        <div class="col-md-12 col-sm-12 mb-2">
                                                            <label class="mb-0" for="employee_user_${countEmployeeUserDiv}_job_skill">Job Skill</label>
                                                            <select name="employee_user[${countEmployeeUserDiv}][job_skill[]]" id="employee_user_${countEmployeeUserDiv}_job_skill" class="form-control job-skill-${countEmployeeUserDiv}" multiple="multiple">
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12 col-sm-12 mb-2">
                                                            <label class="mb-0" for="employee_user_${countEmployeeUserDiv}_job_description">Job Key Responsibilities</label>
                                                            <textarea name="employee_user[${countEmployeeUserDiv}][job_description]" id="employee_user_${countEmployeeUserDiv}_job_description" class="form-control description" rows="4" placeholder="Job Description"></textarea>
                                                        </div>
                                                        <div class="col-md-12 col-sm-12 mb-2">
                                                            <label class="mb-0" for="employee_user_${countEmployeeUserDiv}_company_address">Company Address</label>
                                                            <textarea name="employee_user[${countEmployeeUserDiv}][company_address]" id="employee_user_${countEmployeeUserDiv}_company_address" class="form-control description" rows="2" placeholder="Job Address"></textarea>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 col-sm-12 mb-2">
                                                                <label class="mb-0" for="employee_user_${countEmployeeUserDiv}_company_continent_id">Continent<span class="text-error">*</span></label>
                                                                <select name="employee_user[${countEmployeeUserDiv}][company_continent_id]" id="employee_user_${countEmployeeUserDiv}_continent_id" class="form-control get-country-list bussiness-continent-id" data-id="employee_user_${countEmployeeUserDiv}_company_country_id" data-required="yes">
                                                                    ${selectDropdown}
                                                                </select>
                                                                <div class="error text-error"></div>
                                                            </div>
                                                            <div class="col-md-6 col-sm-12 mb-2">
                                                                <label class="mb-0" for="employee_user_${countEmployeeUserDiv}_company_country_id">Country<span class="text-error">*</span></label>
                                                                <select name="employee_user[${countEmployeeUserDiv}][company_country_id]" id="employee_user_${countEmployeeUserDiv}_company_country_id" class="form-control get-state-list" data-id="employee_user_${countEmployeeUserDiv}_company_state_id" data-required="yes">
                                                                    <option value="0">Select Country</option>
                                                                </select>
                                                                <div class="error text-error"></div>
                                                            </div>
                                                            <div class="col-md-6 col-sm-12 mb-2">
                                                                <label class="mb-0" for="employee_user_${countEmployeeUserDiv}_company_state_id">State<span class="text-error">*</span></label>
                                                                <select name="employee_user[${countEmployeeUserDiv}][company_state_id]" id="employee_user_${countEmployeeUserDiv}_company_state_id" class="form-control get-city-list" data-id="employee_user_${countEmployeeUserDiv}_company_city_id" data-required="yes">
                                                                    <option value="0">Select State</option>
                                                                </select>
                                                                <div class="error text-error"></div>
                                                            </div>
                                                            <div class="col-md-6 col-sm-12 mb-2">
                                                                <label class="mb-0" for="employee_user_${countEmployeeUserDiv}_company_city_id">City<span class="text-error">*</span></label>
                                                                <select name="employee_user[${countEmployeeUserDiv}][company_city_id]" id="employee_user_${countEmployeeUserDiv}_company_city_id" class="form-control" data-required="yes">
                                                                    <option value="0">Select City</option>
                                                                </select>
                                                                <div class="error text-error"></div>
                                                            </div>
                                                            <div class="col-md-6 col-sm-12 mb-2">
                                                                <label class="mb-0" for="employee_user_${countEmployeeUserDiv}_company_zipcode">Zip Code<span class="text-error">*</span></label>
                                                                <input type="text" class="form-control" id="employee_user_${countEmployeeUserDiv}_company_zipcode" name="employee_user[${countEmployeeUserDiv}][company_zipcode]" placeholder="Zip Code" data-required="yes" value="">
                                                                <div class="error text-error"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 col-sm-12 mb-2">
                                                    <div class="row">
                                                        <div class="col-md-4 col-sm-12 mb-2">
                                                            <label class="mb-0" for="employee_user_${countEmployeeUserDiv}_company_facebook_url">Company Facebook URL</label>
                                                            <input type="text" class="form-control" id="employee_user_${countEmployeeUserDiv}_company_facebook_url" name="employee_user[${countEmployeeUserDiv}][company_facebook_url]" placeholder="Company Facebook URL" value="">
                                                        </div>
                                                        <div class="col-md-4 col-sm-12 mb-2">
                                                            <label class="mb-0" for="employee_user_${countEmployeeUserDiv}_company_twitter_url">Company Twitter URL</label>
                                                            <input type="text" class="form-control" id="employee_user_${countEmployeeUserDiv}_company_twitter_url" name="employee_user[${countEmployeeUserDiv}][company_twitter_url]" placeholder="Company Twitter URL" value="">
                                                        </div>
                                                        <div class="col-md-4 col-sm-12 mb-2">
                                                            <label class="mb-0" for="employee_user_${countEmployeeUserDiv}_company_linked_in_url">Company Linked in URL</label>
                                                            <input type="text" class="form-control" id="employee_user_${countEmployeeUserDiv}_company_linked_in_url" name="employee_user[${countEmployeeUserDiv}][company_linked_in_url]" placeholder="Company Linked in URL" value="">
                                                        </div>
                                                        <div class="col-md-4 col-sm-12 mb-2 d-none">
                                                            <label class="mb-0" for="employee_user_${countEmployeeUserDiv}_company_pinterest_url">Company Pinterest URL</label>
                                                            <input type="text" class="form-control" id="employee_user_${countEmployeeUserDiv}_company_pinterest_url" name="employee_user[${countEmployeeUserDiv}][company_pinterest_url]" placeholder="Company Pinterest URL" value="">
                                                        </div>
                                                        <div class="col-md-4 col-sm-12 mb-2 d-none">
                                                            <label class="mb-0" for="employee_user_${countEmployeeUserDiv}_company_quora_url">Company Quora URL</label>
                                                            <input type="text" class="form-control" id="employee_user_${countEmployeeUserDiv}_company_quora_url" name="employee_user[${countEmployeeUserDiv}][company_quora_url]" placeholder="Company Quora URL" value="">
                                                        </div>
                                                        <div class="col-md-4 col-sm-12 mb-2 d-none">
                                                            <label class="mb-0" for="employee_user_${countEmployeeUserDiv}_company_instagram_url">Company Instagram URL</label>
                                                            <input type="text" class="form-control" id="employee_user_${countEmployeeUserDiv}_company_instagram_url" name="employee_user[${countEmployeeUserDiv}][company_instagram_url]" placeholder="Company Instagram URL" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </fieldset>
                                    </div>`;

                $("#employee_user_container").append( addressHtml );

                const choices = new Choices('.job-skill-'+countEmployeeUserDiv, {
                    removeItemButton: true,
                    searchPlaceholderValue: 'Search options',
                    placeholder: "Select options",
                    allowClear: true,
                });

                addNewMobileCountryFlag( "employee_user_"+countAddressDiv+"_company_mobile" );
            },
            error: function (error) {
                console.log(`Error ${error}`);
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', function () {
    if( $('.job-skill').length > 0 ){
        const choices = new Choices('.job-skill', {
            removeItemButton: true,
            searchPlaceholderValue: 'Search options',
            placeholder: "Select options",
            allowClear: true,
        });
    }
});

if( $(".get-child-skill-list").length > 0 ){
    $(document).on( "change", "#employee_user_0_position_id", function(e){
        var parent_id = $(this).val();

        $("#employee_user_0_job_skill").find('.child-skill').addClass("d-none");
        $("#employee_user_0_job_skill").find(".parent-skill-"+parent_id ).removeClass("d-none");

        $("#skill-tag-store").html("");
        $("#employee_user_0_job_skill").val(0);
    });

    $(document).on( "change", "#employee_user_0_job_skill", async (e) => {
        var skillId = $("#employee_user_0_job_skill option:selected").val();
        var skillName = $("#employee_user_0_job_skill option:selected").text();

        if( skillName == "Other" ){
            const { value: otherSkill } = await Swal.fire({
                title: "Enter Other Skill",
                input: "text",
                inputLabel: "Enter your new skill",
                inputPlaceholder: "Enter your skill"
            });

            console.log( otherSkill );
            if (otherSkill) {
                $("#skill-tag-store").append(
                    `<span class="btn badge-dark title-badge mx-1 mt-2">
                        <input type="hidden" name="job_skill[]" value="${otherSkill}" >
                        ${otherSkill}
                        <a href="javascript:void(0)" class="text-white-50 ml-1 delTag">
                            <i class="fa fa-times"></i>
                        </a>
                    </span>`
                );
            }
        } else {

            $("#skill-tag-store").append(
                `<span class="btn badge-dark title-badge mx-1 mt-2">
                    <input type="hidden" name="job_skill[]" value="${skillId}" >
                    ${skillName}
                    <a href="javascript:void(0)" class="text-white-50 ml-1 delTag">
                        <i class="fa fa-times"></i>
                    </a>
                </span>`
            );
        }
    });

    /**
     *Add tag
    */
    $("#addSkillTag").on( "click", function (e) {
        var skillId = $("#employee_user_0_job_skill option:selected").val();
        var skillName = $("#employee_user_0_job_skill option:selected").text();

        if ( skillId > 0)
        {
            $("#skill-tag-store").append(
                `<span class="btn badge-dark title-badge mx-1 mt-2">
                    <input type="hidden" name="job_skill[]" value="${skillId}" >
                    ${skillName}
                    <a href="javascript:void(0)" class="text-white-50 ml-1 delTag">
                        <i class="fa fa-times"></i>
                    </a>
                </span>`
            );
        }
    });

    /**
     * Remove select tag
     */
    $("#skill-tag-store").on("click", ".delTag", function () {
        $(this).parent().remove();
    });

}

// if( $(".get-qualification-list").length > 0 ){
//     $(document).on( "click", ".get-qualification-list", function(e){

        // $.ajax({
        //     url: url+'/api/get-qualification-list',
        //     type: "GET",
        //     success: function (obj) {
        //         var selectDropdown = "<option value='0' >Select Qualification</option>";

        //         $.each( obj.data, function( key, value ) {
        //             selectDropdown+= '<option value="'+value.id+'">'+value.name+'</option>';
        //         });

        //         $("#employee_user_0_qualification").html( selectDropdown );
        //         console.log( selectDropdown );
        //     },
        //     error: function (error) {
        //         console.log(`Error ${error}`);
        //     }
        // });
//     });
// }
