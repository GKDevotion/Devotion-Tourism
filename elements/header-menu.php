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
                    padding-top: -96 !important;
                }
            }

            @media (max-width: 991px) {
                .hero-overlay {
                    padding-top: 20vh;
                }
            }

            /* Navbar should stick directly to top on mobile */
            @media (max-width: 991px) {
                body {
                    padding-top: 0 !important;
                    /* Remove space above content */
                    margin-top: 0 !important;
                }

                .main-navbar {
                    margin-top: 0 !important;
                    /* Remove navbar margin */
                    top: 0;
                }

                .top-bar {
                    display: none !important;
                    /* Hide top bar on mobile */
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
                    padding-top: 65vh;
                }
            }

            @media (max-width: 991px) {
                .contact-section .container {
                    padding-bottom: 30vh;
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
                                src="../asset/image/icons/youtube-icon.png" width="25"></a>
                        <a href="https://www.linkedin.com/company/92558268/admin/"><img
                                src="../asset/image/icons/linkedin-icon.png" width="25"></a>
                        <a href="https://www.facebook.com/profile.php?id=100091695764298"><img
                                src="../asset/image/icons/facebook-icon.png" width="25"></a>
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
            <img src="../asset/image/Logo.png" alt="Devotion" style="height:50px;">
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
                <li class="nav-item"><a href="visa.html" class="nav-link fw-bold">Global Visa</a></li>
                <li class="nav-item"><a href="contactus.html" class="nav-link fw-bold">Contact Us</a></li>
            </ul>
        </div>

    </div>
</nav>