<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Face Authentication</title>
    <style>
        .container {
            text-align: center;
        }

        button {
            padding: 10px 20px;
            font-size: 18px;
        }
    </style>
</head>

<body>

    <div id="faceio-modal"></div>
    <script src="https://cdn.faceio.net/fio.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript">
        // Initialize the library first with your application Public ID.
        // Grab your public ID from the Application Manager on the FACEIO console at: https://console.faceio.net/
        const faceio = new faceIO("fioa8e8b"); // Replace with your application Public ID
        var faceId;
        authenticateUser();
        //  verifyuser();
        function enrollNewUser() {
            // Start the facial enrollment process
            faceio.enroll({
                "locale": "auto", // Default user locale
                "userConsent": true, // Set to true if you have already collected user consent
                "payload": {
                    /* The payload we want to associate with this particular user
                     * which is forwarded back to us on each of his future authentication...
                     */
                    "whoami": '<?php echo $_SESSION['username']; ?>', // Example of dummy ID linked to this particular user
                    "email": "sample@example.com"
                }
            }).then(userInfo => {
                // User Successfully Enrolled!
                alert(
                    `User Successfully Enrolled! Details:
                    Unique Facial ID: ${userInfo.facialId}
                    Enrollment Date: ${userInfo.timestamp}
                    Gender: ${userInfo.details.gender}
                    Age Approximation: ${userInfo.details.age}`
                );
                console.log(userInfo);
                // handle success, save the facial ID, redirect to dashboard...
                //
                // faceio.restartSession() let you enroll another user again (without reloading the entire HTML page)
            }).catch(errCode => {
                // handle enrollment failure. Visit:
                // https://faceio.net/integration-guide#error-codes
                // for the list of all possible error codes
                handleError(errCode);

                // If you want to restart the session again without refreshing the current TAB. Just call:
                faceio.restartSession();
                // restartSession() let you enroll the same or another user again (in case of failure) without refreshing the entire page.
            });
        }

        function authenticateUser() {
            // Start the facial authentication process (Identify a previously enrolled user)
            faceio.authenticate({
                "locale": "auto" // Default user locale
            }).then(userData => {
                console.log("Success, user recognized")
                // Grab the facial ID linked to this particular user which will be same
                // for each of his successful future authentication. FACEIO recommend
                // that your rely on this ID if you plan to uniquely identify
                // all enrolled users on your backend for example.
                console.log("Linked facial Id: " + userData.facialId)
                // Grab the arbitrary data you have already linked (if any) to this particular user
                // during his enrollment via the payload parameter the enroll() method takes.
                console.log("Associated Payload: " + JSON.stringify(userData.payload))
                // {"whoami": 123456, "email": "john.doe@example.com"} set via enroll()
                // Make an AJAX request to verify the user
                faceId = userData.facialId;
                verifyuser();

                // faceio.restartSession() let you authenticate another user again (without reloading the entire HTML page)
                //
            }).catch(errCode => {
                // handle authentication failure. Visit:
                // https://faceio.net/integration-guide#error-codes
                // for the list of all possible error codes
                handleError(errCode);

                // If you want to restart the session again without refreshing the current TAB. Just call:
                //	faceio.restartSession();
                // restartSession() let you authenticate the same user again (in case of failure) 
                // without refreshing the entire page.
                // restartSession() is available starting from the PRO plan and up, so think of upgrading your app
                // for user usability.
            });
        }

        function handleError(errCode) {
            // Log all possible error codes during user interaction..
            // Refer to: https://faceio.net/integration-guide#error-codes
            // for a detailed overview when these errors are triggered.
            switch (errCode) {
                case fioErrCode.PERMISSION_REFUSED:
                    console.log("Access to the Camera stream was denied by the end user");
                    alert("Access to the Camera stream was denied by the end user");
                    // window.location.href = 'signout.php';
                    break;
                case fioErrCode.NO_FACES_DETECTED:
                    console.log("No faces were detected during the enroll or authentication process");
                    alert("No faces were detected during the enroll or authentication process");
                    // window.location.href = 'signout.php';
                    break;
                case fioErrCode.UNRECOGNIZED_FACE:
                    console.log("Unrecognized face on this application's Facial Index");
                    alert("Unrecognized face on this application's Facial Index");
                    // window.location.href = 'signout.php';
                    break;
                case fioErrCode.MANY_FACES:
                    console.log("Two or more faces were detected during the scan process");
                    alert("Two or more faces were detected during the scan process");
                    // window.location.href = 'signout.php';
                    break;
                case fioErrCode.FACE_DUPLICATION:

                    console.log("User enrolled previously (facial features already recorded). Cannot enroll again!");
                    alert("User enrolled previously (facial features already recorded). Cannot enroll again!");
                    // window.location.href = 'signout.php';
                    break;
                case fioErrCode.MINORS_NOT_ALLOWED:
                    console.log("Minors are not allowed to enroll on this application!");
                    alert("Minors are not allowed to enroll on this application!");
                    // window.location.href = 'signout.php';
                    break;
                case fioErrCode.PAD_ATTACK:
                    console.log("Presentation (Spoof) Attack (PAD) detected during the scan process");
                    alert("Presentation (Spoof) Attack (PAD) detected during the scan process");
                    // window.location.href = 'signout.php';
                    break;
                case fioErrCode.FACE_MISMATCH:
                    console.log("Calculated Facial Vectors of the user being enrolled do not matches");
                    alert("Calculated Facial Vectors of the user being enrolled do not matches");
                    // window.location.href = 'signout.php';
                    break;
                case fioErrCode.WRONG_PIN_CODE:
                    console.log("Wrong PIN code supplied by the user being authenticated");
                    alert("Wrong PIN code supplied by the user being authenticated");
                    // window.location.href = 'signout.php';
                    break;
                case fioErrCode.PROCESSING_ERR:
                    console.log("Server side error");
                    alert("Server side error");
                    // window.location.href = 'signout.php';
                    break;
                case fioErrCode.UNAUTHORIZED:
                    console.log("Your application is not allowed to perform the requested operation (eg. Invalid ID, Blocked, Paused, etc.). Refer to the FACEIO Console for additional information");
                    alert("Your application is not allowed to perform the requested operation (eg. Invalid ID, Blocked, Paused, etc.). Refer to the FACEIO Console for additional information");
                    // window.location.href = 'signout.php';
                    break;
                case fioErrCode.TERMS_NOT_ACCEPTED:
                    console.log("Terms & Conditions set out by FACEIO/host application rejected by the end user");
                    alert("Terms & Conditions set out by FACEIO/host application rejected by the end user");
                    // window.location.href = 'signout.php';
                    break;
                case fioErrCode.UI_NOT_READY:
                    console.log("The FACEIO Widget could not be (or is being) injected onto the client DOM");
                    alert("The FACEIO Widget could not be (or is being) injected onto the client DOM");
                    // window.location.href = 'signout.php';
                    break;
                case fioErrCode.SESSION_EXPIRED:
                    console.log("Client session expired. The first promise was already fulfilled but the host application failed to act accordingly");
                    alert("Client session expired. The first promise was already fulfilled but the host application failed to act accordingly");
                    // window.location.href = 'signout.php';
                    break;
                case fioErrCode.TIMEOUT:
                    console.log("Ongoing operation timed out (eg, Camera access permission, ToS accept delay, Face not yet detected, Server Reply, etc.)");
                    alert("Ongoing operation timed out (eg, Camera access permission, ToS accept delay, Face not yet detected, Server Reply, etc.)");
                    // window.location.href = 'signout.php';
                    break;
                case fioErrCode.TOO_MANY_REQUESTS:
                    console.log("Widget instantiation requests exceeded for freemium applications. Does not apply for upgraded applications");
                    alert("Widget instantiation requests exceeded for freemium applications. Does not apply for upgraded applications");
                    // window.location.href = 'signout.php';
                    break;
                case fioErrCode.EMPTY_ORIGIN:
                    console.log("Origin or Referer HTTP request header is empty or missing");
                    alert("Origin or Referer HTTP request header is empty or missing");
                    // window.location.href = 'signout.php';
                    break;
                case fioErrCode.FORBIDDDEN_ORIGIN:
                    console.log("Domain origin is forbidden from instantiating fio.js");
                    alert("Domain origin is forbidden from instantiating fio.js");
                    // window.location.href = 'signout.php';
                    break;
                case fioErrCode.FORBIDDDEN_COUNTRY:
                    console.log("Country ISO-3166-1 Code is forbidden from instantiating fio.js");
                    alert("Country ISO-3166-1 Code is forbidden from instantiating fio.js");
                    // window.location.href = 'signout.php';
                    break;
                case fioErrCode.SESSION_IN_PROGRESS:
                    console.log("Another authentication or enrollment session is in progress");
                    alert("Another authentication or enrollment session is in progress");
                    // window.location.href = 'signout.php';
                    break;
                case fioErrCode.NETWORK_IO:
                default:
                    console.log("Error while establishing network connection with the target FACEIO processing node");
                    alert("Error while establishing network connection with the target FACEIO processing node");
                    // window.location.href = 'signout.php';
                    break;
            }
        }

        function verifyuser() {
            alert( faceId );
            // $.ajax({
            //     url: 'verify_face_user.php',
            //     type: 'post',
            //     data: {
            //         username: '<?php echo $_SESSION['username']; ?>',
            //         facialId: faceId
            //     },
            //     success: function(response) {

            //         if (response === 'success') {
            //             window.location.href = 'index.php';
            //         } else {
            //             alert('Not same face');
            //             window.location.href = 'login.php';
            //         }
            //     },
            //     error: function(jqXHR, textStatus, errorThrown) {
            //         console.error(textStatus, errorThrown);
            //     }
            // });
        }
    </script>
</body>

</html>