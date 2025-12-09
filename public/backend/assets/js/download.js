/**
 *
 */
if( $("#downloadClientCompanyCSV").length > 0 ){
    document.getElementById("downloadClientCompanyCSV").addEventListener("click", function () {
        const data = {
            companyId: $("#company_id").val(),
        };

        fetch(url+'/client-company-download-csv', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),  // CSRF token
            },
            body: JSON.stringify(data)
        })
        .then(response => response.blob())
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = $("#company_name").val()+'.csv';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
        })
        .catch(error => console.error('Error:', error));
    });
}

/**
 *
 */
if( $("#downloadClientCompanyPDF").length > 0 ){
    document.getElementById("downloadClientCompanyPDF").addEventListener("click", function () {
        var companyId = $("#company_id").val();

        // Construct the URL with query parameters
        const openURL = url+`/client-company-download-pdf?companyId=${companyId}`;

        // Open the URL in a new tab
        window.open(openURL, '_blank');
    });
}

/**
 *
 */
if( $("#viewCompanyPDF").length > 0 ){
    document.getElementById("viewCompanyPDF").addEventListener("click", function () {
        var status = $("#status").val();
        var industryId = $("#industry_id").val();
        var companyName = $("#company_name").val();

        // Construct the URL with query parameters
        const openURL = url+`/company-view-pdf?status=${status}&industryId=${industryId}&companyName=${companyName}`;

        // Open the URL in a new tab
        window.open(openURL, '_blank');
    });
}

/**
 *
 */
if( $("#viewDepartmentPDF").length > 0 ){
    document.getElementById("viewDepartmentPDF").addEventListener("click", function () {
        var status = $("#status").val();
        var industryId = $("#industry_id").val();
        var companyId = $("#company_id").val();
        var departmentName = $("#department_name").val();

        // Construct the URL with query parameters
        const openURL = url+`/department-view-pdf?status=${status}&industryId=${industryId}&companyId=${companyId}&departmentName=${departmentName}`;

        // Open the URL in a new tab
        window.open(openURL, '_blank');
    });
}

/**
 * Download Client Personal Meeting data
 */
if( $("#downloadClientMeetingCSV").length > 0 ){

    document.getElementById("downloadClientMeetingCSV").addEventListener("click", function () {
        const data = {
            status: $("#meeting_status").val(),
            segment_id: $("#segment").val(),
            start_date: $("#start_date").val(),
            end_date: $("#end_date").val(),
        };

        fetch(url+'/personal-client-meeting-download-csv', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),  // CSRF token
            },
            body: JSON.stringify(data)
        })
        .then(response => response.blob())
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = 'client_meeting.csv';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
        })
        .catch(error => console.error('Error:', error));
    });
}

/**
 * View Client Personal Meeting data
 */
if( $("#viewClientMeetingPDF").length > 0 ){
    document.getElementById("viewClientMeetingPDF").addEventListener("click", function () {
        var status = $("#meeting_status").val();
        var segment_id = $("#segment").val();
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();

        // Construct the URL with query parameters
        const openURL = url+`/personal-client-meeting-view-pdf?status=${status}&segment_id=${segment_id}&start_date=${start_date}&end_date=${end_date}`;

        // Open the URL in a new tab
        window.open(openURL, '_blank');
    });
}

/**
 * Download Client Personal Meeting data
 */
if( $("#downloadCompanyMeetingCSV").length > 0 ){

    document.getElementById("downloadCompanyMeetingCSV").addEventListener("click", function () {
        const data = {
            status: $("#meeting_status").val(),
            segment_id: $("#segment").val(),
            start_date: $("#start_date").val(),
            end_date: $("#end_date").val(),
        };

        fetch(url+'/company-meeting-download-csv', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),  // CSRF token
            },
            body: JSON.stringify(data)
        })
        .then(response => response.blob())
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = 'client_meeting.csv';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
        })
        .catch(error => console.error('Error:', error));
    });
}

/**
 * View Company Meeting data
 */
if( $("#viewCompanyMeetingPDF").length > 0 ){
    document.getElementById("viewCompanyMeetingPDF").addEventListener("click", function () {
        var status = $("#meeting_status").val();
        var segment_id = $("#segment").val();
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();

        // Construct the URL with query parameters
        const openURL = url+`/company-meeting-view-pdf?status=${status}&segment_id=${segment_id}&start_date=${start_date}&end_date=${end_date}`;

        // Open the URL in a new tab
        window.open(openURL, '_blank');
    });
}

/**
 *
 */
if( $("#downloadAccountSummeryCSVFile").length > 0 ){
    document.getElementById("downloadAccountSummeryCSVFile").addEventListener("click", function () {
        $("#preloader").show();

        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var companyId = $("#company_id").val();
        var paymentType = $("#payment_type").val();

        const data = {
            from_date: from_date,
            to_date: to_date,
            companyId: companyId,
            paymentType: paymentType,
        };

        if( from_date == "" || to_date == "" || paymentType == "" ){
            $(".form-control").removeClass("error-border");
            $(".select2").removeClass("error-border");

            if( from_date == "" ){
                $("#from_date").addClass("error-border");
            }

            if( to_date == "" ){
                $("#to_date").addClass("error-border");
            }

            if( paymentType == null ){
                $(".select2").addClass("error-border");
            }
        } else {
            fetch(url+'/account-summery-download-csv', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),  // CSRF token
                },
                body: JSON.stringify(data)
            })
            .then(response => response.blob())
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                a.download = 'account-summery-download.xlsx';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
            })
            .catch(error => console.error('Error:', error))
            .finally(() => {
                $("#preloader").hide();
            });
        }
    });
}

/**
 * Download Account Summery data as PDF format
 */
if( $("#downloadAccountSummeryPDFFile").length > 0 ){
    document.getElementById("downloadAccountSummeryPDFFile").addEventListener("click", function () {
        $("#preloader").show();

        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var companyId = $("#company_id").val();
        var paymentType = $("#payment_type").val();

        if( from_date == "" || to_date == "" || paymentType == "" ){
            $(".form-control").removeClass("error-border");
            $(".select2").removeClass("error-border");

            if( from_date == "" ){
                $("#from_date").addClass("error-border");
            }

            if( to_date == "" ){
                $("#to_date").addClass("error-border");
            }

            if( paymentType == null ){
                $(".select2").addClass("error-border");
            }
        } else {
            // Construct the URL with query parameters
            const openURL = url+`/account-summery-download-pdf?from_date=${from_date}&to_date=${to_date}&companyId=${companyId}&paymentType=${paymentType}`;

            // Open the URL in a new tab
            window.open(openURL, '_blank');
        }
    });
}

/**
 * View Account Summery data as Table format
 */
if( $("#viewAccountSummery").length > 0 ){
    document.getElementById("viewAccountSummery").addEventListener("click", function () {
        $("#preloader").show();

        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var companyId = $("#company_id").val();
        var paymentType = $("#payment_type").val();

        if( from_date == "" || to_date == "" || paymentType == "" ){
            $(".form-control").removeClass("error-border");
            $(".select2").removeClass("error-border");

            if( from_date == "" ){
                $("#from_date").addClass("error-border");
            }

            if( to_date == "" ){
                $("#to_date").addClass("error-border");
            }

            if( paymentType == null ){
                $(".select2").addClass("error-border");
            }
        } else {
            const openURL = url+`/account-summery-view?from_date=${from_date}&to_date=${to_date}&companyId=${companyId}&paymentType=${paymentType}`;// Construct the URL with query parameters
            window.open(openURL, '_blank');//Open the URL in a new tab
        }

    });
}

/**
 * Search Specific Account Summery data as Table format
 */
if( $("#searchAccountSummery").length > 0 ){
    document.getElementById("searchAccountSummery").addEventListener("click", function () {
        $("#preloader").show();

        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var companyId = $("#company_id").val();
        var paymentType = $("#payment_type").val();

        if( from_date == "" || to_date == "" || paymentType == "" ){
            $(".form-control").removeClass("error-border");
            $(".select2").removeClass("error-border");

            if( from_date == "" ){
                $("#from_date").addClass("error-border");
            }

            if( to_date == "" ){
                $("#to_date").addClass("error-border");
            }

            if( paymentType == null ){
                $(".select2").addClass("error-border");
            }
        } else {
            const openURL = url+`/admin/company-account-summery/${companyId}?from_date=${from_date}&to_date=${to_date}&payment_type=${paymentType}`;// Construct the URL with query parameters
            window.location.href = openURL;//Open the URL in a new tab
        }

    });
}
