<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <title>Devotion Tourism</title>
        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="icon" href="../asset/image/D-logo.png">
        <link rel="stylesheet" href="asset/css/custom.css">
        <link  href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"  rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"  rel="stylesheet">
        <!-- bootstrap 5.3 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Merienda:wght@300..900&display=swap" rel="stylesheet">

        <style>
            body {
                padding-top: 45px;
                /* Adjust if needed */
            }

            .navbar {
                position: fixed;

                text-align: center;
                left: 0;
                background-color: #ffffff;
                z-index: 1060;
                width: 100%;
                border-radius: 0px;
                box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
            }

            @media (min-width: 1400px) {

                .container,
                .container-lg,
                .container-md,
                .container-sm,
                .container-xl,
                .container-xxl {
                    max-width: 1838px;
                }
            }

            .py-2 {
                padding-top: .5rem !important;
                padding-bottom: 1.5rem !important;
            }

            /* Beige icon circle (large icons for Call/Email/Location) */
            .icon-circle {
                width: 50px;
                height: 50px;
                background: #f7ecd7;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 26px;
                color: #b8903b;
                /* golden brown icon color */
                margin: auto;
            }

            /* Small beige circle for social icons */
            .icon-circle-small {
                width: 50px;
                height: 50px;
                background: #f7ecd7;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #b8903b;
                font-size: 26px;
                text-decoration: none;
            }

            .icon-circle-small:hover {
                opacity: 0.8;
            }

            /* Optional: cleaner fonts */
            .footer-section {
                font-family: 'Poppins', sans-serif;
            }




            @media (min-width: 1400px) {
                .custom-container {
                    max-width: 1320px;
                    margin: 0 auto;
                }
            }

            /* Change radio button checked color */
            .form-check-input:checked {
                background-color: #ab823e !important;
                border-color: #ab823e !important;
            }

            /* Optional: change hover/focus outline */
            .form-check-input:focus {
                border-color: #ab823e !important;
                box-shadow: 0 0 0 0.25rem rgba(171, 130, 62, 0.25) !important;
            }

            .icon-circle {
                height: 46px;
                width: 46px;
                background-color: #f2f2f2;
                border-radius: 50%;
                display: flex;
                justify-content: center;
                align-items: center;
                color: #ab823e;
                font-size: 20px;
            }

            .contact-item {
                display: flex;
                align-items: center;
                margin-bottom: 15px;
                font-size: 15px;
            }

            .contact-icon {
                color: #ab823e;
                font-size: 20px;
                margin-right: 12px;
            }

            .social-btn {
                height: 40px;
                width: 40px;
                border: 1px solid #ab823e;
                display: flex;
                justify-content: center;
                align-items: center;
                border-radius: 50%;
                background-color: #A3865D10;
                font-size: 18px;
                color: #ab823e;
                text-decoration: none;
                transition: 0.3s ease-in-out;
            }

            .social-btn:hover {
                background-color: #ab823e;
                color: white;
                transform: scale(1.1);
            }

            footer ul,
            footer h5,
            footer li,
            footer a {
                text-align: left !important;
            }

            .paragraph {
                padding-bottom: 30px;
            }

            .heading {
                font-size: 22px;
                color: #ab823e;
            }
        </style>
        
    </head>

    <body>
        <?php include_once('elements/header-menu.php'); ?>