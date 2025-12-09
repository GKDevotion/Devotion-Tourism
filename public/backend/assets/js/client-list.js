var client_meeting_index;

/**
 *
 */
$(document).on( "ready", function() {
    if( $('#client_index').length > 0 ){

        var table = $('#client_index').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            dom: 'Bfrtip', // Define the table controls layout (B for Buttons)
            buttons: [
                'excel', 'pdf'//'copy',
            ],
            pageLength: 20,
            lengthMenu: [5, 10, 20, 30, 40, 50, 75, 100],
            ajax: $("#client_history_url").text(),
            columns: [
                // { data: 'id', name: 'id' },
                { data: 'unique_id', name: 'unique_id' },
                { data: 'name', name: 'name' },
                { data: 'contact', name: 'contact' },
                { data: 'email_id', name: 'email_id' },
                { data: 'company', name: 'company' },
                // { data: 'industry', name: 'industry' },
                // { data: 'birth_date', name: 'birth_date' },
                // { data: 'gender', name: 'gender' },
                // { data: 'status', name: 'status', orderable: false, searchable: false },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
            createdRow: function (row, data, dataIndex) {
                $(row).attr('id', 'row_' + data.id);// Assign a custom ID to the row
                $(row).attr('class', 'client_row');// Assign a custom Class to the row
            },
            language: {
                emptyTable: "No data available in table"  // Custom message for empty table
            },
        });

        // Adjust the table width after the data is loaded
        table.on('xhr', function() {
            var data = table.ajax.json().data;

            if (data.length === 0) {
                $('#client_index').css('width', '100%');
            } else {
                $('#client_index').css('width', '100%');
            }
        });
    }

});

/**
 *
 */
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

                if( $("#clientMettingScheduleFormModal").length > 0 ){
                    $(".btn-close").trigger("click");
                } else {
                    client_meeting_index.ajax.reload();
                }
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

/**
 *
 */
$(document).on('click', '.move-update-meeting', function () {
    let title = $(this).data("title");
    let id = $(this).data("id");

    $(".modal-title").text( title );
    $("#client_meeting_id").val( id );

    // Fetch data via AJAX
    $.ajax({
        url: url+'/api/get-client-meeting-data/'+id,
        method: 'GET',
        success: function (response) {

            $("#meeting_div_form, #meeting_div_history").trigger("click");

            if( response.success == true ){
                $("#clientMeetingSubmitForm #communication_type_id").val( response.data.communication_type_id );
                $("#clientMeetingSubmitForm #title").val( response.data.title );
                $("#clientMeetingSubmitForm #date").val( response.data.date );
                $("#clientMeetingSubmitForm #follow_up_date").val( response.data.follow_up_date );
                $("#clientMeetingSubmitForm #description").text( response.data.description );
                $("#clientMeetingSubmitForm #follow_up_detail").text( response.data.follow_up_detail );
                $("#clientMeetingSubmitForm #status").val( response.data.status_val );
                $("#clientMeetingSubmitForm #segment").val( response.data.segment_id );
                $("#clientMeetingSubmitForm #client_id").val( response.data.client_id );
            }
        },
        error: function () {
            $('#client_company').html( "Something went wrong." );
        }
    });
});

/**
 *
 */
$(document).on('click', '.metting-record', function () {
    $(".client-id").text( $(this).attr('data-id') );
    $(".client-id").val( $(this).attr('data-id') );

    if ( $.fn.dataTable.isDataTable( '#client_meeting_index' ) ) {
        $('#client_meeting_index').DataTable().destroy();
    }

    client_meeting_index = $('#client_meeting_index').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        dom: 'Bfrtip', // Define the table controls layout (B for Buttons)
        buttons: [
            'excel', 'pdf'//'copy',
        ],
        pageLength: 20,
        lengthMenu: [5, 10, 20, 30, 40, 50, 75, 100],
        ajax: $(".get-ajax-meeting-list-url").text(),
        columns: [
            { data: 'id', name: 'id' },
            { data: 'communication_type', name: 'communication_type' },
            { data: 'title', name: 'title' },
            { data: 'date', name: 'date' },
            { data: 'description', name: 'description'},
            { data: 'follow_up_date', name: 'follow_up_date' },
            { data: 'follow_up_detail', name: 'follow_up_detail'},
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        createdRow: function (row, data, dataIndex) {
            $(row).attr('id', 'row_' + data.id);// Assign a custom ID to the row
            $(row).attr('class', 'client_meeting_row');// Assign a custom Class to the row
        },
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

    // Validate all input fields
    $("input[data-required='yes']").each(function() {
        $(this).removeClass("error-border");
    });

    // Validate all textareas
    $("textarea[data-required='yes']").each(function() {
        $(this).siblings(".error").text("");
    });

    // Validate all select dropdowns
    $("select[data-required='yes']").each(function() {
        $(this).siblings(".error").text("");
    });
});

/**
 *
 */
$(document).on('click', '.admin-logs', function () {
    let id = $(this).data('id');

    $('#activity_logs tbody').html('<tr> <td colspan="8"Loading data.....</td></tr>');

    $("#activityLogModalLabel").text( $(this).data('name') )
    // Fetch data via AJAX
    $.ajax({
        url: url+'/api/get-activity-log/'+id,
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
            $('#activity_logs_index tbody').html( html );
        },
        error: function () {
            $('#activity_logs_index tbody').html('<tr><td colspan="8">Failed to load data.</td></tr>');
        }
    });
});

/**
 *
 */
$(document).on('change', '#client_services', function () {
    let id = $(this).val();

    // Fetch data via AJAX
    $.ajax({
        url: url+'/api/get-company-list/'+id,
        method: 'GET',
        success: function (response) {

            if( response.success == true ){
                var html= `<option value='0'>Select Company</option>`;
                $.each( response.data, function( key, val ){
                    html+= `<option value='${val.id}'>${val.name}</option>`;
                });
                $('#client_company').html( html );
            }
        },
        error: function () {
            $('#client_company').html( "Something went wrong." );
        }
    });
});

/**
 *
 */
$(document).on('change', '#client_company', function () {
    let id = $(this).val();

    // Fetch data via AJAX
    $.ajax({
        url: url+'/api/get-department-list/'+id,
        method: 'GET',
        success: function (response) {

            if( response.success == true ){
                var html= `<option value='0'>Select Department</option>`;
                $.each( response.data, function( key, val ){
                    html+= `<option value='${val.id}'>${val.name}</option>`;
                });
                $('#client_department').html( html );
            }
        },
        error: function () {
            $('#client_department').html( "Something went wrong." );
        }
    });
});

/**
 *
 */
$("#continueClient").on( "click", function(){
    var iid = $("#client_services").val();
    var cid = $("#client_company").val();
    var did = $("#client_department").val();

    window.location.href = url+"/admin/client/create?iid="+iid+"&cid="+cid+"&did="+did;
});

/**
 *
 */
$(document).on('click', '.devotion-services', function () {
    $(".client-id").text( $(this).data('id') );
    $(".client-id").val( $(this).data('id') );
    $("#clientName").text( $(this).data('name') )
    // Fetch data via AJAX
    $.ajax({
        url: $(".get-devotion-service-url").text(),
        method: 'GET',
        success: function (response) {
            // Update modal content
            $('#companyminLandNested').html( response );
        },
        error: function () {
            $('#companyminLandNested').html('Failed to load data');
        }
    });
});

/**
 *
 */
if( $('#clientDevotionServiceSubmitForm').length > 0){
    $('#clientDevotionServiceSubmitForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function(response) {
                // Handle success response
                showToast( response.message );
            },
            error: function(xhr) {
                // Handle error response
                showToast(xhr.responseJSON.errors);
            }
        });
    });
}
