const urlRegex = /^(https?:\/\/)?([\da-z.-]+)\.([a-z.]{2,6})([/\w.-]*)*\/?$/;
const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const amountRegex = /^[0-9]*\.?[0-9]+$/; // Matches numbers with optional decimal point

$.ajaxSetup({
	headers: {
	    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
});

(function($) {
    "use strict";

    /*================================
    Preloader
    ==================================*/

    var preloader = $("#preloader");
    $(window).on("load", function() {
        setTimeout(function() {
            preloader.fadeOut("slow", function() {
                $(this).remove();
            });
        }, 300);
    });

    /*================================
    sidebar collapsing
    ==================================*/
    if (window.innerWidth <= 1364) {
        $(".page-container").addClass("sbar_collapsed");
    }

    $(".nav-btn").on("click", function() {
        $(".page-container").toggleClass("sbar_collapsed");
    });

    /*================================
    Start Footer resizer
    ==================================*/
    var e = function() {
        var e =
            (window.innerHeight > 0 ? window.innerHeight : this.screen.height) -
            5;
        (e -= 67) < 1 && (e = 1),
            e > 67 && $(".main-content").css("min-height", e + "px");
    };
    $(window).ready(e), $(window).on("resize", e);

    /*================================
    sidebar menu
    ==================================*/
    $("#menu").metisMenu();

    /*================================
    slimscroll activation
    ==================================*/
    $(".menu-inner").slimScroll({
        height: "auto"
    });
    $(".nofity-list").slimScroll({
        height: "435px"
    });
    $(".timeline-area").slimScroll({
        height: "500px"
    });
    $(".recent-activity").slimScroll({
        height: "calc(100vh - 114px)"
    });
    $(".settings-list").slimScroll({
        height: "calc(100vh - 158px)"
    });

    /*================================
    stickey Header
    ==================================*/
    $(window).on("scroll", function() {
        var scroll = $(window).scrollTop(),
            mainHeader = $("#sticky-header"),
            mainHeaderHeight = mainHeader.innerHeight();

        // console.log(mainHeader.innerHeight());
        if (scroll > 1) {
            $("#sticky-header").addClass("sticky-menu");
        } else {
            $("#sticky-header").removeClass("sticky-menu");
        }
    });

    /*================================
    form bootstrap validation
    ==================================*/
    $('[data-toggle="popover"]').popover();

    /*------------- Start form Validation -------------*/
    window.addEventListener(
        "load",
        function() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName("needs-validation");
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener(
                    "submit",
                    function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add("was-validated");
                    },
                    false
                );
            });
        },
        false
    );

    /*================================
    Slicknav mobile menu
    ==================================*/
    $("ul#nav_menu").slicknav({
        prependTo: "#mobile_menu"
    });

    /*================================
    login form
    ==================================*/
    $(".form-gp input").on("focus", function() {
        $(this)
            .parent(".form-gp")
            .addClass("focused");
    });

    $(".form-gp input").on("focusout", function() {
        if ($(this).val().length === 0) {
            $(this)
                .parent(".form-gp")
                .removeClass("focused");
        }
    });

    /*================================
    slider-area background setting
    ==================================*/
    $(".settings-btn, .offset-close").on("click", function() {
        $(".offset-area").toggleClass("show_hide");
        $(".settings-btn").toggleClass("active");
    });

    /*================================
    Owl Carousel
    ==================================*/
    function slider_area() {
        var owl = $(".testimonial-carousel").owlCarousel({
            margin: 50,
            loop: true,
            autoplay: false,
            nav: false,
            dots: true,
            responsive: {
                0: {
                    items: 1
                },
                450: {
                    items: 1
                },
                768: {
                    items: 2
                },
                1000: {
                    items: 2
                },
                1360: {
                    items: 1
                },
                1600: {
                    items: 2
                }
            }
        });
    }
    slider_area();

    /*================================
    Fullscreen Page
    ==================================*/

    if ($("#full-view").length) {
        var requestFullscreen = function(ele) {
            if (ele.requestFullscreen) {
                ele.requestFullscreen();
            } else if (ele.webkitRequestFullscreen) {
                ele.webkitRequestFullscreen();
            } else if (ele.mozRequestFullScreen) {
                ele.mozRequestFullScreen();
            } else if (ele.msRequestFullscreen) {
                ele.msRequestFullscreen();
            } else {
                console.log("Fullscreen API is not supported.");
            }
        };

        var exitFullscreen = function() {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            } else {
                console.log("Fullscreen API is not supported.");
            }
        };

        var fsDocButton = document.getElementById("full-view");
        var fsExitDocButton = document.getElementById("full-view-exit");

        fsDocButton.addEventListener("click", function(e) {
            e.preventDefault();
            requestFullscreen(document.documentElement);
            $("body").addClass("expanded");
        });

        fsExitDocButton.addEventListener("click", function(e) {
            e.preventDefault();
            exitFullscreen();
            $("body").removeClass("expanded");
        });
    }

    $(document).on('change', '.get-country-list', function(){
        var continent_id = $(this).val();
        var appendDropdownId = $(this).attr("data-id");
        $.ajax({
            url: $(".get-country-list-url").text()+"/"+continent_id,
            type: "GET",
            success: function (obj) {
                var selectDropdown = '<option value="0">Select Country</option>';

                $.each( obj.data, function( key, value ) {
                    selectDropdown+= '<option value="'+value.id+'">'+value.name+'</option>';
                });

                $("#"+appendDropdownId).html(selectDropdown);
            },
            error: function (error) {
                console.log(`Error ${error}`);
            }
        });
    } );

    $(document).on('change', '.get-state-list', function(){
        var country_id = $(this).val();
        var appendDropdownId = $(this).attr("data-id");
        $.ajax({
            url: $(".get-state-list-url").text()+"/"+country_id,
            type: "GET",
            success: function (obj) {
                var selectDropdown = '<option value="0">Select State</option>';

                $.each( obj.data, function( key, value ) {
                    selectDropdown+= '<option value="'+value.id+'">'+value.name+'</option>';
                });

                $("#"+appendDropdownId).html(selectDropdown);
            },
            error: function (error) {
                console.log(`Error ${error}`);
            }
        });
    } );

    $(document).on('change', '.get-city-list', function(){
        var state_id = $(this).val();
        var appendDropdownId = $(this).attr("data-id");
        $.ajax({
            url: $(".get-city-list-url").text()+"/"+state_id,
            type: "GET",
            success: function (obj) {
                var selectDropdown = '<option value="0">Select City</option>';

                $.each( obj.data, function( key, value ) {
                    selectDropdown+= '<option value="'+value.id+'">'+value.name+'</option>';
                });

                $("#"+appendDropdownId).html(selectDropdown);
            },
            error: function (error) {
                console.log(`Error ${error}`);
            }
        });
    } );

    $(document).on('click', '.delete-permanent-address', function(){
        $("#permanent_address_div_"+$(this).attr( "data-id" )).remove();
    } );

    $(document).on( "change", ".permanent-address-checkbox", function(){
        if ($(this).is(':checked')) {
            $('.permanent-address-checkbox').not(this).prop('checked', false);
        }
    } );

    /*
    +---------------------------------------------+
        Update Listing status
    +---------------------------------------------+
    */
    $(document).on( "click", ".update-status", function(){
    // $(".update-status").on( "click", function() {
        var status = $(this).attr('data-status');//$(this).val();
        var table = $(this).attr('data-table');
        var id = $(this).attr('data-id');

        var updateStatus = ( status == 0 ) ? 1 : 0;
        $.ajax({
            url: url + '/admin/update-status/'+table+'/'+id+'/'+updateStatus,
            type: "GET",
            data: {},
            dataType: 'json',
            success: function(result) {
                showToast( "Status update successfully.");
            }
        });

        if( status == 0 ){
            $(this).removeClass('fa-times');
            $(this).addClass('fa-check');
            $(this).attr('data-status', 1 );
        } else {
            $(this).removeClass('fa-check');
            $(this).addClass('fa-times');
            $(this).attr('data-status', 0);
        }
    });

    /*
    +---------------------------------------------+
        Update Listing status
    +---------------------------------------------+
    */
    $(document).on( "click", ".update-field-status", function(){
    // $(".update-status").on( "click", function() {
        var status = $(this).attr('data-status');//$(this).val();
        var table = $(this).attr('data-table');
        var field = $(this).attr('data-field');
        var id = $(this).attr('data-id');

        var updateStatus = ( status == 0 ) ? 1 : 0;
        $.ajax({
            url: url + '/admin/update-field-status/'+table+'/'+id+'/'+updateStatus+'/'+field,
            type: "GET",
            data: {},
            dataType: 'json',
            success: function(result) {
                showToast( "Status update successfully.");
            }
        });

        if( status == 0 ){
            $(this).removeClass('fa-times');
            $(this).addClass('fa-check');
            $(this).attr('data-status', 1 );
        } else {
            $(this).removeClass('fa-check');
            $(this).addClass('fa-times');
            $(this).attr('data-status', 0);
        }
    });

    /*
    +---------------------------------------------+
        Update Listing status
    +---------------------------------------------+
    */
    $(document).on( "change", ".crm-update", function(){
        var crm = $(this).attr('data-crm');
        var table = $(this).attr('data-table');
        var id = $(this).attr('data-id');

        var updateCRM = ( crm == 0 ) ? 1 : 0;
        $.ajax({
            url: url + '/admin/crm-update/'+table+'/'+id+'/'+updateCRM,
            type: "GET",
            data: {},
            dataType: 'json',
            success: function(result) {
                showToast( "CRM update successfully.");
            }
        });

        if( crm == 0 ){
            $(this).attr('data-crm', 1 );
            const option = document.querySelector('#crm_update_'+id+' option[value="1"]');
            option.classList.remove('bg-warning');
            option.classList.add('bg-success');
        } else {
            $(this).attr('data-crm', 0);
            const option = document.querySelector('#crm_update_'+id+' option[value="0"]');
            option.classList.remove('bg-success');
            option.classList.add('bg-warning');
        }
    });

    /**
     * get industry list
     */
    $(document).on('change', '.get-company-list', function(){
        var state_id = $(this).val();
        var appendDropdownId = $(this).attr("data-id");
        $.ajax({
            url: $(".get-company-list-url").text()+"/"+state_id,
            type: "GET",
            success: function (obj) {
                var selectDropdown = '<option value="0">Select Company</option>';

                $.each( obj.data, function( key, value ) {
                    selectDropdown+= '<option value="'+value.id+'">'+value.name+'</option>';
                });

                $("#"+appendDropdownId).html(selectDropdown);
            },
            error: function (error) {
                console.log(`Error ${error}`);
            }
        });
    } );

    /**
     * get departtment list
     */
    $(document).on('change', '.get-department-list', function(){
        var state_id = $(this).val();
        var appendDropdownId = $(this).attr("data-id");
        $.ajax({
            url: $(".get-department-list-url").text()+"/"+state_id,
            type: "GET",
            success: function (obj) {
                var selectDropdown = '<option value="0">Select Department</option>';

                $.each( obj.data, function( key, value ) {
                    selectDropdown+= '<option value="'+value.id+'">'+value.name+'</option>';
                });

                $("#"+appendDropdownId).html(selectDropdown);
            },
            error: function (error) {
                console.log(`Error ${error}`);
            }
        });
    } );

    /**
     * get departtment list
     */
    $(document).on('change', '.get-position-list', function(){
        var role_id = $(this).val();
        var appendDropdownId = $(this).attr("data-id");
        $.ajax({
            url: $(".get-position-list-url").text()+"/"+role_id,
            type: "GET",
            success: function (obj) {
                var selectDropdown = '<option value="0">Select Position</option>';

                $.each( obj.data, function( key, value ) {
                    selectDropdown+= '<option value="'+value.id+'">'+value.name+'</option>';
                });

                $("#"+appendDropdownId).html(selectDropdown);
            },
            error: function (error) {
                console.log(`Error ${error}`);
            }
        });
    } );

    /**
     * get departtment list
     */
    $(document).on('change', '.get-illness-list', function(){
        var role_id = $(this).val();
        var appendDropdownId = $(this).attr("data-id");
        $.ajax({
            url: $(".get-illness-list-url").text()+"/"+role_id,
            type: "GET",
            success: function (obj) {
                var selectDropdown = '<option value="0">Select Illness</option>';

                $.each( obj.data, function( key, value ) {
                    selectDropdown+= '<option value="'+value.id+'">'+value.name+'</option>';
                });

                $("#"+appendDropdownId).html(selectDropdown);
            },
            error: function (error) {
                console.log(`Error ${error}`);
            }
        });
    } );

    if( $(".notification-area").length > 0 ){
        refreshNotificationList();
    }

    // setInterval(function() {
    //     refreshNotificationList();
    // }, 25 * 1000);

    $(document).on('change', '.get-employee-list', function(){
        var employee_id = $(this).val();
        var appendDropdownId = $(this).attr("data-id");
        $.ajax({
            url: $(".get-employee-list-url").text()+"/"+employee_id,
            type: "GET",
            success: function (obj) {
                var selectDropdown = '<option value="0">Select Employee</option>';

                $.each( obj.data, function( key, value ) {
                    selectDropdown+= '<option value="'+value.id+'">'+value.first_name+' '+value.middle_name+' '+value.last_name+'</option>';
                });

                $("#"+appendDropdownId).html(selectDropdown);
            },
            error: function (error) {
                console.log(`Error ${error}`);
            }
        });
    } );

    $(document).on('change', '.get-employee-details', function(){
        var employee_id = $(this).val();
        $.ajax({
            url: $(".get-employee-details-url").text()+"/"+employee_id,
            type: "GET",
            success: function (obj) {

                $("#name").val( obj.data.first_name+' '+obj.data.last_name );
                $("#email").val( obj.data.email_id );
                $("#contact").val( obj.data.personal_mobile_number );
            },
            error: function (error) {
                console.log(`Error ${error}`);
            }
        });
    } );

})(jQuery);

function refreshNotificationList(){

    $.ajax({
        url: $("#get_dashboard_notification_URL").text(),
        type: "GET",
        success: function (obj) {
            var selectDropdown = '';
            var total = 0;
            $.each( obj, function( key, value ) {
                selectDropdown+= `<a href="`+value.url+`" class="notify-item">
                                    <div class="notify-thumb">
                                        <i class="ti-key btn-danger"></i>
                                    </div>
                                    <div class="notify-text">
                                        <p>`+value.title+`</p>
                                        <span>`+value.time+`</span>
                                    </div>
                                </a>`;

                total++;
            });

            $(".bell-notify-box .nofity-list").html(selectDropdown);
            $(".total-notification").text(total);
        },
        error: function (error) {
            // console.log(`Error ${error}`);
        }
    });
}

function showToast(message) {
    // Create the toast element
    var toast = document.createElement("div");
    toast.className = "toast";
    toast.innerText = message;

    // Append the toast element to the body
    document.body.appendChild(toast);

    // Show the toast
    setTimeout(function(){
        toast.className = "toast show";
    }, 100);

    // Hide the toast after 3 seconds
    setTimeout(function(){
        toast.className = toast.className.replace("show", "");
        // Remove the toast from the DOM
        document.body.removeChild(toast);
    }, 3000);
}

$(document).on( "ready", function () {
    var trigger = $('.hamburger'),
        overlay = $('.overlay'),
        isClosed = false;

        trigger.on( "click", function () {
            hamburger_cross();
        });

        function hamburger_cross() {

        if (isClosed == true) {
            overlay.hide();
            trigger.removeClass('is-open');
            trigger.addClass('is-closed');
            isClosed = false;
        } else {
            overlay.show();
            trigger.removeClass('is-closed');
            trigger.addClass('is-open');
            isClosed = true;
        }
    }

    $('[data-toggle="offcanvas"]').on( "click", function () {
        $('#wrapper').toggleClass('toggled');
    });
});

$(document).on( "click", '.clear-cache', function(){
    // navigator.sendBeacon('admin/logout/submit');
    $.ajax({
        url: url+"/api/clear-cache",
        method: 'GET',

        // Show loader before the request
        beforeSend: function() {
            $("#preloader").show();
        },

        // Process the response data on success
        success: function (response) {
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

/**
 * @Function:        <__construct>
 * @Author:          Gautam Kakadiya( ShreeGurave Dev Team )
 * @Created On:      <06-12-2021>
 * @Last Modified By:Gautam Kakadiya
 * @Last Modified:   Gautam Kakadiya
 * @Description:     <This function work for delete selected records.>
 * @return void
 */
$(document).on( "click", '.delete-record', function(){
    var id = $(this).data('id');
    var title = $(this).data('title');
    var segment = $(this).data('segment');

    // $('.bootbox').on('hide.bs.modal', function (e) {
    //       // do something...
    //       alert("Bootstrap here");
    // });

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
                    success: function(result) {

                        if( result.data.status == 201 ){
                            showToast( result.data.message, "success");
                        } else {

                            if( segment == "accounting" ){
                                table.ajax.reload(); // Refresh DataTable
                            } else {
                                $("#row_"+id).remove();//remove current selected table row
                                $("#"+segment+"_index tr.child").remove();//remove once available child table rows open

                                //check current select row is last then disaply default row message
                                if( $( "."+segment+"_row" ).length == 0 ){
                                    var totallength = $( "#"+segment+" th").length;
                                    $("tbody").html('<tr class="text-center"><td colspan="'+totallength+'">There is no data available.</td></tr>');
                                }
                            }

                            showToast( result.data.message, "success");
                        }
                    }
                });
            } else {
                // dataTable.ajax.reload();
                showToast( "Entry was reverted", "warning");
            }
        }
    });
});

if( $('.website-link-validation').length > 0 ){
    $('.website-link-validation').on('keyup', function() {
        var url = $(this).val();

        if (urlRegex.test(url)) {
            $(this).removeClass("error-border");
        } else {
            $(this).addClass("error-border");
        }
    });
}

if( $(".check-email-validation").length > 0 ){
    $('.check-email-validation').on('keypress keyup blur', function () {
        const email = $(this).val();

        if (emailRegex.test(email)) {
            $(this).removeClass("error-border");
        } else {
            $(this).addClass("error-border");
        }
    });
}

if( $(".allow-only-number").length > 0 ){
    $('.allow-only-number').on('keypress', function(event) {
        // Allow only numeric input
        if ( event.which < 48 || event.which > 57 ) {
            event.preventDefault();  // Prevent non-numeric characters
        }
    });
}

function scrollToMoveSpecificDiv(divId) {
    const element = document.getElementById(divId);
    element.scrollIntoView({ behavior: 'smooth' });
}

/*
+---------------------------------------------+
	Function is displaying form error beside the
	input area.
	@params : errorArray : 2 dimensional array with
							name of input.
+---------------------------------------------+
*/
function displayErrors(errorArray)
{
	//remove previous all error border class
	$('.required-field').removeClass('error-border');

	//setting error into form
	for( x in errorArray)
	{
        // console.log( x );
		$('#'+x.replace(/\./g, "_")).addClass('error-border');
		//jQuery('.c-form').attr('class','c-form error');
		//jQuery('.content-field1 ul li').attr('class','li error');
	}
}

/**
 *
 */
function onSubmitValidateForm() {

    var moveValidID = '';
    let isValid = true;
    $('[data-required]').each(function() {

        if ( $(this).attr("data-required") == 'yes' ) { // Check if the value is empty or whitespace
            $(this).removeClass("error-border");// remove an "error-border" class to the field:

            if( $(this).prop("tagName").toLowerCase() == "select" && $(this).val().trim() === '' ){
                console.log( "val: "+$(this).val().trim()+", ", $(this).prop("tagName").toLowerCase() );
                $(this).addClass("error-border");// add an "error-border" class to the field:
                isValid = false;

                if( moveValidID == "" ){
                    moveValidID = $(this).attr( 'id' );
                }
            } else if( $(this).prop("tagName").toLowerCase() == "textarea" && !$(this).val().trim() ){
                console.log( "val: "+$(this).val().trim()+", ", $(this).prop("tagName").toLowerCase() );
                $(this).addClass("error-border");// add an "error-border" class to the field:
                isValid = false;

                if( moveValidID == "" ){
                    moveValidID = $(this).attr( 'id' );
                }
            } else if( $(this).prop("tagName").toLowerCase() == "input" && !$(this).val().trim() ){
                console.log( "val: "+$(this).val().trim()+", ", $(this).prop("tagName").toLowerCase() );
                $(this).addClass("error-border");// add an "error-border" class to the field:
                isValid = false;

                if( moveValidID == "" ){
                    moveValidID = $(this).attr( 'id' );
                }
            } else if( !$(this).val().trim() ){
                console.log( "val: "+$(this).val().trim()+", ", $(this).prop("tagName").toLowerCase() );
                $(this).addClass("error-border");// add an "error-border" class to the field:
                isValid = false;

                if( moveValidID == "" ){
                    moveValidID = $(this).attr( 'id' );
                }
            }
        }
    });

    if( moveValidID != "" ){
        console.log( moveValidID );
        $('html, body').animate({
            scrollTop: $("#"+moveValidID).offset().top - 200
        }, 800); // 800ms animation speed
    }

    return isValid;
}

var countAboutOptionDiv = 0;
if( $(".add-more-about-options").length > 0){
    $(".add-more-about-options").on( "click", function(){
        countAboutOptionDiv++;
        var aboutOption = `<div class="col-md-2 col-sm-12 mt-2">
                                <input name="about_options[${countAboutOptionDiv}][font]" id="about_options_${countAboutOptionDiv}_font" class="form-control" placeholder="fa fa-******">
                            </div>
                            <div class="col-md-3 col-sm-12 mt-2">
                                <input name="about_options[${countAboutOptionDiv}][title]" id="about_options_${countAboutOptionDiv}_title" class="form-control" placeholder="Enter Option title">
                            </div>
                            <div class="col-md-7 col-sm-12 mt-2">
                                <input name="about_options[${countAboutOptionDiv}][description]" id="about_options_${countAboutOptionDiv}_description" class="form-control" placeholder="Enter Option Description">
                            </div>`;

        $("#addAboutOptions").append( aboutOption );
    });
}

var countProvidedServiceOptionDiv = 0;
if( $(".add-more-provide-service-options").length > 0){
    $(".add-more-provide-service-options").on( "click", function(){
        countProvidedServiceOptionDiv++;
        var providedService = `<div class="col-md-2 col-sm-12 mt-2">
                                <input name="about_options[${countProvidedServiceOptionDiv}][font]" id="about_options_${countProvidedServiceOptionDiv}_font" class="form-control" placeholder="fa fa-******">
                            </div>
                            <div class="col-md-3 col-sm-12 mt-2">
                                <input name="about_options[${countProvidedServiceOptionDiv}][title]" id="about_options_${countProvidedServiceOptionDiv}_title" class="form-control" placeholder="Enter Service title">
                            </div>
                            <div class="col-md-7 col-sm-12 mt-2">
                                <input name="about_options[${countProvidedServiceOptionDiv}][description]" id="about_options_${countProvidedServiceOptionDiv}_description" class="form-control" placeholder="Enter Service Description">
                            </div>`;

        $("#addProvidedServiceOptions").append( providedService );
    });
}

var countSocialMedia = 0;
if( $(".add-more-social-media").length > 0){
    $(".add-more-social-media").on( "click", function(){
        countSocialMedia++;
        var socialMedia = `<div class="col-md-6 mt-2">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-12">
                                            <input name="social_media[${countSocialMedia}][font]" id="social_media_${countSocialMedia}_font" class="form-control" placeholder="fa fa-******">
                                        </div>
                                        <div class="col-md-9 col-sm-12">
                                            <input name="social_media[${countSocialMedia}][link]" id="social_media_${countSocialMedia}_link" class="form-control" placeholder="Enter Social Media Link">
                                        </div>
                                    </div>
                                </div>`;

        $("#addSocialMedia").append( socialMedia );
    });
}

var countEmployeeProfession = 0;
if( $(".add-more-employee-profession").length > 0){
    $(".add-more-employee-profession").on( "click", function(){
        countEmployeeProfession++;
        var EmployeeProfession = `<div class="col-md-4 mt-2">
                                <input name="profession[${countEmployeeProfession}]" id="profession_${countEmployeeProfession}" class="form-control" placeholder="Enter Profession here">
                            </div>`;

        $("#addEmployeeProfession").append( EmployeeProfession );
    });
}

function unAuthoriseAccess( textMessage="" ){
    Swal.fire({
        icon: 'warning',
        title: 'Access to this resource on the server is denied',
        text: textMessage,
        confirmButtonText: 'OK'
    });
}
