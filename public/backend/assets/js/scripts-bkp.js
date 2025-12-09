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

    /**
     * add client more permanent address
     */
    $(".add-more-permanent-address").on( "click", function(){
        var countAddressDiv = $("#personal_permanent_address_container .permanent-address").length;
        
        $.ajax({
            url: $(".get-continent-list-url").text(),
            type: "GET",
            success: function (obj) {
                var selectDropdown = "<option value='0'>Select Continent</option>";

                $.each( obj.data, function( key, value ) {
                    selectDropdown+= '<option value="'+value.id+'">'+value.name+'</option>';
                });

                var addressHtml = `<div class="form-row permanent-address">
                                    <div class="form-group col-md-4 col-sm-12">
                                        <label for="address">Address</label>
                                        <textarea name="address" id="address" class="form-control address" rows="9" placeholder="Business Address"></textarea>
                                    </div>

                                    <div class="form-group col-md-8 col-sm-12">
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="permanent_address_unique_id_'+countAddressDiv+'">Unique ID</label>
                                                <input type="text" class="form-control" id="permanent_address_unique_id_${countAddressDiv}" name="permanent_address_unique_id[]" placeholder="Unique ID">
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="permanent_address_contact_number">Mobile Number</label>
                                                <input type="text" class="form-control" id="permanent_address_contact_number_${countAddressDiv}" name="permanent_address_contact_number[]" placeholder="Mobile Number">
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="permanent_address_continent_id">Continent</label>
                                                <select name="permanent_address_continent_id[]" id="permanent_address_continent_id_${countAddressDiv}" class="form-control continent-id" onchange="getCountryList( 'permanent_address_country_id_0' )">
                                                    ${selectDropdown}
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="permanent_address_country_id">Country</label>
                                                <select name="permanent_address_country_id[]" id="permanent_address_country_id_${countAddressDiv}" class="form-control  country-id" onchange="getStateList( 'permanent_address_state_id_0' )">
                                                    <option value="0">Select Country</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="state_id">State</label>
                                                <select name="state_id" id="permanent_address_state_id_${countAddressDiv}" class="form-control state-id"  onchange="getCityList( 'permanent_address_city_id_0' )">
                                                    <option value="0">Select State</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="permanent_address_city_id">City</label>
                                                <select name="permanent_address_city_id[]" id="permanent_address_city_id_${countAddressDiv}" class="form-control city-id">
                                                    <option value="0">Select City</option>
                                                </select>
                                            </div>   
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="permanent_address_zipcode">Zipcode</label>
                                                <input type="text" class="form-control" id="permanent_address_zipcode_${countAddressDiv}" name="permanent_address_zipcode[]" placeholder="Zipcode">
                                            </div>                                                     
                                        </div>
                                    </div>

                                    <div class="form-group col-md-12 text-center">
                                        <button type="button" class="btn btn-outline-danger mt-4 pr-4 pl-4">
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

})(jQuery);

function getCountryList( appendDropdownId){
    var continent_id = $(this).val();
    alert(continent_id);
    $.ajax({
        url: $(".get-country-list-url").text()+"/"+continent_id,
        type: "GET",
        success: function (obj) {
            var selectDropdown = "";

            $.each( obj.data, function( key, value ) {
                selectDropdown+= '<option value="'+value.id+'">'+value.name+'</option>';
            });

            $("#"+appendDropdownId).html(selectDropdown);
        },
        error: function (error) {
            console.log(`Error ${error}`);
        }
    });
};

function getStateList( country_id, appendDropdownId){
    $.ajax({
        url: $(".get-state-list-url").text()+"/"+country_id,
        type: "GET",
        success: function (obj) {
            var selectDropdown = "";

            $.each( obj.data, function( key, value ) {
                selectDropdown+= '<option value="'+value.id+'">'+value.name+'</option>';
            });

            $("#"+appendDropdownId).html(selectDropdown);
        },
        error: function (error) {
            console.log(`Error ${error}`);
        }
    });
};

function getCityList( state_id, appendDropdownId){
    $.ajax({
        url: $(".get-city-list-url").text()+"/"+state_id,
        type: "GET",
        success: function (obj) {
            var selectDropdown = "";

            $.each( obj.data, function( key, value ) {
                selectDropdown+= '<option value="'+value.id+'">'+value.name+'</option>';
            });

            $("#"+appendDropdownId).html(selectDropdown);
        },
        error: function (error) {
            console.log(`Error ${error}`);
        }
    });
};