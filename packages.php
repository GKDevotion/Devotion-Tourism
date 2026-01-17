<?php
$pageTitle = "Devotion Tourism - Packages";
include 'includes/head.php';
?>

<style>
  body {
    padding-top: 45px;
    /* Adjust if needed */
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
    padding-top: 0.5rem !important;
    padding-bottom: 1.5rem !important;
  }

  /* Optional: cleaner fonts */
  .footer-section {
    font-family: "Poppins", sans-serif;
  }

  .contact-section {
    background: url("asset/image/packages/bg5.avif") no-repeat center center;
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
    content: "";
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
    background-color: #a3865d10;
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

  /* Card */
  .tour-card {
    transition: all 0.3s ease;
    border-radius: 14px;
    overflow: hidden;
  }

  .tour-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
  }

  /* Image */
  .tour-img-wrapper {
    position: relative;
    overflow: hidden;
  }

  .tour-img-wrapper img {
    height: 220px;
    object-fit: cover;
  }

  /* Duration badge */
  .tour-duration {
    position: absolute;
    bottom: 12px;
    left: 12px;
    background: rgba(0, 0, 0, 0.75);
    color: #fff;
    padding: 4px 10px;
    font-size: 12px;
    border-radius: 20px;
  }

  /* Info list */
  .tour-info li {
    margin-bottom: 6px;
    color: #555;
  }

  /* Price */
  .price-box {
    display: flex;
    align-items: baseline;
    gap: 6px;
  }

  .price {
    font-size: 20px;
    font-weight: 700;
    color: #ab823e;
  }

  .price-note {
    font-size: 13px;
    color: #777;
  }
</style>

<body>

  <?php include 'navbar.php'; ?>

  <div class="custom-container">
    <div class="row" style="margin-top: 100px">
      <h1 class="text-center" style="font-family: 'Merienda', cursive">
        Explore Our <span id="category-name"></span> Packages
      </h1>
    </div>

    <div class="row" id="package-container" style="margin-top: 30px"></div>
  </div>

  <?php include 'footer.php'; ?>

  <?php include 'includes/whatsapp.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- menu script -->
  <script src="asset/js/menu.js"></script>
  <script src="asset/js/packages.js"></script>

</body>