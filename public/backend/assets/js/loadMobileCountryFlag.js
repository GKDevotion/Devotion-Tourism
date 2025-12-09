
$(".mobile-number-with-country-flag").each(function () {
    const input = this;

    // Initialize intlTelInput
    const iti = intlTelInput(input, {
        initialCountry: "auto",
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        geoIpLookup: function (callback) {
            $.get("https://ipinfo.io", function () {}, "jsonp").always(function (resp) {
                const countryCode = resp && resp.country ? resp.country : "us";
                callback(countryCode);
            });
        },
    });

    // Add country code to the input field when a country is selected
    $(input).on("countrychange", function () {
        const countryData = iti.getSelectedCountryData();
        const dialCode = `+${countryData.dialCode}`;
        const currentValue = input.value.trim();

        // Avoid duplicate country codes
        if (!currentValue.startsWith(dialCode)) {
            input.value = `${dialCode} `;
        }
    });

    // Validation on form submit or input change
    $(input).on('blur', function () {
        if (iti.isValidNumber()) {
            $(this).removeClass("error-border");
        } else {
            $(this).addClass("error-border");
        }
    });

    // Ensure input starts with country code on initialization
    const initialCountryData = iti.getSelectedCountryData();
    const mobileCode = `+${initialCountryData.dialCode}${input.value}`;
    input.value = `${mobileCode.replace( '+'+initialCountryData.dialCode, "")}`;
});

// $(".mobile-number-with-country-flag").prop('value', '');


function addNewMobileCountryFlag( id ){
    // Initialize intlTelInput for the phone input field
    var phoneInput = document.querySelector("#"+id);
    var iti = intlTelInput(phoneInput, {
        initialCountry: "us",
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js", // For validation and formatting
    });

    // Listen for the `countrychange` event
    phoneInput.addEventListener("countrychange", function () {
        // Get selected country data
        var countryData = iti.getSelectedCountryData();
        var countryCode = "+" + countryData.dialCode;

        // Update the input field with the country code
        phoneInput.value = countryCode + " ";
    });

    // Optional: Set the country code initially
    var initialCountryData = iti.getSelectedCountryData();
    phoneInput.value = "+" + initialCountryData.dialCode + " ";
}