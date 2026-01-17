<?php
$pageTitle = "Devotion Tourism - Carrear";
include 'includes/head.php';
?>

<body>

  <?php include 'navbar.php'; ?>

  <section class="career-section">
    <div class="container custom-container text-white">
      <div class="row align-items-center">
        <!-- Left Image -->
        <div class="col-md-6 text-center mb-4 mb-md-0">
          <img
            src="asset\image\333.webp"
            alt="Career Image"
            class="img-fluid career-image" />
        </div>

        <!-- Right Form -->
        <div class="col-md-6">
          <h2 class="mb-4 text-dark">Career</h2>

          <form class="career-form">
            <input
              type="text"
              class="form-control mb-3"
              id="name"
              placeholder="Name" />

            <input
              type="email"
              class="form-control mb-3"
              id="email"
              placeholder="Email" />

            <div class="input-group mb-3">
              <span class="input-group-text">AED</span>
              <input
                type="tel"
                class="form-control"
                id="mobileNumber"
                placeholder="Mobile Number" />
            </div>

            <input
              type="text"
              class="form-control mb-3"
              id="designation"
              placeholder="Designation" />

            <input
              type="text"
              class="form-control mb-3"
              id="totalExperience"
              placeholder="Total Experience" />

            <div class="mb-3">
              <label for="uploadResume" class="form-label text-dark">Upload Resume</label>
              <input type="file" class="form-control" id="uploadResume" />
            </div>

            <button
              type="submit"
              class="btn submit-btn w-100"
              style="background-color: #ab823e; color: white">
              Submit Now
            </button>
          </form>
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
 