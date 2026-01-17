 <?php
$pageTitle = "Devotion Tourism - Country Visa";
include 'includes/head.php';
?>

    <style>
      .read-more {
        color: #ab823e;
      }

      .read-more:hover {
        color: #ab823e;
      }
    </style>
  

  <body>

    <?php include 'navbar.php'; ?>

    <section class="contact-section">
      <div class="container">
        <h2 class="display-4">Global Visa</h2>
        <p class="lead">
          Global Visa services help travelers explore the world with confidence
          and ease. From tourism to business and long-term stays, we assist with
          fast and reliable visa processing for countries across the globe. Our
          expert support ensures hassle-free documentation, transparent
          guidance, and timely approvals. Travel anywhere — we make your global
          journey smoother.
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
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body" id="descModalBody"></div>

          <h6 class="require-document">Required Documents</h6>
          <div class="modal-body" id="docuModalBody"></div>
          <h6 class="require-document">Notes</h6>
          <div class="modal-body" id="notesModalBody"></div>
        </div>
      </div>
    </div>

    <?php include 'footer.php'; ?>

    <?php include 'includes/whatsapp.php'; ?>

    <!-- menu script -->
    <script src="asset/js/menu.js"></script>
    <!-- Bootstrap bundle (includes Popper) - required for modal behavior -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="asset/js/country_visa.js"></script>

  </body>
