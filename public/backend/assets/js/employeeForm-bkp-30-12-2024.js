var currentTab = 0; // Current tab is set to be the first tab (0)
var checkValidation = false;

$(document).on( "ready", function() {

    // const select2Class = ["religion-id", "continent-id", "country-id", "state-id", "city-id"];

    // $( select2Class ).each(function( index, className ) {
    //     if( $('.'+className).length > 0){
    //         $('.'+className).select2();
    //     }
    // });

    const dropifyClass = ["avtar", "aadhar_card", "pan_card", "passport", "driving_license", "national_id_card", "voter_id_card", "utility_bills", "bank_statement", "agreement", "property_receipt", "birth_certificate", "employee_letter", "body_checkup_report", "health_report"];
    $( dropifyClass ).each(function( index, className ) {
        
        if( $('.'+className).length > 0){
            $('.'+className).dropify();
        }
    });

    var countSocialMediaDiv = 0;

    if( $("#employeeForm").length > 0){
        $("#employeeForm").on( "submit", function(e) {
            var isValid = true;
        
            // Validate all input fields
            $("input[data-required='yes']").each(function() {

                if ( $(this).val().trim() === "" && !$(this).is( ":disabled" ) ) {
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
                if ( $(this).val().trim() === "" && !$(this).is( ":disabled" ) ) {
                    // $(this).next(".error").text("This field is required.");
                    $(this).addClass("error-border");
                    isValid = false;
                } else {
                    // $(this).next(".error").text("");
                    $(this).removeClass("error-border");
                }
            });
        
            // Validate all select dropdowns
            $("select[data-required='yes']").each(function() {
                
                if ( ( $(this).val() == "" || $(this).val() == 0 || $(this).val() == null ) && !$(this).is( ":disabled" ) ) {
                    $(this).siblings(".error").text("Please select an option.");
                    // $(this).siblings().addClass("error-border");
                    isValid = false;
                } else {
                    $(this).removeClass("error-border");
                    // $(this).next(".error").text("");
                }
            });
        
            // If any field is invalid, prevent form submission
            if (!isValid) {
                e.preventDefault();
                return false;
            }
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
                                            <div class="error text-error"></div>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <input name="social_media[${countSocialMediaDiv}][link]" id="link_${countSocialMediaDiv}" class="form-control" value="" placeholder="Enter Social Media link">
                                            <div class="error text-error"></div>
                                        </div>`;

                    $("#addSocialMediaPlatform").append( addressHtml );
                },
                error: function (error) {
                    console.log(`Error ${error}`);
                }
            });
        });
    }

    if( $(".textarea").length > 0 ){
        document.querySelectorAll('textarea').forEach(textarea => {
            ClassicEditor
                .create(textarea)
                .catch(error => {
                    console.error(error);
                });
        });
    }

    /**
     * get departtment list
     */
    $(document).on('change', '#employee_type_id', function(){
        $(".contractual").attr( 'disabled', true );
        if( $("#employee_type_id option:selected").text().toLowerCase() == "contractual" ){
            $(".contractual").attr( 'disabled', false );
        }
    } );

    if( $("#employeeRegistrationForm").length > 0){
        showTab(currentTab); // Display the current tab
    }
    
    /**
     * 
     */
    $(document).on('change', '#reference_type', function(){
        $(".reference-field").addClass('d-none');
        $('.reference-field input').val('');
        var selected = $("#reference_type option:selected").val();
        $("#"+selected+"Reference").removeClass("d-none");
    });
});

function showTab(n) {
    // This function will display the specified tab of the form...
    var x = document.getElementsByClassName("step");
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

function nextPrev(n) {
    // This function will figure out which tab to display
    var x = document.getElementsByClassName("step");
    
    // Exit the function if any field in the current tab is invalid:
    if (n == 1 && !validateForm()) return false;
    
    x[currentTab].style.display = "none"; // Hide the current tab:
    
    currentTab = currentTab + n; // Increase or decrease the current tab by 1:
    
    // if you have reached the end of the form...
    if ( currentTab >= ( x.length + 1 ) ) {
        document.getElementById("signUpForm").submit(); // ... the form gets submitted:
        return false;
    }

    // Otherwise, display the correct tab:
    showTab(currentTab);
}

function validateForm() {

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
 * employee KYC information enable input tag
 */
$(".kyc-information").on( "change", function(){
    $("#"+$(this).attr('id')+"_input").removeAttr( "disabled" );
});

/**
 * employee KYC information disable input tag
 */
$(document).on( "click", '.dropify-clear', function() {
    // Finds the sibling input field within the same parent and gets its ID
    let inputId = $(this).siblings('input').attr('id');
    $("#"+inputId+"_input").attr( "disabled", true ); // Outputs the ID of the input field
});