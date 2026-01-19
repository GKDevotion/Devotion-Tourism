 <?php
  $pageTitle = "Devotion Tourism - Corporate Tour";
  include 'includes/head.php';
  ?>

 <style>
   .nav-pills .nav-link.active,
   .nav-pills .show>.nav-link {
     color: var(--bs-nav-pills-link-active-color);
     background-color: #ab823e;
     border-radius: 100px;
   }

   .object-fit-cover {
     object-fit: cover;
   }

   .btn-request {
     background-color: #ab823e;
     color: #ffffff;
   }

   .bi-check-circle-fill {
     color: #ab823e;
   }

   .corporate-service-card {
     border: 1px solid #eee;
     border-radius: 14px;
     transition: all 0.4s ease;
     background: #fff;
   }

   .corporate-service-card:hover {
     transform: translateY(-8px);
     box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
   }

   .service-img {
     color: #ab823e;
   }

   .icon-box {
     width: 80px;
     height: 80px;
     margin: 0 auto;
   }

   .corporate-service-card {
     width: 100%;
     object-fit: contain;
     transition: transform 0.4s ease;
   }

   .corporate-service-card:hover .service-img {
     transform: scale(1.15);
   }

   .package-card {
     background: #fff;
     border-radius: 14px;
     overflow: hidden;
     transition: all 0.3s ease;
   }

   .package-card:hover {
     border: 1px solid black;
     box-shadow: 0 15px 25px rgba(0, 0, 0, 0.2);
   }

   /* Image */
   .package-img {
     height: 220px;
     object-fit: cover;
   }

   /* Duration badge */
   .duration-badge {
     position: absolute;
     left: 12px;
     bottom: 12px;
     background: rgba(0, 0, 0, 0.75);
     color: #fff;
     font-size: 12px;
     padding: 6px 10px;
     border-radius: 20px;
   }

   .corporate-prev,
   .corporate-next {
     position: static;
     /* VERY IMPORTANT */
     width: 38px;
     height: 38px;
     border-radius: 50%;
     background: #ab823e;
     color: #fff;
     display: flex;
     align-items: center;
     justify-content: center;
   }

   .corporate-prev::after,
   .corporate-next::after {
     font-size: 14px;
     font-weight: bold;
   }

   .enquiry-section {
     background: #fff;
     border-radius: 20px;
     padding: 40px;
     box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
   }

   .form-control,
   .form-select {
     padding-left: 45px;
     height: 50px;
     background-color: #f5f7fb;
     border: none;
   }

   .input-icon {
     position: absolute;
     top: 50%;
     left: 18px;
     transform: translateY(-50%);
     color: #6c757d;
   }

   .btn-enquire {
     background-color: #ab823e;
     border-radius: 30px;
     padding: 12px 40px;
     color: #fff;
     font-weight: 600;
   }

   .info-text {
     font-size: 14px;
     color: #555;
   }

   .btn-enquire:hover {
     background-color: #ab823e;
     border-radius: 30px;
     padding: 12px 40px;
     color: #fff;
     font-weight: 600;
   }
 </style>

 <body>

   <?php include 'navbar.php'; ?>

   <section class="py-5 mt-5">
     <div class="custom-container">
       <div class="row align-items-center bg-light rounded-4 p-4 p-lg-5">
         <!-- LEFT CONTENT -->
         <div class="col-lg-6 mb-4 mb-lg-0">
           <h1 class="fw-bold mb-3">
             Corporate Tours & <br />
             Team Offsites
           </h1>

           <p class="text-muted mb-4">
             Redefine your corporate vibe with handcrafted trip itineraries by
             Go4Explore
           </p>

           <ul class="list-unstyled mb-4">
             <li class="mb-2 d-flex align-items-center">
               <i class="bi bi-check-circle-fill me-2"></i>
               100% Privacy Guaranteed
             </li>
             <li class="mb-2 d-flex align-items-center">
               <i class="bi bi-check-circle-fill me-2"></i>
               No Spam Calls / Messages
             </li>
             <li class="d-flex align-items-center">
               <i class="bi bi-check-circle-fill me-2"></i>
               Quick Response within 24 hrs
             </li>
           </ul>

           <a href="#" class="btn btn-request px-4 py-2 rounded-pill">
             Request Callback
           </a>
         </div>

         <!-- RIGHT IMAGE (ONLY ONE IMAGE) -->
         <div class="col-lg-6 text-center">
           <img src="asset\image\mice\Corporate.jpeg" alt="Corporate Tour" class="img-fluid rounded-4 shadow"
             style="height: 420px; object-fit: cover; width: 100%" />
         </div>
       </div>
     </div>
   </section>

   <section class="py-5">
     <div class="custom-container">
       <!-- Section Title -->
       <div class="text-center mb-5">
         <h2 class="fw-bold">Corporate Services Offered</h2>
       </div>

       <!-- Cards -->
       <div class="row g-4">
         <!-- Card 1 -->
         <div class="col-lg-3 col-md-6">
           <div class="corporate-service-card text-center p-4 h-100">
             <div class="icon-box mb-3">
               <img src="asset\image\mice\corporate-trip.png" alt="Corporate Trips" class="img-fluid service-img" />
             </div>

             <h5 class="fw-semibold mb-2">Corporate Trips</h5>
             <p class="text-muted small">
               Bring your work crew together on a trip to elevate team spirit &
               performances.
             </p>
           </div>
         </div>

         <!-- Card 2 -->
         <div class="col-lg-3 col-md-6">
           <div class="corporate-service-card text-center p-4 h-100">
             <div class="icon-box mb-3">
               <img src="asset\image\mice\incentive.png" alt="Team Incentive Travel" class="img-fluid service-img" />
             </div>

             <h5 class="fw-semibold mb-2">Team Incentive Travel</h5>
             <p class="text-muted small">
               Travel experiences designed to foster team bonding & enjoyment
               outside office.
             </p>
           </div>
         </div>

         <!-- Card 3 -->
         <div class="col-lg-3 col-md-6">
           <div class="corporate-service-card text-center p-4 h-100">
             <div class="icon-box mb-3">
               <img src="asset\image\mice\travel.png" alt="MICE" class="img-fluid service-img" />
             </div>

             <h5 class="fw-semibold mb-2">MICE</h5>
             <p class="text-muted small">
               Transform Meetings, Incentives, Conferences & Events into
               extraordinary.
             </p>
           </div>
         </div>

         <!-- Card 4 -->
         <div class="col-lg-3 col-md-6">
           <div class="corporate-service-card text-center p-4 h-100">
             <div class="icon-box mb-3">
               <img src="asset\image\mice\incentive.png" alt="Vendor Incentive Plan" class="img-fluid service-img" />
             </div>

             <h5 class="fw-semibold mb-2">Vendor Incentive Plan</h5>
             <p class="text-muted small">
               Experiences designed to motivate & reward vendors, suppliers or
               partners.
             </p>
           </div>
         </div>
       </div>
     </div>
   </section>

   <section class="py-5">
     <div class="custom-container">
       <!-- Title + Arrows Row -->
       <div class="d-flex justify-content-between align-items-center mb-4">
         <h3 class="fw-semibold mb-0">Corporate Tour Packages</h3>

         <div class="d-flex gap-2">
           <div class="swiper-button-prev corporate-prev"></div>
           <div class="swiper-button-next corporate-next"></div>
         </div>
       </div>

       <!-- Swiper -->
       <div class="swiper corporateSwiper">
         <div class="swiper-wrapper" id="corporatePackageWrapper">
           <!-- JS inject slides -->
         </div>
       </div>
     </div>
   </section>

   <section class="py-5 mt-5">
     <div class="custom-container">
       <div class="row align-items-center bg-light rounded-4 p-4 p-lg-5">
         <div class="enquiry-section">
           <div class="row g-4 align-items-center">
             <!-- LEFT CONTENT -->
             <div class="col-lg-6">
               <h2 class="fw-bold">Let's plan your next trip</h2>
               <p class="text-muted">
                 Make your move, fill out your details now!
               </p>
             </div>

             <!-- RIGHT FORM -->
             <div class="col-lg-6">
               <form>
                 <div class="row g-3">
                   <div class="col-md-6 position-relative">
                     <i class="bi bi-person input-icon"></i>
                     <input type="text" class="form-control" placeholder="Full Name" />
                   </div>

                   <div class="col-md-6 position-relative">
                     <i class="bi bi-envelope input-icon"></i>
                     <input type="email" class="form-control" placeholder="Email Address" />
                   </div>

                   <div class="col-md-6 position-relative">
                     <i class="bi bi-telephone input-icon"></i>
                     <input type="text" class="form-control" placeholder="Mobile Number" />
                   </div>

                   <div class="col-md-6 position-relative">
                     <i class="bi bi-people input-icon"></i>
                     <select class="form-select">
                       <option selected>Number of Travellers</option>
                       <option>1</option>
                       <option>2</option>
                       <option>3+</option>
                     </select>
                   </div>

                   <div class="col-md-6 position-relative">
                     <i class="bi bi-calendar input-icon"></i>
                     <select class="form-select">
                       <option selected>Month of Travel</option>
                       <option>January</option>
                       <option>February</option>
                       <option>March</option>
                     </select>
                   </div>

                   <div class="col-md-6 position-relative">
                     <i class="bi bi-building input-icon"></i>
                     <input type="text" class="form-control" placeholder="Company Name" />
                   </div>

                   <div class="col-12 position-relative">
                     <i class="bi bi-chat-dots input-icon"></i>
                     <textarea class="form-control" rows="2" placeholder="Destination / Message (Optional)"></textarea>
                   </div>

                   <div class="col-12 text-center mt-3">
                     <button class="btn btn-enquire">Enquire Now</button>
                   </div>
                 </div>
               </form>
             </div>
           </div>

           <!-- FOOTER INFO -->
           <div class="row mt-4 text-center info-text">
             <div class="col-md-4">✔ 100% Privacy Guaranteed</div>
             <div class="col-md-4">✔ No Spam Calls/Messages</div>
             <div class="col-md-4">✔ Quick Response within 24 hrs</div>
           </div>
         </div>
       </div>
     </div>
   </section>

   <?php include 'footer.php'; ?>

   <?php include 'includes/whatsapp.php'; ?>

   <!-- menu script -->
   <script src="asset/js/menu.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="asset/js/corporate_tour.js"></script>
   
 </body>

