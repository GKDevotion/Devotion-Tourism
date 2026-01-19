 
<?php
$pageTitle = "Devotion Tourism - Contact Us";
include 'includes/head.php';
?>

<body>

  <?php include 'navbar.php'; ?>

  <section class="contact-section">
    <div class="container">
      <h2 class="display-4">Contact our team</h2>
      <p class="lead">
        We are a leading travel and tourism company that specializes in flight
        booking, hotel booking, and corporate travel services. With our
        unwavering commitment to excellence and personalized service, we
        strive to make your travel experiences truly memorable.
      </p>
    </div>
  </section>

  <section class="py-5" style="background-color: #ffffff">
    <div class="container custom-container">
      <div class="row">
        <div class="col-lg-6 mb-4 mb-lg-0">
          <div class="card shadow-sm border-0">
            <div class="card-body p-4">
              <h2 class="card-title fs-4 mb-4">Request Callback</h2>

              <form>
                <div class="mb-3">
                  <label for="fullName" class="form-label fw-semibold">Name</label>
                  <input
                    type="text"
                    class="form-control"
                    id="fullName"
                    placeholder="Full Name" />
                </div>

                <div class="mb-3">
                  <label for="emailID" class="form-label fw-semibold">Email</label>
                  <input
                    type="email"
                    class="form-control"
                    id="emailID"
                    placeholder="Enter Email ID" />
                </div>

                <div class="mb-3">
                  <label for="mobileNumber" class="form-label fw-semibold">Contact Number</label>
                  <div class="input-group">
                    <input
                      type="text"
                      class="form-control"
                      value="+91"
                      style="max-width: 60px" />
                    <input
                      type="text"
                      class="form-control"
                      id="mobileNumber"
                      placeholder="Mobile Number" />
                  </div>
                </div>

                <div class="mb-3">
                  <label for="messageText" class="form-label fw-semibold">Message</label>
                  <textarea
                    class="form-control"
                    id="messageText"
                    rows="4"
                    placeholder="Enter Message"></textarea>
                </div>

                <div class="mt-4">
                  <label class="form-label fw-semibold mb-2">Services</label>
                  <div class="d-flex flex-wrap gap-3">
                    <div class="form-check">
                      <input
                        class="form-check-input"
                        type="radio"
                        name="serviceRadio"
                        id="flightBookings"
                        checked />
                      <label class="form-check-label" for="flightBookings">
                        Flight Bookings
                      </label>
                    </div>
                    <div class="form-check">
                      <input
                        class="form-check-input"
                        type="radio"
                        name="serviceRadio"
                        id="corporateBookings" />
                      <label class="form-check-label" for="corporateBookings">
                        Corporate Bookings
                      </label>
                    </div>
                    <div class="form-check">
                      <input
                        class="form-check-input"
                        type="radio"
                        name="serviceRadio"
                        id="packages" />
                      <label class="form-check-label" for="packages">
                        Packages
                      </label>
                    </div>
                    <div class="form-check">
                      <input
                        class="form-check-input"
                        type="radio"
                        name="serviceRadio"
                        id="globalVisa" />
                      <label class="form-check-label" for="globalVisa">
                        Global Visa
                      </label>
                    </div>
                  </div>
                </div>

                <button
                  type="submit"
                  class="btn btn-lg mt-4"
                  style="
                      background-color: #ab823e;
                      color: white;
                      border-color: #ab823e;
                    ">
                  Send Request
                </button>
              </form>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="card shadow-sm border-0">
            <div class="card-body p-4">
              <!-- Location Section -->
              <h2 class="fs-5 mb-3">Location</h2>

              <div class="d-flex align-items-center mb-4 text-start">
                <div
                  class="icon-circle me-3 d-flex align-items-center justify-content-center">
                  <img
                    src="asset/image//icons/location.svg"
                    width="20"
                    height="20"
                    alt="Location" />
                </div>
                <p class="mb-0 text-muted w-100">
                  Aspect Tower, Bay Avenue - 2801, A Zone, Business Bay, Dubai
                  UAE
                </p>
              </div>

              <!-- Contact Section -->
              <h2 class="fs-5 mb-3 mt-4">Get in Touch</h2>

              <div class="d-flex align-items-center mb-4 text-start">
                <div
                  class="icon-circle me-3 d-flex align-items-center justify-content-center">
                  <img
                    src="asset/image/icons/call.svg"
                    width="20"
                    height="20"
                    alt="Phone" />
                </div>
                <a
                  href="tel:+971 585775469"
                  class="text-muted mb-0 text-decoration-none w-100">
                  +971 58 577 5469
                </a>
                <!-- <p class="mb-0 text-muted w-100">+971 58 577 5469</p> -->
              </div>

              <!-- Email -->
              <div class="d-flex align-items-center mb-4 text-start">
                <div
                  class="icon-circle me-3 d-flex align-items-center justify-content-center">
                  <img
                    src="asset/image/icons/emil.svg"
                    width="20"
                    height="20"
                    alt="Email" />
                </div>
                <a
                  href="mailto:support@devotiontourism.com"
                  class="text-muted mb-0 text-decoration-none w-100">
                  support@devotiontourism.com
                </a>
              </div>

              <div class="d-flex align-items-center mb-4 text-start">
                <div
                  class="icon-circle me-3 d-flex align-items-center justify-content-center">
                  <img
                    src="asset/image/icons/whatsapPng.png"
                    width="20"
                    height="20"
                    alt="WhatsApp" />
                </div>
                <a
                  href="https://wa.me/971585775469?text=Hi%20Devotion%20Tourism!%20I%20need%20more%20info%20about%20your%20services"
                  target="_blank"
                  class="text-muted text-decoration-none w-100" aria-label="Chat on WhatsApp">
                  Whatsapp
                </a>
              </div>

              <!-- Social Links -->
              <h2 class="fs-5 mt-4 mb-3">Social Links</h2>
              <div class="d-flex gap-3">
                <a
                  href="https://www.instagram.com/devotiontourism/"
                  class="social-btn d-flex align-items-center justify-content-center">
                  <img
                    src="asset/image/icons/insta.svg"
                    width="30"
                    height="30"
                    alt="Instagram" />
                </a>

                <a
                  href="https://www.linkedin.com/company/92558268/admin/"
                  class="social-btn d-flex align-items-center justify-content-center">
                  <img
                    src="asset/image/icons/linkdn.svg"
                    width="30"
                    height="30"
                    alt="LinkedIn" />
                </a>

                <a
                  href="https://www.linkedin.com/company/devotiontourism/"
                  class="social-btn d-flex align-items-center justify-content-center">
                  <img
                    src="asset/image/icons/twitter.svg"
                    width="30"
                    height="30"
                    alt="Twitter" />
                </a>

                <a
                  href="https://www.youtube.com/@Devotiontravelandtourism"
                  class="social-btn d-flex align-items-center justify-content-center">
                  <img
                    src="asset/image/icons/youtub.svg"
                    width="30"
                    height="30"
                    alt="YouTube" />
                </a>
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
 