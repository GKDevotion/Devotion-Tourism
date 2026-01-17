 <?php
  $pageTitle = "Devotion Tourism - Packages";
  include 'includes/head.php';
  ?>


 <style>
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
 </style>


 <body>

   <?php include 'navbar.php'; ?>

   <div class="custom-container py-5 mt-5">
     <h2 id="page-title" class="mb-4 fw-bold"></h2>

     <div class="row" id="package-container"></div>
   </div>

   <?php include 'footer.php'; ?>

   <?php include 'includes/whatsapp.php'; ?>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
   <!-- menu script -->
   <script src="asset/js/menu.js"></script>
   <script src="asset/js/package_detail.js"></script>

 </body>