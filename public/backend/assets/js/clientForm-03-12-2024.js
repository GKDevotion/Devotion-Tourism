$(document).on( "ready", function() {

    const select2 = ["religion-id", "continent-id", "country-id", "state-id", "city-id"];

    $( select2 ).each(function( index ) {
        if( $('.'+index).length > 0){
            $('.'+index).select2();
        }
    });

    const dropifyClass = ["avtar", "emp_avtar", "business-logo"];
    $( dropifyClass ).each(function( index, className ) {
        
        if( $('.'+className).length > 0){
            $('.'+className).dropify();
        }
    });

    var countAddressDiv = 0;
    var countSocialMediaDiv = 0;

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
                                            <label for="address_${countAddressDiv}">Address ${countAddressDiv + 1}</label>
                                            <textarea name="permanent_address[${countAddressDiv}][address]" id="address_${countAddressDiv}" class="form-control address" rows="9" data-required="yes" placeholder="Address"></textarea>
                                            <div class="error text-error"></div>
                                        </div>

                                        <div class="col-md-8 col-sm-12">
                                            <div class="row">
                                                <div class="col-md-6 col-sm-12">
                                                    <label for="permanent_address_unique_id_${countAddressDiv}">Unique ID</label>
                                                    <input type="text" class="form-control" id="permanent_address_unique_id_${countAddressDiv}" data-required="yes" name="permanent_address[${countAddressDiv}][unique_id]" placeholder="Unique ID">
                                                    <div class="error text-error"></div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <label for="permanent_address_contact_number_${countAddressDiv}">Mobile Number</label>
                                                    <input type="text" class="form-control" id="permanent_address_contact_number_${countAddressDiv}" name="permanent_address[${countAddressDiv}][contact_number]" placeholder="Mobile Number" data-required="yes">
                                                    <div class="error text-error"></div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <label for="permanent_address_continent_id_${countAddressDiv}">Continent</label>
                                                    <select name="permanent_address[${countAddressDiv}][continent_id]" id="permanent_address_continent_id_${countAddressDiv}" class="form-control get-country-list continent-id" data-id="permanent_address_country_id_${countAddressDiv}" data-required="yes">
                                                        ${selectDropdown}
                                                    </select>
                                                    <div class="error text-error"></div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <label for="permanent_address_country_id_${countAddressDiv}">Country</label>
                                                    <select name="permanent_address[${countAddressDiv}][country_id]" id="permanent_address_country_id_${countAddressDiv}" class="form-control get-state-list country-id" data-id="permanent_address_state_id_${countAddressDiv}" data-required="yes">
                                                        <option value="0">Select Country</option>
                                                    </select>
                                                    <div class="error text-error"></div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <label for="permanent_address_state_id_${countAddressDiv}">State</label>
                                                    <select name="permanent_address[${countAddressDiv}][state_id]" id="permanent_address_state_id_${countAddressDiv}" class="form-control get-city-list state-id" data-id="permanent_address_city_id_${countAddressDiv}" data-required="yes">
                                                        <option value="0">Select State</option>
                                                    </select>
                                                    <div class="error text-error"></div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <label for="permanent_address_city_id_${countAddressDiv}">City</label>
                                                    <select name="permanent_address[${countAddressDiv}][city_id]" id="permanent_address_city_id_${countAddressDiv}" class="form-control city-id" data-required="yes">
                                                        <option value="0">Select City</option>
                                                    </select>
                                                    <div class="error text-error"></div>
                                                </div>   
                                                <div class="col-md-6 col-sm-12">
                                                    <label for="permanent_address_zipcode_${countAddressDiv}">Zipcode</label>
                                                    <input type="text" class="form-control" id="permanent_address_zipcode_${countAddressDiv}" name="permanent_address[${countAddressDiv}][zipcode]" placeholder="Zipcode">
                                                    <div class="error text-error"></div>
                                                </div>
                                                <div class="col-md-6 col-sm-12 permanent-address-checkbox-div">
                                                    <input type="checkbox" class="permanent-address-checkbox" id="permanent_address_checkbox_${countAddressDiv}" name="permanent_address[${countAddressDiv}][checkbox]">
                                                    <label for="permanent_address_checkbox_${countAddressDiv}">Permanent Address</label>
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
                },
                error: function (error) {
                    console.log(`Error ${error}`);
                }
            });
        });
    }

    if( $("#clientForm").length > 0){
        $("#clientForm").on( "submit", function(e) {
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
                    $(this).addClass("error-border");
                    isValid = false;
                } else {
                    // $(this).next(".error").text("");
                    $(this).removeClass("error-border");
                }
            });
        
            // Validate all select dropdowns
            $("select[data-required='yes']").each(function() {
                console.log( $(this).val() );
                if ($(this).val() == "" || $(this).val() == 0 || $(this).val() == null) {
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

    document.querySelectorAll('textarea').forEach(textarea => {
        ClassicEditor
            .create(textarea)
            .catch(error => {
                console.error(error);
            });
    });

    var client_meeting_index;
    $(window).load(function() {

        if( $('#client_meeting_index').length > 0){
            client_meeting_index = $('#client_meeting_index').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: $(".get-ajax-meeting-list-url").text(),
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'communication_type', name: 'communication_type' },
                    { data: 'title', name: 'title' },
                    { data: 'date', name: 'date' },
                    { data: 'follow_up_date', name: 'follow_up_date' },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                language: {
                    emptyTable: "No data available in table"  // Custom message for empty table
                },
            });

            // Adjust the table width after the data is loaded
            client_meeting_index.on('xhr', function() {
                var data = client_meeting_index.ajax.json().data;

                if (data.length === 0) {
                    $('#client_meeting_index').css('width', '100%');
                } else {
                    $('#client_meeting_index').css('width', 'auto');
                }
            });
        }
    });

    if( $('#clientMeetingSubmitForm').length > 0){
        $('#clientMeetingSubmitForm').on('submit', function(e) {
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
                    client_meeting_index.ajax.reload();
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
});
