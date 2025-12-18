<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Devotion Tourism - Country Visa</title>
    <link rel="icon" href="asset/image/D-logo.png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="asset/css/custom.css">
    <style>
        .clamp-text {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            /* 2 lines */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            word-break: break-word;
        }

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

        /* Optional: cleaner fonts */
        .footer-section {
            font-family: 'Poppins', sans-serif;
        }

        .contact-section {
            background: url('asset/image/packages/bg5.avif') no-repeat center center;
            background-size: cover;
            color: white;
            /* Set text color to white for better contrast on a dark image */
            padding: 150px 0;
            /* Adjust padding as needed */
            text-align: center;
            position: relative;
        }

        /* Optional: Add an overlay to make the text more readable */
        .contact-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;

        }

        .contact-section .container {
            position: relative;
            /* Ensure container content is above the overlay */
            z-index: 1;
        }

        .contact-section h2 {
            font-family: "Merienda", cursive;
            font-size: 3.5rem;
            margin-bottom: 20px;
        }

        .contact-section p {
            font-size: 1rem;
            max-width: 1300px;
            font-weight: 500;
            margin: 0 auto;
            line-height: 1.6;
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

        /* Make both sides match height */
        .hotel-box-view {

            min-height: 300px;
            height: 100%;

        }

        .card-title {
            color: #ab823e;
        }

        .read-more {
            color: #ab823e;
        }

        .read-more:hover {
            color: #ab823e;
        }

        .visa-price {
            color: #ab823e;
        }

        .require-document {
            padding-left: 19px;
            color: #ab823e;
        }

        .accordion-button:not(.collapsed) {
            color: #ab823e;
            background-color: white;
            box-shadow: inset 0 calc(-1 * var(--bs-accordion-border-width)) 0 var(--bs-accordion-border-color);
        }


        .accordion-button:not(.collapsed)::after {
            filter: brightness(0) saturate(150%) invert(66%) sepia(15%) saturate(935%) hue-rotate(6deg) brightness(94%) contrast(100%);
        }

                .alphabet-filter {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 8px;
            margin: 20px auto;
        }

        .alphabet-filter button {
            border: 1px solid #ab823e;
            background: #fff;
            color: #ab823e;
            padding: 6px 12px;
            cursor: pointer;
            border-radius: 4px;
            transition: 0.3s;
        }

        .alphabet-filter button:hover,
        .alphabet-filter button.active {
            background: #ab823e;
            color: #fff;
        }

        /* .accordion-button:not(.collapsed) {
            color: #ffffff;
            background-color: #ab823e;
            box-shadow: inset 0 calc(-1 * var(--bs-accordion-border-width)) 0 var(--bs-accordion-border-color);
        }
  */
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg bg-white shadow-sm main-navbar">
        <div class="container">
            <style>
                /* Top Bar */
                .top-bar {
                    background-color: #ab823e;
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    z-index: 1050;
                }

                /* Remove top padding only on mobile */
                @media (max-width: 991px) {
                    body {
                        padding-top: 0 !important;
                    }
                }

                /* Mobile Dropdown Improvements */
                @media (max-width: 991px) {
                    .navbar-nav .nav-link {
                        padding: 12px 10px;
                        font-size: 16px;
                        border-bottom: 1px solid #eee;
                    }

                    .dropdown-menu {
                        width: 100% !important;
                        border: none;
                        background: #fff;
                        padding-left: 25px;
                    }

                    .dropdown-item {
                        padding: 10px 0;
                        font-size: 15px;
                        text-align: center;
                        border-bottom: 1px solid #f1f1f1;
                    }
                }

                @media (max-width: 991px) {
                    .contact-section {
                        padding-top: 30vh;
                    }
                }

                /* Desktop View */
                @media (min-width: 992px) {
                    .top-bar {
                        padding: 5px 0;
                    }
                }
            </style>
            <!-- Top Contact Bar -->
            <div class="top-bar text-white d-none d-md-block">
                <div class="container py-1">
                    <div class="row align-items-center">

                        <!-- Social Icons (Left) -->
                        <div
                            class="col-12 col-md-4 d-flex justify-content-center justify-content-md-start gap-3 mb-2 mb-md-0">
                            <a href="https://www.youtube.com/@Devotiontravelandtourism"><img
                                    src="asset/image/icons/youtube-icon.png" width="25"></a>
                            <a href="https://www.linkedin.com/company/92558268/admin/"><img
                                    src="asset/image/icons/linkedin-icon.png" width="25"></a>
                            <a href="https://www.facebook.com/profile.php?id=100091695764298"><img
                                    src="asset/image/icons/facebook-icon.png" width="25"></a>
                        </div>

                        <!-- Contact Details (Right) -->
                        <div class="col-12 col-md-8 text-center text-md-end small">
                            <span><i class="bi bi-envelope"></i> support@devotiontourism.com</span>
                            <span class="d-none d-md-inline mx-2">|</span>
                            <span><i class="bi bi-telephone"></i> +971 585775469</span>
                            <span class="d-none d-md-inline mx-2">|</span>
                            <span><i class="bi bi-printer"></i> +971 44488538</span>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Main Navbar -->
            <a class="navbar-brand ms-2" href="index.html">
                <img src="asset/image/Logo.png" alt="Devotion" style="height:50px;">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="mainNavbar">
                <ul class="navbar-nav gap-lg-3">
                    <li class="nav-item"><a href="index.html" class="nav-link fw-bold">Home</a></li>
                    <li class="nav-item"><a href="aboutus.html" class="nav-link fw-bold">About Us</a></li>

                    <!-- Packages Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-bold" href="#" id="packagesMenu" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Packages
                        </a>

                        <ul class="dropdown-menu shadow border-0 mt-2 py-2" aria-labelledby="packagesMenu">

                            <!-- Domestic -->
                            <li class="dropdown-submenu dropend">
                                <a class="dropdown-item dropdown-toggle py-1 fw-semibold" href="#">
                                    Domestic
                                </a>
                                <ul class="dropdown-menu shadow border-0">
                                    <li><a class="dropdown-item py-1" href="#">Adventure</a></li>
                                    <li><a class="dropdown-item py-1" href="#">Leisure</a></li>
                                    <li><a class="dropdown-item py-1" href="#">Desert Safari</a></li>
                                    <li><a class="dropdown-item py-1" href="#">Theme Park</a></li>
                                    <li><a class="dropdown-item py-1" href="#">Day Tour</a></li>
                                </ul>
                            </li>

                            <!-- International -->
                            <li class="dropdown-submenu dropend">
                                <a class="dropdown-item dropdown-toggle py-1 fw-semibold" href="#">
                                    International
                                </a>
                                <ul class="dropdown-menu shadow border-0">
                                    <li><a class="dropdown-item py-1" href="#">Europe</a></li>
                                    <li><a class="dropdown-item py-1" href="#">Asian</a></li>
                                    <li><a class="dropdown-item py-1" href="#">Middle East</a></li>
                                    <li><a class="dropdown-item py-1" href="#">Africa</a></li>
                                    <li><a class="dropdown-item py-1" href="#">USA & Canada</a></li>
                                </ul>
                            </li>

                        </ul>
                    </li>

                    <!-- Visa -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-bold" href="#" id="visaMenu" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Visa
                        </a>
                        <ul class="dropdown-menu shadow border-0 mt-2" aria-labelledby="visaMenu">
                            <li><a class="dropdown-item py-1" href="country_visa.html">Country Visa</a></li>
                        </ul>
                    </li>

                    <!-- Corporate Tour -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-bold" href="#" id="corporateMenu" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Corporate Tour
                        </a>
                        <ul class="dropdown-menu shadow border-0 mt-2" aria-labelledby="corporateMenu">
                            <li><a class="dropdown-item py-1" href="#">Corporate</a></li>
                            <li><a class="dropdown-item py-1" href="#">Delegation & Trade</a></li>
                            <li><a class="dropdown-item py-1" href="#">Trade Fair & Exhibition</a></li>
                            <li><a class="dropdown-item py-1" href="#">MICE</a></li>
                        </ul>
                    </li>

                    <!-- Exclusive -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-bold" href="#" id="exclusiveMenu" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Exclusive
                        </a>
                        <ul class="dropdown-menu shadow border-0 mt-2" aria-labelledby="exclusiveMenu">
                            <li><a class="dropdown-item py-1" href="#">Senior</a></li>
                            <li><a class="dropdown-item py-1" href="#">Destination Wedding</a></li>
                            <li><a class="dropdown-item py-1" href="#">Student Tour</a></li>
                            <!-- Domestic -->
                            <li class="dropdown-submenu dropend">
                                <a class="dropdown-item dropdown-toggle py-1" href="#">
                                    Gift
                                </a>
                                <ul class="dropdown-menu shadow border-0">
                                    <li><a class="dropdown-item py-1" href="#">Anniversary</a></li>
                                    <li><a class="dropdown-item py-1" href="#">Birthday</a></li>
                                    <li><a class="dropdown-item py-1" href="#">Reitrement</a></li>
                                    <li><a class="dropdown-item py-1" href="#">HoneyMoon</a></li>
                                </ul>
                            </li>

                        </ul>
                    </li>

                    <!-- Travel Guide -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-bold" href="#" id="visaMenu" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Travel Guide
                        </a>
                        <ul class="dropdown-menu shadow border-0 mt-2" aria-labelledby="visaMenu">
                            <li><a class="dropdown-item py-1" href="#">Flight Booking</a></li>
                            <li><a class="dropdown-item py-1" href="#">Passport</a></li>
                            <li><a class="dropdown-item py-1" href="#">Hotel Booking</a></li>
                            <li><a class="dropdown-item py-1" href="#">Insurance</a></li>
                            <li><a class="dropdown-item py-1" href="#">Forex</a></li>
                            <li><a class="dropdown-item py-1" href="#">Rent a car</a></li>
                        </ul>
                    </li>


                    <style>
                        /* Multi-Level Positioning */
                        .dropdown-submenu {
                            position: relative;
                        }

                        .dropdown-submenu>.dropdown-menu {
                            top: 0;
                            left: 100%;
                            margin-left: .1rem;
                        }

                        /* Hover Effect Enabled Only on Desktop */
                        @media (min-width: 992px) {
                            .dropdown-menu {
                                display: block;
                                opacity: 0;
                                visibility: hidden;
                                transform: translateY(10px);
                                transition: .25s ease;
                            }

                            .nav-item.dropdown:hover>.dropdown-menu,
                            .dropdown-submenu:hover>.dropdown-menu {
                                opacity: 1;
                                visibility: visible;
                                transform: translateY(0);
                            }
                        }

                        /* Item Styling */
                        .dropdown-item:hover {
                            background-color: #e4d3ae;
                            color: #000;
                            font-weight: 600;
                        }
                    </style>

                    <li class="nav-item"><a href="contactus.html" class="nav-link fw-bold">Contact Us</a></li>
                </ul>
            </div>

        </div>
    </nav>

    <section class="contact-section">
        <div class="container">
            <h2 class="display-4">Global Visa</h2>
            <p class="lead">
                Global Visa services help travelers explore the world with confidence and ease. From tourism to business
                and long-term stays, we assist with fast and reliable visa processing for countries across the globe.
                Our expert support ensures hassle-free documentation, transparent guidance, and timely approvals. Travel
                anywhere — we make your global journey smoother.
            </p>
        </div>
    </section>

    <!-- A-Z Filter (replace your existing alphabet-filter div) -->
    <div class="alphabet-filter" role="tablist" aria-label="Filter by alphabet">
        <button data-letter="A">A</button>
        <button data-letter="B">B</button>
        <button data-letter="C">C</button>
        <button data-letter="D">D</button>
        <button data-letter="E">E</button>
        <button data-letter="F">F</button>
        <button data-letter="G">G</button>
        <button data-letter="H">H</button>
        <button data-letter="I">I</button>
        <button data-letter="J">J</button>
        <button data-letter="K">K</button>
        <button data-letter="L">L</button>
        <button data-letter="M">M</button>
        <button data-letter="N">N</button>
        <button data-letter="O">O</button>
        <button data-letter="P">P</button>
        <button data-letter="Q">Q</button>
        <button data-letter="R">R</button>
        <button data-letter="S">S</button>
        <button data-letter="T">T</button>
        <button data-letter="U">U</button>
        <button data-letter="V">V</button>
        <button data-letter="W">W</button>
        <button data-letter="X">X</button>
        <button data-letter="Y">Y</button>
        <button data-letter="Z">Z</button>
    </div>

    <div class="container custom-container py-4">
        <div class="row" id="visa-container"></div>
    </div>

    <!-- Description Modal -->
    <div class="modal fade" id="descModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="descModalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="descModalBody"></div>

                <h6 class=" require-document">Required Documents</h6>
                <div class="modal-body" id="docuModalBody"></div>
                <h6 class=" require-document">Notes</h6>
                <div class="modal-body" id="notesModalBody"></div>
            </div>
        </div>
    </div>

    <footer class="bg-white pt-5 text-center">

        <section class="py-5" style=" background: linear-gradient(to bottom, #ffffff 0%, #eee9e5 100%);">
            <div class="container custom-container">

                <div class="row py-5">

                    <div class="col-md-3 mb-4 mb-md-0">
                        <h5 class="text-uppercase mb-3" style="color: #ab823e;">UAE Activities</h5>
                        <ul class="list-unstyled" style="line-height: 2rem;">
                            <li><a href="#" class="text-decoration-none text-muted">Desert Safari Dubai</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">Dubai City Tours</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">Dhow Cruise Dubai</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">Ras Al Khaimah Desert Safari</a>
                            </li>
                            <li><a href="#" class="text-decoration-none text-muted">Dubai Parks</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">Burj Khalifa Tours</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">Abu Dhabi City Tours</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">Dubai Theme Park</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">Dubai Water Activities</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">Abu Dhabi Water Parks</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">Dubai Adventure Tours</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">Abu Dhabi Theme Parks</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">Dubai Helicopter Tours</a></li>
                        </ul>
                    </div>

                    <div class="col-md-3 mb-4 mb-md-0">
                        <h5 class="text-uppercase  mb-3" style="color: #ab823e;">Best Selling Activities</h5>
                        <ul class="list-unstyled" style="line-height: 2rem;">
                            <li><a href="#" class="text-decoration-none text-muted">Desert Safari</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">Abu Dhabi City Tour</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">Dubai Burj Khalifa</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">Global Village Dubai</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">Atlantis Aquaventure</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">The Green Planet Dubai</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">Dhow Cruise Dinner Marina</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">Ain Dubai</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">IMG Worlds of Adventure</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">Private Limousine Rental Dubai</a>
                            </li>
                            <li><a href="#" class="text-decoration-none text-muted">Luxury Yacht Rental Dubai</a></li>
                        </ul>
                    </div>

                    <div class="col-md-3 mb-4 mb-md-0">
                        <h5 class="text-uppercase mb-3" style="color: #ab823e;">Thrilling Activities</h5>
                        <ul class="list-unstyled" style="line-height: 2rem;">
                            <li><a href="#" class="text-decoration-none text-muted">Private Limo</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">Dubai Mall Aquarium</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">Ski Dubai Tickets</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">Motiongate Dubai</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">Dhow Cruise</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">La Perle by Dragone Dubai</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">Ferrari World Abu Dhabi</a></li>
                        </ul>
                    </div>

                    <div class="col-md-3">
                        <h5 class="text-uppercase mb-3" style="color: #ab823e;">International Visa</h5>
                        <ul class="list-unstyled" style="line-height: 2rem;">
                            <li><a href="#" class="text-decoration-none text-muted">UK Visa</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">UAE Visa</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">UAE Visa Extension</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">Canada Visa</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">USA Visa</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">Turkey Visa</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">Egypt Visa</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">Singapore Visa</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">Thailand Visa</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">Malaysia Visa</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">Sri Lanka Visa</a></li>
                        </ul>
                    </div>

                </div>
            </div>
        </section>
        
        <!-- Top navigation -->
        <div class="container">

            <ul class="nav justify-content-center pt-3">
                <li class="nav-item"><a class="nav-link text-dark fw-semibold" href="index.html">Home</a></li>
                <li class="nav-item"><a class="nav-link text-dark fw-semibold" href="aboutus.html">About US</a></li>
                <li class="nav-item"><a class="nav-link text-dark fw-semibold" href="contactus.html">Contact Us</a></li>
                <li class="nav-item"><a class="nav-link text-dark fw-semibold" href="carrear.html">Career</a></li>
            </ul>
            <hr style="padding-bottom: 20px;">

            <div class="d-flex justify-content-center mb-5 align-items-center" style="gap: 2rem;">
                <div class="mb-4 d-none d-md-block">
                    <img src="asset/image/D-logo.png" alt="logo" height="100" style="width: auto;">
                </div>


                <div>
                    <div class="d-flex justify-content-center" style="gap: 3rem; margin-bottom: 0.5rem;">


                        <a href="tel:+97144576077"
                            class="d-flex align-items-center justify-content-center gap-2 contact-item"
                            style="text-decoration: none;">
                            <div class="icon-circle d-flex align-items-center justify-content-center">
                                <img src="asset/image/icons/phone-bg.png" alt="Call Icon" width="50" height="50">
                            </div>
                            <p class="fw-semibold text-dark mb-0">Call Us</p>
                        </a>


                        <a href="mailto:support@devotiontourism.com" target="_blank"
                            class="d-flex align-items-center justify-content-center gap-2 text-center contact-item"
                            style="text-decoration: none;">
                            <div class="icon-circle d-flex align-items-center justify-content-center">
                                <img src="asset/image/icons/Group3.png" alt="Email Icon" width="50" height="50">
                            </div>
                            <p class="fw-semibold text-dark mb-0">Email Us</p>
                        </a>

                        <a href="https://www.google.com/maps/dir/25.1820753,55.2590815/devotion+tourism/@25.1714707,55.2688578,13.92z/data=!4m9!4m8!1m1!4e1!1m5!1m1!1s0x3e5f69fff8df8c8f:0xf9c39d32654761bd!2m2!1d55.2602652!2d25.1859019?entry=ttu"
                            target="_blank"
                            class="d-flex contact-item align-items-center justify-content-center gap-2 text-center"
                            style="text-decoration: none;">
                            <div class="icon-circle d-flex align-items-center justify-content-center">
                                <img src="asset/image/icons/Group4.png" alt="Location Icon" width="50" height="50">
                            </div>
                            <p class="fw-semibold text-dark mb-0">Location</p>
                        </a>

                    </div>

                    <style>
                        /* ===========================
                            📱 Tablet / Medium Screens
                            =========================== */
                        @media (max-width: 992px) {
                            .contact-row {
                                gap: 2rem;
                            }

                            .contact-item p {
                                font-size: 15px;
                            }
                        }

                        /* ===========================
                        📱 Mobile Responsive
                        =========================== */
                        @media (max-width: 768px) {
                            .contact-row {
                                gap: 1.5rem;
                                flex-wrap: wrap;
                            }

                            .contact-item {
                                flex-direction: column;
                                text-align: center;
                            }

                            .contact-item p {
                                margin-top: 4px;
                                font-size: 14px;
                            }

                            .icon-circle img {
                                width: 45px;
                                height: 45px;
                            }
                        }

                        /* ===========================
                        📱 Very Small Phones
                        =========================== */
                        @media (max-width: 480px) {
                            .contact-row {
                                gap: 1.2rem;
                            }

                            .icon-circle img {
                                width: 40px;
                                height: 40px;
                            }

                            .contact-item p {
                                font-size: 13px;
                            }
                        }
                    </style>

                    <div class="d-flex justify-content-center" style="gap: 1rem; margin-top: 1rem;">
                        <a class="icon-circle-small d-flex align-items-center justify-content-center"
                            href="https://www.facebook.com/profile.php?id=100091695764298" target="_blank">
                            <img src="asset/image/icons/facebook-icon.png" width="50" height="50" alt="Facebook">
                        </a>

                        <a class="icon-circle-small d-flex align-items-center justify-content-center"
                            href="https://www.instagram.com/devotiontourism/" target="_blank">
                            <img src="asset/image/icons/insta-icon.png" width="50" height="50" alt="Instagram">
                        </a>

                        <a class="icon-circle-small d-flex align-items-center justify-content-center"
                            href="https://www.linkedin.com/company/92558268/admin/" target="_blank">
                            <img src="asset/image/icons/linkedin-icon.png" width="50" height="50" alt="LinkedIn">
                        </a>

                        <a class="icon-circle-small d-flex align-items-center justify-content-center"
                            href="https://www.youtube.com/@Devotiontravelandtourism" target="_blank">
                            <img src="asset/image/icons/youtube-icon.png" width="50" height="50" alt="YouTube">
                        </a>

                        <a class="icon-circle-small d-flex align-items-center justify-content-center"
                            href="https://pin.it/1O6HPbY" target="_blank">
                            <img src="asset/image/icons/pintrest.png" width="50" height="50" alt="Pinterest">
                        </a>
                    </div>

                </div>
            </div>
            <!-- Legal -->
            <div class="d-flex justify-content-center gap-3 small mb-2">
                <a href="privacy_policy.html" class="text-dark text-decoration-none">Privacy Policy</a>
                <span>|</span>
                <a href="terms_of_service.html" class="text-dark text-decoration-none">Terms of Service</a>
                <span>|</span>
                <a href="cookies.html" class="text-dark text-decoration-none">Cookies Settings</a>
            </div>

            <p class="small text-muted">2025 Devotion. All rights reserved.</p>
        </div>

        <!-- Skyline image -->
        <div class="mt-4">
            <img src="asset\image\footerBG.png" class="img-fluid w-100" alt="skyline">
        </div>

    </footer>

    <!-- Bootstrap bundle (includes Popper) - required for modal behavior -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // RENDER UI
        const container = document.getElementById("visa-container");
        const alphabetContainer = document.querySelector(".alphabet-filter");

        fetch("asset/json/GlobalVisa.json")
            .then(res => res.json())
            .then(json => {
                json.response.forEach(country => {
                    // determine first letter; fallback to '#'
                    const firstChar = (country.countryName || "").trim().charAt(0).toUpperCase();
                    const letter = /[A-Z]/.test(firstChar) ? firstChar : "#";

                    country.visaInfo.forEach(visa => {

                        const flagsHtml = (visa.flags || []).map((f, index) => `
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading-${visa._id}-${index}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                            data-bs-target="#collapse-${visa._id}-${index}" aria-expanded="false" 
                            aria-controls="collapse-${visa._id}-${index}">
                        ${f.serviceType}
                    </button>
                    </h2>
                    <div id="collapse-${visa._id}-${index}" class="accordion-collapse collapse" 
                        aria-labelledby="heading-${visa._id}-${index}" data-bs-parent="#accordion-${visa._id}">
                    <div class="accordion-body">
                        ${f.serviceDetails}
                    </div>
                    </div>
                </div>
                `).join("");

                        const accordionWrapper = `
                <div class="accordion" id="accordion-${visa._id}">
                ${flagsHtml}
                </div>
                `;


                        const additionalHtml = (visa.addtionalService || []).map((a, idx) => `
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading-add-${visa._id}-${idx}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-add-${visa._id}-${idx}" aria-expanded="false" aria-controls="collapse-add-${visa._id}-${idx}">
                        ${a.serviceName}
                    </button>
                    </h2>
                    <div id="collapse-add-${visa._id}-${idx}" class="accordion-collapse collapse" aria-labelledby="heading-add-${visa._id}-${idx}" data-bs-parent="#additionalAccordion-${visa._id}">
                    <div class="accordion-body">
                        ${a.serviceDescription}
                    </div>
                    </div>
                </div>
                `).join('');


                        const showReadMore = (visa.description || "").length > 10;
                        const encodedDesc = encodeURIComponent(visa.description || "");
                        const encodedDocu = encodeURIComponent(visa.reqDocuments || "");
                        const encodedNote = encodeURIComponent(visa.reqNotes || "");

                        container.innerHTML += `
              
                <div class="col-lg-6 col-md-6 mb-4 global-visa-card" data-letter="${letter}">
                <div class="card mb-4 shadow-sm h-100">
                  <div class="card-body">
                    <h5 class="card-title">${country.countryName}</h5>
                    <h6 class="text-secondary">${visa.title}</h6>

                    <p class="mt-2"><strong>Processing Days:</strong> ${country.processDaysStart}-${country.processDaysEnd} Days</p>

                    <!-- clamped description -->
                    <p class="small clamp-text description-text">
                    ${visa.description || ''}
                    </p>

                    ${showReadMore ? `
                    <button class="btn btn-link text-decoration-none border-0 shadow-none p-0 read-more"
                    data-title="${encodeURIComponent(country.countryName + ' - ' + visa.title)}"
                            data-desc="${encodedDesc}" data-docu="${encodedDocu}" data-notes="${encodedNote}">
                    Read More
                    </button>` : ''}

                    <h6 class="mt-3">Visa Details</h6>
                    <ul class="list-group mb-3">
                      ${accordionWrapper}
                    </ul>

                    <h6 class="mt-3">Additional Services</h6>
                    <div class="accordion" id="additionalAccordion-${visa._id}">
                    ${additionalHtml}
                    </div>

                    <div class="d-flex justify-content-between p-2 align-items-center">
                      <h5 class="visa-price mb-0">AED ${visa.price}</h5>
                    </div>
                  </div>
                </div>
         
              </div>`;
                    });
                });
                // after rendering, ensure default filter state (show ALL)
                applyAlphabetFilter("A"); // Default filter = A

            })
            .catch(err => {
                console.error("Failed to load JSON:", err);
                container.innerHTML = `<div class="col-12"><div class="alert alert-danger">Failed to load data.</div></div>`;
            });

        // ---------- Alphabet filter logic ----------
        function applyAlphabetFilter(letter) {
            // normalize
            const normalized = (letter || "ALL").toString().toUpperCase();
            // toggle button active class
            document.querySelectorAll(".alphabet-filter button").forEach(btn => {
                btn.classList.toggle("active", btn.dataset.letter === normalized);
            });

            const cards = document.querySelectorAll(".global-visa-card");
            cards.forEach(card => {
                const cardLetter = (card.dataset.letter || "").toUpperCase();
                if (normalized === "ALL") {
                    card.style.display = ""; // restore default (Bootstrap col will show)
                } else {
                    card.style.display = (cardLetter === normalized) ? "" : "none";
                }
            });
        }

        // attach click handlers to alphabet buttons (use event delegation)
        alphabetContainer.addEventListener("click", function (e) {
            const btn = e.target.closest("button[data-letter]");
            if (!btn) return;
            const letter = btn.dataset.letter;
            applyAlphabetFilter(letter);
            // optional: scroll to cards area when a letter is clicked
            // document.getElementById('visa-container').scrollIntoView({ behavior: 'smooth' });
        });

        // keyboard accessibility: allow Enter on focused button
        alphabetContainer.addEventListener("keydown", function (e) {
            if ((e.key === "Enter" || e.key === " ") && document.activeElement.dataset?.letter) {
                e.preventDefault();
                applyAlphabetFilter(document.activeElement.dataset.letter);
            }
        });

        // Use Bootstrap 5's Modal API (not jQuery .modal)
        $(document).on("click", ".read-more", function () {
            // decode safely
            const title = decodeURIComponent($(this).data("title") || "");
            const desc = decodeURIComponent($(this).data("desc") || "");
            const docu = decodeURIComponent($(this).data("docu") || "");
            const notes = decodeURIComponent($(this).data("notes") || "");
            // fill modal
            document.getElementById("descModalTitle").textContent = title;
            // allow HTML if you want formatting: use innerHTML carefully
            document.getElementById("descModalBody").innerHTML = desc.replace(/\n/g, "<br>");
            document.getElementById("docuModalBody").innerHTML = docu.replace(/\n/g, "<br>");
            document.getElementById("notesModalBody").innerHTML = notes.replace(/\n/g, "<br>");
            // show modal using Bootstrap 5
            const modalEl = document.getElementById('descModal');
            const bsModal = new bootstrap.Modal(modalEl);
            bsModal.show();
        });

        $(document).ready(function () {
            $('#descModal').on('show.bs.modal', function () {
                $('nav.navbar, footer').fadeOut(200);
            });

            $('#descModal').on('hide.bs.modal', function () {
                $('nav.navbar, footer').fadeIn(200);
            });
        });

    </script>

</body>
</html>