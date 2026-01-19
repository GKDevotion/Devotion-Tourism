<?php
$pageTitle = "Devotion Tourism - Trade Fair and Exhibitions";
include 'includes/head.php';
?>
 
<style>
  .trade-fair-section {
    background: #fff;
  }

  .small-title {
    font-family: cursive;
    color: #ab823e;
    font-size: 16px;
  }

  .main-title {
    font-weight: 800;
    font-size: 48px;
  }

  .divider {
    width: 60%;
    height: 4px;
    background: #2f3542;
    margin-top: 30px;
  }

  /* IMAGE COLLAGE */
  .image-collage {
    min-height: 500px;
  }

  .main-img {
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    max-width: 100%;
    height: 350px;
  }

  /* RESPONSIVE */
  @media (max-width: 991px) {
    .image-collage {
      margin-top: 40px;
      min-height: auto;
    }

    .main-title {
      font-size: 36px;
    }
  }

  .trade-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s ease;
  }

  .trade-card:hover {
    transform: translateY(-6px);
  }

  .trade-top {
    background: #fff;
    font-size: 14px;
  }

  .trade-top .days {
    color: #20c997;
    font-weight: 500;
  }

  .trade-top .icons i {
    margin-left: 10px;
    cursor: pointer;
    color: #20c997;
  }

  .trade-img {
    height: 220px;
    object-fit: cover;
    width: 100%;
  }

  .placeholder-img {
    height: 220px;
    background: #f2f2f2;
  }

  .location {
    font-size: 14px;
  }

  .location i {
    color: #ab823e;
  }

  .enquiry {
    font-size: 13px;
    color: #ab823e;
    font-weight: 500;
    cursor: pointer;
  }

  .btn-read {
    background-color: #ab823e;
    color: white;
  }
</style> 

<body>

  <?php include 'navbar.php'; ?>

  <section class="trade-fair-section py-5 mt-5">
    <div class="custom-container">
      <div class="row align-items-center">
        <!-- LEFT CONTENT -->
        <div class="col-lg-6">
          <span class="small-title">Experience perfection with us on every journey.</span>
          <h1 class="main-title mt-2">Trade Fair Travel</h1>

          <p class="text-muted mt-3">
            Travel & Tape is one of the Best Trade Fair agency in mumbai
            India. We offer excellent Trade Fair packages that include all the
            essentials such as visa services, air ticket, accommodation,
            meals, transfers to & from the Trade Fair Centre & Airport etc.
          </p>

          <p class="text-muted">
            We have an excellent team of Travel Professionals which ensures
            that you have a smooth stay wherever you travel for Trade Fairs in
            the world.
          </p>

          <div class="divider mt-4"></div>
        </div>

        <!-- RIGHT IMAGE -->
        <div class="col-lg-6 text-center">
          <img
            src="asset/image/packages/trade-fair.jfif"
            class="img-fluid main-img"
            alt="Trade Fair" />
        </div>
      </div>
    </div>
  </section>

  <section class="py-5 bg-light">
    <div class="custom-container">
      <!-- Heading -->
      <div class="text-center mb-5">
        <small class="small-title d-block mb-2">Let us help you plan your next tour</small>
        <h2 class="fw-bold">Upcoming Trade Fairs</h2>
      </div>

      <!-- Cards Row -->
      <div class="row g-4">
        <!-- Card 1 -->
        <div class="col-lg-4 col-md-6">
          <div class="trade-card h-100">
            <div
              class="trade-top d-flex justify-content-between align-items-center px-3 py-2">
              <span class="days"><i class="bi bi-clock"></i> 4 days</span>
              <div class="icons">
                <i class="bi bi-envelope"></i>
                <i class="bi bi-bookmark"></i>
              </div>
            </div>

            <img
              src="asset\image\packages\thailand.jfif"
              class="img-fluid trade-img"
              alt="" />

            <div class="p-3">
              <h5 class="fw-semibold">Chinaplas 2026</h5>

              <p class="location mb-2">
                <i class="bi bi-geo-alt-fill"></i> Shanghai, China
                <span class="float-end enquiry">Enquiry Now</span>
              </p>

              <p class="text-muted small">
                21 – 24 April 2026 Shanghai, PR China CHINAPLAS 2026 – Asia’s
                Leading Plastics & Rubber Technology Event...
              </p>

              <div
                class="d-flex justify-content-between align-items-center mt-4">
                <a href="#" class="btn btn-read btn-sm px-4">Read More</a>
                <strong>21 April, 2026</strong>
              </div>
            </div>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="col-lg-4 col-md-6">
          <div class="trade-card h-100">
            <div
              class="trade-top d-flex justify-content-between align-items-center px-3 py-2">
              <span class="days"><i class="bi bi-clock"></i> 4 Days</span>
              <div class="icons">
                <i class="bi bi-envelope"></i>
                <i class="bi bi-bookmark"></i>
              </div>
            </div>

            <img
              src="asset\image\packages\thailand2.jpg"
              class="img-fluid trade-img"
              alt="" />

            <div class="p-3">
              <h5 class="fw-semibold">World Health Expo (WHX) 2026</h5>

              <p class="location mb-2">
                <i class="bi bi-geo-alt-fill"></i> Dubai, UAE
                <span class="float-end enquiry">Enquiry Now</span>
              </p>

              <p class="text-muted small">
                09 – 12 Feb 2026 Dubai, UAE WHX Dubai 2026 World Health Expo
                Discover the future...
              </p>

              <div
                class="d-flex justify-content-between align-items-center mt-4">
                <a href="#" class="btn btn-read btn-sm px-4">Read More</a>
                <strong>9 February, 2026</strong>
              </div>
            </div>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="col-lg-4 col-md-6">
          <div class="trade-card h-100">
            <div
              class="trade-top d-flex justify-content-between align-items-center px-3 py-2">
              <span class="days"><i class="bi bi-clock"></i> 20 days</span>
              <div class="icons">
                <i class="bi bi-envelope"></i>
                <i class="bi bi-bookmark"></i>
              </div>
            </div>

            <img
              src="asset\image\packages\thailand1.jfif"
              class="img-fluid trade-img"
              alt="" />

            <div class="p-3">
              <h5 class="fw-semibold">CANTON 2026</h5>

              <p class="location mb-2">
                <i class="bi bi-geo-alt-fill"></i> Guangzhou, China
                <span class="float-end enquiry">Enquiry Now</span>
              </p>

              <p class="text-muted small">
                15 Apr – 05 May 2026 Guangzhou, China Canton Fair – China
                Import & Export Fair 139th Edition...
              </p>

              <div
                class="d-flex justify-content-between align-items-center mt-4">
                <a href="#" class="btn btn-read btn-sm px-4">Read More</a>
                <strong>15 April, 2026</strong>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <?php include 'footer.php'; ?>

  <?php include 'includes/whatsapp.php'; ?>

  <!-- menu script -->
  <script src="asset/js/menu.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
</body>

 